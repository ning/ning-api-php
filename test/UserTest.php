<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class UserTest extends PHPUnit_Framework_TestCase {

    protected $ningApi;

    protected function setUp() {
        $subdomain = TestConfig::SUBDOMAIN;

        $consumer_key = TestConfig::CONSUMER_KEY;
        $consumer_secret = TestConfig::CONSUMER_SECRET;

        $access_token = TestConfig::ACCESS_KEY;
        $access_token_secret = TestConfig::ACCESS_SECRET;

        $ningApi = new NingApi($subdomain, $consumer_key, $consumer_secret,
            $access_token, $access_token_secret);

        $this->ningApi = $ningApi;
    }
    
    public function testRecent() {
        $count = 3;
        $fields = "fullName";
        $path = sprintf("User/recent?count=%s&fields=%s", $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testAlpha() {
        $count = 3;
        $fields = "fullName";
        $path = sprintf("User/alpha?count=%s&fields=%s", $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testUpdateStatus() {

        $parts = array(
            "id" => "3011345:User:343",
            "statusMessage" => "Hello, World!"
        );

        $result = $this->ningApi->put("User", $parts);
        $this->assertTrue($result['success']);
    }

    public function testCount() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));
        
        $path = sprintf("User/count?createdAfter=%s", $date);
        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
