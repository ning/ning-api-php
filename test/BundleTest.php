<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class BundleTest extends PHPUnit_Framework_TestCase {

    protected $ningApi;

    protected function setUp() {
        $subdomain = TestConfig::SUBDOMAIN;

        $consumer_key = TestConfig::CONSUMER_KEY;
        $consumer_secret = TestConfig::CONSUMER_SECRET;

        $ningApi = new NingApi($subdomain, $consumer_key, $consumer_secret);

        $this->ningApi = $ningApi;

        $this->ningApi->login(TestConfig::EMAIL, TestConfig::PASSWORD);
    }

    private function getBundleId() {
        $bundleId = 'none';
        $count = 1;
        $fields = 'id';
        $entryContentType = 'BlogPost';
        $path = sprintf('Bundle/recent?count=%s&fields=%s&entryContentType=%s', $count,
            $fields, $entryContentType);

        $result = $this->ningApi->get($path);
        if (isset($result['entry'][0]['id'])) {
            $bundleId = $result['entry'][0]['id'];
        }

        return $bundleId;
    }

    public function testGet() {
        $bundleId = $this->getBundleId();
        $fields = 'createdDate,author,entryContentType,categoryNames';
        $path = sprintf('Bundle/?id=%s&fields=%s', $bundleId, $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testRecent() {
        $count = 12;
        $fields = 'id,entryContentType,categoryNames';
        $path = sprintf('Bundle/recent?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);

        return $result['entry'][0]['id'];
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}