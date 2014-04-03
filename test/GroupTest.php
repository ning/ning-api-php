<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class GroupTest extends PHPUnit_Framework_TestCase {

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
        $parts = array(
            'title' => 'Group Title',
            'description' => 'Group Description',
            'joinSetting' => 'invitation'
        );

        $result = $this->ningApi->post('Group', $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    public function testGet() {
        $result = $this->create();
        $fields = 'title,description,joinSetting';
        $path = sprintf('Group/?id=%s&fields=%s', $result['id'], $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testRecent() {
        $count = 3;
        $fields = 'title,description,joinSetting';
        $path = sprintf('Group/recent?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testFeatured() {
        $count = 3;
        $fields = 'title,description,joinSetting';
        $path = sprintf('Group/featured?count=%s&fields=%s', $count,
            $fields);

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
            'title' => 'Updated Group Title',
            'description' => 'Updated Group Description',
            'joinSetting' => 'anyone',
            'id' => $result['id']
        );

        $result = $this->ningApi->put('Group', $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $result = $this->create();

        $path = sprintf('Group?id=%s', $result['id']);

        $result = $this->ningApi->delete($path);
        $this->assertTrue($result['success']);
    }

    public function testCount() {
        date_default_timezone_set('UTC');
        $date = date('Y-m-d\TH:i:s\Z', strtotime('-2 days'));

        $path = sprintf('Group/count?createdAfter=%s&', $date);
        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
