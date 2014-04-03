<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class PhotoTest extends PHPUnit_Framework_TestCase {

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
            'title' => 'Photo Title',
            'description' => 'Photo Description',
            'photo' => '@' . __DIR__ . '/files/sample.jpg'
        );

        $result = $this->ningApi->post('Photo', $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    private function getBundleId() {
        $bundleId = 'none';
        $count = 1;
        $fields = 'id';
        $entryContentType = 'Photo';
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
        $fields = 'author,title';
        $path = sprintf('Photo/?id=%s&fields=%s', $result['id'], $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testRecent() {
        $count = 3;
        $fields = 'title,author.url';
        $bundleId = $this->getBundleId();
        $path = sprintf('Photo/recent?count=%s&fields=%s&bundleId=%s', $count,
            $fields, $bundleId);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testFeatured() {
        $count = 3;
        $fields = 'title, author.url';
        $bundleId = $this->getBundleId();
        $path = sprintf('Photo/featured?count=%s&fields=%s&bundleId=%s', $count,
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
            'title' => 'Updated Photo Title',
            'description' => 'Updated Photo Description',
            'id' => $result['id']
        );

        $result = $this->ningApi->put('Photo', $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $result = $this->create();

        $path = sprintf('Photo?id=%s', $result['id']);

        $result = $this->ningApi->delete($path);
        $this->assertTrue($result['success']);
    }

    public function testCount() {
        date_default_timezone_set('UTC');
        $date = date('Y-m-d\TH:i:s\Z', strtotime('-2 days'));
        $bundleId = $this->getBundleId();

        $path = sprintf('Photo/count?createdAfter=%s&bundleId=%s', $date, $bundleId);
        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
