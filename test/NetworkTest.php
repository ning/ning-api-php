<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class NetworkTest extends PHPUnit_Framework_TestCase {

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
    
    public function testGet() {
        $fields = "name";
        $path = sprintf("Network/alpha?fields=%s", $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testAlpha() {
        $count = 3;
        $fields = "name";
        $path = sprintf("Network/alpha?count=%s&fields=%s", $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
