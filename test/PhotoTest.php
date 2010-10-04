<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class PhotoTest extends PHPUnit_Framework_TestCase {

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
    
    private function create() {
        $parts = array(
            "title" => "Photo Title",
            "description" => "Photo Description",
            "file" => "@" . __DIR__ . "/files/sample.jpg"
        );

        $result = $this->ningApi->post("Photo", $parts);
        $this->assertTrue($result['success']);
        
        return $result;
    }

    public function testRecent() {
        $count = 3;
        $fields = "title,author.url";
        $path = sprintf("Photo/recent?count=%s&fields=%s", $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testCreate() {
        $result = $this->create();
    }

    public function testUpdate() {
        $result = $result = $this->create();

        $parts = array(
            "title" => "Updated Photo Title",
            "description" => "Updated Photo Description",
            "id" => $result['id']
        );

        $result = $this->ningApi->put("Photo", $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $result = $result = $this->create();

        $path = sprintf("Photo?id=%s", $result['id']);

        $result = $this->ningApi->delete($path);
        $this->assertTrue($result['success']);
    }

    public function testCount() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));
        
        $path = sprintf("Photo/count?createdAfter=%s", $date);
        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
