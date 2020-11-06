<?php

define('SRC_PATH', __DIR__);
define('OBJECTS_PATH', SRC_PATH . '/objects');
set_include_path(get_include_path() . PATH_SEPARATOR . SRC_PATH . PATH_SEPARATOR . OBJECTS_PATH);
require_once('OAuth.php');
require_once('NingException.php');
require_once('NingObject.php');
require_once('NingUpload.php');
require_once('NingUploadFile.php');
require_once('NingUploadBinary.php');


class NingApi {
    const SECURE_PROTOCOL = 'https://';
    const INSECURE_PROTOCOL = 'http://';
    protected static $BASE_URL = 'external.ningapis.com/xn/rest';
    protected static $API_VERSION = '2.0';

    // The maximum number of seconds to allow cURL to execute
    protected static $CURL_TIMEOUT = 100;

    public $subdomain = '';
    // Ning user email address
    protected $email = '';
    // Ning user password
    protected $password = '';
    // Consumer key found at [subdomain].ning.com/main/extend/keys
    protected $consumerKey = '';
    // Consumer secret found at [subdomain].ning.com/main/extend/keys
    protected $consumerSecret = '';

    protected $requestToken = null;
    private static $_instance = null;

    protected $verifySslCertificates = true;

    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new NingApi();
        }
        return self::$_instance;
    }

    public function __construct($subdomain=null, $consumerKey=null, $consumerSecret=null, $emailOrRequestKey=null, $passwordOrRequestSecret=null) {
        if ($subdomain) {
            $this->subdomain = $subdomain;
        }
        if ($consumerKey) {
            $this->consumerKey = $consumerKey;
        }
        if ($consumerSecret) {
            $this->consumerSecret = $consumerSecret;
        }

        if ($this->consumerKey && $this->consumerSecret) {
            $this->_initAuthTokens($this->consumerKey, $this->consumerSecret);
        }

        if (preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $emailOrRequestKey) && preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/', $passwordOrRequestSecret)) {
            $this->setRequestTokens($emailOrRequestKey,
                                    $passwordOrRequestSecret);
        }
        else {
            if ($emailOrRequestKey) {
                $this->email = $emailOrRequestKey;
            }
            if ($passwordOrRequestSecret) {
                $this->password = $passwordOrRequestSecret;
            }

            if ($this->email && $this->password) {
                $this->login($this->email, $this->password);
            }
        }
        $this->_initNingObjects();

        //set the static $_instance variable for singleton access
        self::$_instance = $this;
    }

    public function setVersion($version) {
        self::$API_VERSION = $version;
    }

    public function setBaseUrl($baseUrl) {
        self::$BASE_URL = $baseUrl;
        echo "set base url successfully";
    }

    /**
     *
     * Set maximum number of seconds to allow cURL to execute
     * @param int $time
     */
    public function setCurlTimeOut($time) {
        self::$CURL_TIMEOUT = $time;
    }

    public function setSslCertificateVerification($verify, $check = null) {
        if ((! $verify) && ($check !== "I am only turning off SSL Certificate Verification because I am testing something and I promise I know what I am doing.")) {
            NingException::generate(array('reason' => "Don't turn off SSL Certificate Verification unless you know what you are doing.",
                                          'status' => 401,
                                          'code' => 1,
                                          'subcode' => 4));
        }
        $this->verifySslCertificates = (bool) $verify;
        echo "set ssl verification to $verify";
    }

    private function _requireUserSpecificData($required = array('subdomain', 'consumerKey', 'consumerSecret', 'requestToken')) {
        foreach ($required as $val) {
            if (!$this->{$val}) {
                throw new NingException("Failed to find the value for '$val'");
            }
        }
    }

    private function _initAuthTokens($consumerKey, $consumerSecret) {
        $this->consumerToken = new OAuthConsumer($consumerKey, $consumerSecret);
        $this->signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
        self::$_instance = $this;
    }

    private function _initNingObjects() {
        $this->blogPost = new NingBlogPost();
        $this->comment = new NingComment();
        $this->network = new NingNetwork();
        $this->photo = new NingPhoto();
        $this->user = new NingUser();
    }

    public function setSubdomain($subdomain) {
        $this->subdomain = $subdomain;
        self::$_instance = $this;
    }

    public function setConsumerTokens($consumerKey, $consumerSecret) {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
        $this->_initAuthTokens($this->consumerKey, $this->consumerSecret);
        self::$_instance = $this;
    }

    public function setRequestTokens($key, $secret) {
        $this->oauthToken = $key;
        $this->oauthTokenSecret = $secret;
        $this->requestToken = new OAuthConsumer($this->oauthToken, $this->oauthTokenSecret);
    }

    public function login($email, $password) {
        try{
            $this->_requireUserSpecificData(array('subdomain','consumerKey','consumerSecret'));
        }catch(Exception $e){
            $message = $e->getMessage();
            throw new NingException("You must specify the subdomain, consumerKey, and consumerSecret before calling login(). ".$message);
        }
        $this->email = $email;
        $this->password = $password;
        $credentials = base64_encode($email . ':' . $password);
        $headers = array(
            'Authorization: Basic ' . $credentials
        );
        $result = $this->post('Token', NULL, $headers, true);
        $this->setRequestTokens($result['entry']['oauthToken'],
                                $result['entry']['oauthTokenSecret']);
        self::$_instance = $this;
        return $result;
    }

    /**
     * Create a Ning API request URL
     */
    public function buildUrl($path, $secure=TRUE, $version='2.0') {
        $protocol = $secure ? self::SECURE_PROTOCOL : self::INSECURE_PROTOCOL;
        $base = $protocol . self::$BASE_URL;
        $parts = array($base, $this->subdomain, $version, $path);
        $url = join('/', $parts);
        return $url;
    }

    /**
     * Call the Ning API
     */
    public function call($path, $method='GET', $body=NULL, $headers=NULL, $secure=TRUE) {
        if($path == 'Token'){
            $version = '1.0';
        } else {
            //unless we're authenticating, we need all user data
            $this->_requireUserSpecificData();
            $version = self::$API_VERSION;
        }
        $url = $this->buildUrl($path, $secure, $version);
        $headers = $headers ? $headers : array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, self::$CURL_TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        $isMultipart = $this->isMultipart($body);

        if ($isMultipart) {
            // Don't include the body params for multipart requests
            $oauth_req = OAuthRequest::from_consumer_and_token($this->consumerToken,
                            $this->requestToken, $method, $url);
        } else {
            $oauth_req = OAuthRequest::from_consumer_and_token($this->consumerToken,
                            $this->requestToken, $method, $url, $body);
        }

        $oauth_req->sign_request($this->signatureMethod, $this->consumerToken,
                $this->requestToken);

        if ($method === 'POST' || $method === 'PUT') {

            if ($isMultipart) {
                // Send as multipart/form-data
                $headers[] = $oauth_req->to_header();
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->transformMultipartBody($body));
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

        if (false === $this->verifySslCertificates) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        }

        $json = null;
        try {
            $json = curl_exec($ch);
        } catch (Exception $e) {
            throw new NingException($e->getMessage());
        }


        if (curl_errno($ch)) {
            throw new NingException('cURL error: ' . curl_error($ch) . " accessing $method $path");
        }

        curl_close($ch);

        $result = json_decode($json, TRUE);

        if (!$result['success']) {
            throw NingException::generate($result);
        }

        return $result;
    }

    public function post($path, $body=NULL, $headers=NULL, $secure=true) {
        return $this->call($path, 'POST', $body, $headers, $secure);
    }

    public function put($path, $body=NULL, $headers=NULL, $secure=true) {
        return $this->call($path, 'PUT', $body, $headers, $secure);
    }

    public function delete($path, $body=NULL, $headers=NULL, $secure=true) {
        return $this->call($path, 'DELETE', $body, $headers, $secure);
    }

    public function get($path, $body=NULL, $headers=NULL, $secure=true) {
        return $this->call($path, 'GET', $body, $headers, $secure);
    }

    /**
     * Does the provided array of request body elements contain any
     * upload items so that the request should be treated as a multipart
     * request?
     *
     * @param $body array key=>value pairs of request body items
     * @return boolean
     */
    protected function isMultipart($body){
        if (is_array($body)) {
            foreach ($body as $name => $value){
                if ($value instanceof NingUpload) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Transform the provided request body elements into the proper
     * key=>value pairs that curl expects
     */
    protected function transformMultipartBody($body) {
        foreach ($body as $name => $value) {
            if ($value instanceof NingUpload) {
                $body[$name] = (string) $value;
            }
        }
        return $body;
    }
}
