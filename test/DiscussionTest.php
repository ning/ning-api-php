<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class DiscussionTest extends PHPUnit_Framework_TestCase {

    protected $ningApi;

    protected function setUp() {
        $subdomain = TestConfig::SUBDOMAIN;

        $consumer_key = TestConfig::CONSUMER_KEY;
        $consumer_secret = TestConfig::CONSUMER_SECRET;

        $ningApi = new NingApi($subdomain, $consumer_key, $consumer_secret);

        $this->ningApi = $ningApi;

        $this->ningApi->login(TestConfig::EMAIL, TestConfig::PASSWORD);
    }

    private function create() {
        $bundleId = $this->getBundleId();
        $parts = array(
            'bundleId' => $bundleId,
            'title' => 'Discussion Title',
            'description' => 'Discussion Description',
            'tagNames' => 'test, api'
        );

        $result = $this->ningApi->post('Discussion', $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    private function getBundleId() {
        $bundleId = 'none';
        $count = 1;
        $fields = 'id';
        $entryContentType = 'Discussion';
        $path = sprintf('Bundle/recent?count=%s&fields=%s&entryContentType=%s', $count,
            $fields, $entryContentType);

        $result = $this->ningApi->get($path);
        if (isset($result['entry'][0]['id'])) {
            $bundleId = $result['entry'][0]['id'];
        }

        return $bundleId;
    }

    public function testGet() {
        $result = $this->create();
        $fields = 'author,title,description';
        $path = sprintf('Discussion/?id=%s&fields=%s', $result['id'], $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testRecent() {
        $count = 3;
        $fields = 'title, author.url';
        $bundleId = $this->getBundleId();
        $path = sprintf('Discussion/recent?count=%s&fields=%s&bundleId=%s', $count,
            $fields, $bundleId);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testFeatured() {
        $count = 3;
        $fields = 'title, author.url';
        $bundleId = $this->getBundleId();
        $path = sprintf('Discussion/featured?count=%s&fields=%s&bundleId=%s', $count,
            $fields, $bundleId);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testCreate() {
        $result = $this->create();
        $this->assertTrue($result['success']);
    }

    public function testUpdate() {
        $result = $this->create();

        $parts = array(
            'title' => 'Updated Discussion Title',
            'description' => 'Updated Discussion Description',
            'id' => $result['id']
        );

        $result = $this->ningApi->put('Discussion', $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $result = $this->create();

        $path = sprintf('Discussion?id=%s', $result['id']);

        $result = $this->ningApi->delete($path);
        $this->assertTrue($result['success']);
    }

    public function testCount() {
        date_default_timezone_set('UTC');
        $date = date('Y-m-d\TH:i:s\Z', strtotime('-2 days'));
        $bundleId = $this->getBundleId();

        $path = sprintf('Discussion/count?createdAfter=%s&bundleId=%s', $date, $bundleId);
        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
