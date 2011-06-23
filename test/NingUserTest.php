<?php

require_once('NingTestHelper.php');

/**
 * @group User
 */
class NingUserTest extends PHPUnit_Framework_TestCase {

    public function testFetch() {
        $recent = NingApi::instance()->user->fetchRecent();
        $args = array('id' => $recent['entry'][0]['id']);
        $result = NingApi::instance()->user->fetch($args);
        $this->assertTrue($result['success']);
    }

    public function testFetchRecent() {
        $result = NingApi::instance()->user->fetchRecent();
        $this->assertTrue($result['success']);
    }

    public function testFetchNRecent() {
        $result = NingApi::instance()->user->fetchNRecent(5);
        $this->assertTrue($result['success']);
    }

    public function testAddStatusMessage() {
        $recent = NingApi::instance()->user->fetch(array());
        $userId = $recent['entry']['id'];
        $message = "Hey guys just statusing up some messages.";
        $result = NingApi::instance()->user->addStatusMessage($userId, $message);
        $this->assertTrue($result['success']);
    }

    public function testRecent_old() {
        $count = 3;
        $fields = "fullName";
        $path = sprintf("User/recent?count=%s&fields=%s", $count,
                        $fields);

        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

    public function testAlpha_old() {
        $count = 3;
        $fields = "fullName";
        $path = sprintf("User/alpha?count=%s&fields=%s", $count,
                        $fields);

        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

    public function testCount_old() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));

        $path = sprintf("User/count?createdAfter=%s", $date);
        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

}

?>