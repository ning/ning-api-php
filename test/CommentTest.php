<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class CommentTest extends PHPUnit_Framework_TestCase {

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
        $attachedTo = $this->getEntryId();
        $parts = array(
            'description' => 'Comment Description',
            'attachedTo' => $attachedTo
        );

        $result = $this->ningApi->post('Comment', $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    private function getEntryId() {
        $entryId = null;
        $count = 1;
        $fields = 'id';
        $entryContentType = 'BlogPost';
        $path = sprintf('Bundle/recent?count=%s&fields=%s&entryContentType=%s', $count,
            $fields, $entryContentType);

        $result = $this->ningApi->get($path);
        if (isset($result['entry'][0]['id'])) {
            $bundleId = $result['entry'][0]['id'];
            $path = sprintf('BlogPost/recent?bundleId=%s&count=%s&fields=%s', $bundleId,
                $count, $fields);
            $entries = $this->ningApi->get($path);
            if (isset($entries['entry'][0]['id'])) {
                $entryId = $entries['entry'][0]['id'];
            }
        }

        return $entryId;
    }

    public function testGet() {
        $result = $this->create();
        $fields = 'author,description';
        $path = sprintf('Comment/?id=%s&fields=%s', $result['id'], $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testRecent() {
        $count = 3;
        $fields = 'title,author.url';
        $attachedTo = $this->getEntryId();
        $path = sprintf('Comment/recent?count=%s&fields=%s&attachedTo=%s', $count,
            $fields, $attachedTo);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testFeatured() {
        $count = 3;
        $fields = 'title, author.url';
        $attachedTo = $this->getEntryId();
        $path = sprintf('Comment/featured?count=%s&fields=%s&attachedTo=%s', $count,
            $fields, $attachedTo);

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
            'description' => 'Updated Comment Description',
            'id' => $result['id']
        );

        $result = $this->ningApi->put('Comment', $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $result = $this->create();

        $path = sprintf('Comment?id=%s', $result['id']);

        $result = $this->ningApi->delete($path);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
