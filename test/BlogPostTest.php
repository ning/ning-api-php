<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class BlogTest extends PHPUnit_Framework_TestCase {

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
            "title" => "BlogPost Title",
            "description" => "BlogPost Description"
        );

        $result = $this->ningApi->post("BlogPost", $parts);
        $this->assertTrue($result['success']);
        
        return $result;
    }

    public function testRecent() {
        $count = 3;
        $fields = "title,author.url";
        $path = sprintf("BlogPost/recent?count=%s&fields=%s", $count,
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
            "title" => "Updated BlogPost Title",
            "description" => "Updated BlogPost Description",
            "id" => $result['id']
        );

        $result = $this->ningApi->put("BlogPost", $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $result = $result = $this->create();

        $path = sprintf("BlogPost?id=%s", $result['id']);

        $result = $this->ningApi->delete($path);
        $this->assertTrue($result['success']);
    }
    
    public function testCount() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));
        
        $path = sprintf("BlogPost/count?createdAfter=%s", $date);
        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
