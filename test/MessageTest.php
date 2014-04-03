<?php

require_once('NingApi.php');
require_once('TestConfig.php');

class MessageTest extends PHPUnit_Framework_TestCase {

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
        $recipients = $this->getRecipients();
        $parts = array(
            'recipients' => $recipients,
            'subject' => 'Message Subject',
            'body' => 'Message body: the content of the message.',
            'email' => 'true'
        );

        $result = $this->ningApi->post('Message', $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    private function getRecipients() {
        $count = 3;
        $fields = 'title';
        $path = sprintf('User/recent?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $recipients = array();
        foreach ($result['entry'] as $user) {
            $recipients[] = $user['title'];
        }
        return json_encode($recipients);
    }

    public function testInbox() {
        $fields = 'id,createdDate,sender,subject';
        $path = sprintf('Message/inbox?fields=%s', $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);

        return $result;
    }

    public function testSent() {
        $count = 3;
        $fields = 'id,createdDate,subject,recipients';
        $path = sprintf('Message/sent?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);

        return $result;
    }

    public function testArchive() {
        $count = 3;
        $fields = 'createdDate,sender,subject';
        $path = sprintf('Message/archive?count=%s&fields=%s', $count,
            $fields);

        $result = $this->ningApi->get($path);
        $this->assertTrue($result['success']);
    }

    public function testCreate() {
        $result = $this->create();
        $this->assertTrue($result['success']);
    }

    public function testUpdate() {
        $this->create();
        $result = $this->testSent();
        $id = $result['entry'][0]['id'];

        $parts = array(
            'read' => 'true',
            'id' => $id
        );

        $result = $this->ningApi->put('Message', $parts);
        $this->assertTrue($result['success']);
    }

    protected function tearDown() {
        $this->ningApi = NULL;
    }
}
