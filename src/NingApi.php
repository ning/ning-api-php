<?php

require_once('OAuth.php');
require_once('NingException.php');
require_once('NingObject.php');

class NingApi {
    const SECURE_PROTOCOL = 'https://';
    const INSECURE_PROTOCOL = 'http://';
    const BASE_URL = 'external.ningapis.com/xn/rest';
    const API_VERSION = '1.0';

    // The maximum number of seconds to allow cURL to execute
    const CURL_TIMEOUT = 10;
    // Ning network subdomain (ie. 'apiexample' in apiexample.ning.com)
    protected $subdomain = 'apiexample';
    // Ning user email address
    protected $email = 'user@email.com';
    // Ning user password
    protected $password = 'password';
    // Consumer key found at [subdomain].ning.com/main/extend/keys
    protected $consumerKey = '12345678-1234-1234-1234-abcd1234abcd';
    // Consumer secret found at [subdomain].ning.com/main/extend/keys
    protected $consumerSecret = '1234abcd-1234-abcd-1234-abcd1234abcd';
    protected $requestToken = null;
    private static $_instance = null;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new NingApi();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->_initAuthTokens();
        $this->_initNingObjects();
    }

    private function _initAuthTokens() {
        $this->consumerToken = new OAuthConsumer($this->consumerKey, $this->consumerSecret);
        $this->signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
        $this->login($this->email, $this->password);
    }

    private function _initNingObjects() {
        $this->activityItem = new NingActivityItem();
        $this->blogPost = new NingBlogPost();
        $this->broadcastMessage = new NingBroadcastMessage();
        $this->comment = new NingComment();
        $this->network = new NingNetwork();
        $this->photo = new NingPhoto();
        $this->user = new NingUser();
    }

    public function login($email, $password) {
        $credentials = base64_encode($email . ':' . $password);
        $headers = array(
            'Authorization: Basic ' . $credentials
        );
        $result = $this->post('Token', NULL, $headers, TRUE);
        $this->oauthToken = $result['entry']['oauthToken'];
        $this->oauthTokenSecret = $result['entry']['oauthTokenSecret'];
        $this->requestToken = new OAuthConsumer($this->oauthToken, $this->oauthTokenSecret);
    }

    /**
     * Create a Ning API request URL
     */
    public function buildUrl($path, $secure=FALSE) {
        $protocol = $secure ? self::SECURE_PROTOCOL : self::INSECURE_PROTOCOL;
        $base = $protocol . self::BASE_URL;
        $parts = array($base, $this->subdomain, self::API_VERSION, $path);
        $url = join('/', $parts);
        return $url;
    }

    /**
     * Call the Ning API
     */
    public function call($path, $method='GET', $body=NULL, $headers=NULL, $secure=FALSE) {

        $url = $this->buildUrl($path, $secure);
        $headers = $headers ? $headers : array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if ($body && array_key_exists('file', $body)) {
            $isMultipart = TRUE;
            // Don't include the body params for multipart requests
            $oauth_req = OAuthRequest::from_consumer_and_token($this->consumerToken,
                            $this->requestToken, $method, $url);
        } else {
            $isMultipart = FALSE;
            $oauth_req = OAuthRequest::from_consumer_and_token($this->consumerToken,
                            $this->requestToken, $method, $url, $body);
        }

        $oauth_req->sign_request($this->signatureMethod, $this->consumerToken,
                $this->requestToken);

        if ($method === 'POST' || $method === 'PUT') {

            if ($isMultipart) {
                // Send as multipart/form-data
                $headers[] = $oauth_req->to_header();
                curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            } else {
                // Send as application/x-www-form-urlencoded
                curl_setopt($ch, CURLOPT_POSTFIELDS,
                        $oauth_req->to_postdata());
            }

            curl_setopt($ch, CURLOPT_URL,
                    $oauth_req->get_normalized_http_url());

            if ($method === 'PUT') {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            }
        } else if ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_URL, $oauth_req->to_url());
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        } else {
            curl_setopt($ch, CURLOPT_URL, $oauth_req->to_url());
        }


        if (count($headers) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        $json = null;
        try {
            $json = curl_exec($ch);
        } catch (Exception $e) {
            echo 'Found exception trying to curl: ' . $e->getMessage();
            #throw new NingException($e->getMessage());
        }


        if (curl_errno($ch)) {
            throw new NingException('cURL error: ' . curl_error($ch));
        }

        curl_close($ch);

        $result = json_decode($json, TRUE);

        if (empty($result)) {
            throw new NingException('Empty result');
        }

        if (!$result['success']) {
            throw NingException::generate($result);
        }

        return $result;
    }

    public function post($path, $body=NULL, $headers=NULL, $secure=false) {
        return $this->call($path, 'POST', $body, $headers, $secure);
    }

    public function put($path, $body=NULL, $headers=NULL, $secure=false) {
        return $this->call($path, 'PUT', $body, $headers, $secure);
    }

    public function delete($path, $body=NULL, $headers=NULL, $secure=false) {
        return $this->call($path, 'DELETE', $body, $headers, $secure);
    }

    public function get($path, $body=NULL, $headers=NULL, $secure=false) {
        return $this->call($path, 'GET', $body, $headers, $secure);
    }

}
