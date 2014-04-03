<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class MemberCategoryTest extends PHPUnit_Framework_TestCase {

    protected $ningApi;

    protected function setUp() {
        $subdomain = TestConfig::SUBDOMAIN;

        $consumer_key = TestConfig::CONSUMER_KEY;
        $consumer_secret = TestConfig::CONSUMER_SECRET;

        $ningApi = new NingApi($subdomain, $consumer_key, $consumer_secret);

        $this->ningApi = $ningApi;

        $this->ningApi->login(TestConfig::EMAIL, TestConfig::PASSWORD);
    }

    public function testRecent() {
        $count = 9;
        $fields = 'title,imageUrl';
        $path = sprintf('MemberCategory/recent?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}