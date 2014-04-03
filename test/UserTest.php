<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class UserTest extends PHPUnit_Framework_TestCase {

    protected $ningApi;

    protected function setUp() {
        $subdomain = TestConfig::SUBDOMAIN;

        $consumer_key = TestConfig::CONSUMER_KEY;
        $consumer_secret = TestConfig::CONSUMER_SECRET;

        $ningApi = new NingApi($subdomain, $consumer_key, $consumer_secret);

        $this->ningApi = $ningApi;

        $this->ningApi->login(TestConfig::EMAIL, TestConfig::PASSWORD);
    }

    public function create($n) {
        $parts = array(
            'email' => 'testUser'.$n.'@ning.com',
            'password' => 'Test1234',
            'fullName' => 'User Create Test'
        );

        $result = $this->ningApi->post('User', $parts);
        $this->assertTrue($result['success']);
        $this->userId = $result['id'];
        return $result;
    }

    public function testCreate() {
        $result = $this->create(1);
        $this->assertTrue($result['success']);
    }

    public function testUpdate() {
        $result = $this->create(2);
        $parts = array(
            'id' => $result['id'],
            'fullName' => 'User Update Test'
        );

        $result = $this->ningApi->put('User', $parts);
        $this->assertTrue($result['success']);
    }

    public function testGet() {
        $result = $this->create(3);
        $fields = 'email,fullName';
        $path = sprintf('User/?id=%s&fields=%s', $result['id'], $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testRecent() {
        $count = 3;
        $fields = 'fullName,createdDate';
        $path = sprintf('User/recent?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testFeatured() {
        $count = 3;
        $fields = 'fullName,featuredDate';
        $path = sprintf('User/featured?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testPending() {
        $count = 3;
        $fields = 'fullName,createdDate';
        $path = sprintf('User/pending?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
