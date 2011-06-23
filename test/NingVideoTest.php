<?php

require_once('NingTestHelper.php');

/**
 * @group Video
 */
class NingVideoTest extends PHPUnit_Framework_TestCase {

    public function testFetchRecent() {
        $result = NingApi::instance()->video->fetchRecent();
        $this->assertTrue($result['success']);
    }

    public function testRecent_old() {
        $count = 3;
        $fields = "title,author.url";
        $path = sprintf("Video/recent?count=%s&fields=%s", $count,
                        $fields);

        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

    public function testCount_old() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));

        $path = sprintf("Video/count?createdAfter=%s", $date);
        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

}

?>
