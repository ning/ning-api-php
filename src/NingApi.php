<?php

require_once("OAuth.php");

class NingApi {
    const BASE_URL = "https://external.ningapis.com/xn/rest";
    const API_VERSION = "1.0";

    // The maximum number of seconds to allow cURL to execute
    const CURL_TIMEOUT = 10;

    // Ning Network subdomain (ie. 'apiexample' in apiexample.ning.com)
    private $subdomain;

    // OAuth signature method
    private $signatureMethod;

    // OAuth consumer token
    private $consumer;

    // OAuth access token
    private $token;

    public function __construct($subdomain, $consumerKey, $consumerSecret,
        $accessToken = NULL, $accessTokenSecret = NULL) {

        $this->subdomain = $subdomain;
        $this->signatureMethod = new OAuthSignatureMethod_HMAC_SHA1();
        $this->consumer = new OAuthConsumer($consumerKey, $consumerSecret);
        $this->token = new OAuthConsumer($accessToken, $accessTokenSecret);
    }

    /**
     * Create a Ning API request URL
     */
    public function buildUrl($path) {
        $parts = array(self::BASE_URL, $this->subdomain, self::API_VERSION,
            $path);
        return join("/", $parts);
    }

    /**
     * Call the Ning API
     */
    public function call($path, $method="GET", $body=NULL, $headers=NULL) {
        $url = $this->buildUrl($path);

        $headers = $headers ? $headers : array();

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, self::CURL_TIMEOUT);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

        if ($body && array_key_exists("file", $body)) {
            $isMultipart = TRUE;
            // Don't include the body params for multipart requests
            $oauth_req = OAuthRequest::from_consumer_and_token($this->consumer,
                $this->token, $method, $url);
        } else {
            $isMultipart = FALSE;
            $oauth_req = OAuthRequest::from_consumer_and_token($this->consumer,
                $this->token, $method, $url, $body);
        }

        $oauth_req->sign_request($this->signatureMethod, $this->consumer,
            $this->token);

        if ($method === "POST" || $method === "PUT") {

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

            if ($method === "PUT") {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
            }

        } else if ($method === "DELETE") {
            curl_setopt($ch, CURLOPT_URL, $oauth_req->to_url());
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");

        } else {
            curl_setopt($ch, CURLOPT_URL, $oauth_req->to_url());
        }


        if (count($headers) > 0) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }


        $json = curl_exec($ch);

        if (curl_errno($ch)) {
            throw new NingException("cURL error: " . curl_error($ch));
        }

        curl_close($ch);

        $result =  json_decode($json, TRUE);

        if (empty($result)) {
            throw new NingException("Empty result");
        }

        if (!$result["success"]) {
            throw NingException::generate($result);
        }

        return $result;
    }

    public function post($path, $body=NULL, $headers=NULL) {
        return $this->call($path, "POST", $body, $headers);
    }

    public function put($path, $body=NULL, $headers=NULL) {
        return $this->call($path, "PUT", $body, $headers);
    }

    public function delete($path, $body=NULL, $headers=NULL) {
        return $this->call($path, "DELETE", $body, $headers);
    }

    public function get($path, $body=NULL, $headers=NULL) {
        return $this->call($path, "GET", $body, $headers);
    }

    public function login($email, $password) {
        $credentials = base64_encode($email . ":" . $password);
        $headers = array(
            "Authorization: Basic ". $credentials
        );

        $result = $this->call("Token", "POST", NULL, $headers);

        return $result;
    }
}

class NingException extends Exception {

    // HTTP status code
    private $httpStatus;

    // Ning API error code
    private $ningCode;

    // Ning API error subcode
    private $ningSubcode;

    // Ning API trace number
    private $ningTrace;

    public function __construct($message, $status=NULL, $code=NULL,
        $subcode=NULL, $trace=NULL) {

            $this->httpStatus = $status;
            $this->ningCode = $code;
            $this->ningSubcode = $subcode;
            $this->ningTrace = $trace;

            parent::__construct($message);
    }

    public function getHttpStatus() {
        return $this->httpStatus;
    }

    public function getNingCode() {
        return $this->ningCode;
    }

    public function getNingSubcode() {
        return $this->ningSubcode;
    }

    public function getNingTrace() {
        return $this->ningTrace;
    }

    public function __toString() {
        $errorMessage = $this->getMessage();
        if ($this->getHttpStatus()) {
            $errorMessage = sprintf("%s (%s)", $errorMessage,
                $this->getHttpStatus());
        }

        if ($this->getNingCode() && $this->getNingSubcode()) {
            $errorMessage = sprintf("%s %s-%s", $errorMessage,
                $this->getNingCode(), $this->getNingSubcode());
        }

        return $errorMessage;
    }

    public static function generate($response) {
        return new NingException($response['reason'],
            $response['status'], $response['code'], $response['subcode']);
    }
}
