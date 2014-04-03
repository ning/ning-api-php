<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class SitePageTest extends PHPUnit_Framework_TestCase {

    protected $ningApi;

    protected function setUp() {
        $subdomain = TestConfig::SUBDOMAIN;

        $consumer_key = TestConfig::CONSUMER_KEY;
        $consumer_secret = TestConfig::CONSUMER_SECRET;

        $ningApi = new NingApi($subdomain, $consumer_key, $consumer_secret);

        $this->ningApi = $ningApi;

        $this->ningApi->login(TestConfig::EMAIL, TestConfig::PASSWORD);
    }

    private function create($customPath) {
        $parts = array(
            'targetType' => 'customPage',
            'customPath' => $customPath,
            'viewerType' => 'member',
            'contributorType' => 'admin'
        );

        $result = $this->ningApi->post('SitePage', $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    public function testCreate() {
        $result = $this->create('sitePageTestCreate');
        $this->assertTrue($result['success']);
    }

    public function testGet() {
        $result = $this->create('sitePageTestGet');
        $fields = 'targetType,published';
        $path = sprintf('SitePage/?id=%s&fields=%s', $result['id'], $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

}