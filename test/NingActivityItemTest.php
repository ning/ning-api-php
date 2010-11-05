<?php

require_once('NingNetworkTest.php');

class NingActivityItemTest extends PHPUnit_Framework_TestCase {

    public function testFetchRecent() {
        $result = NingApi::instance()->activityItem->fetchRecent();
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $item = NingApi::instance()->activityItem->fetchRecent();
        $result = NingApi::instance()->activityItem->delete($item['entry'][0]);
        $this->assertTrue($result['success']);
    }

    public function testRecent_old() {
        $count = 3;
        $fields = "title";
        $path = sprintf("Activity/recent?count=%s&fields=%s", $count,
                        $fields);

        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

    public function testCount_old() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));

        $path = sprintf("Activity/count?createdAfter=%s", $date);
        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

}

?>
