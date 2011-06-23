<?php

require_once('NingTestHelper.php');

/**
 * @group Network
 */
class NingNetworkTest extends PHPUnit_Framework_TestCase {

    public function testFetch() {
        $result = NingApi::instance()->network->fetch();
        $this->assertTrue($result['success']);
    }

    public function testFetchAlphabetical() {
        $result = NingApi::instance()->network->fetchAlphabetical();
        $this->assertTrue($result['success']);
    }

    public function testGet_old() {
        $fields = "name";
        $path = sprintf("Network/alpha?fields=%s", $fields);

        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

    public function testAlpha_old() {
        $count = 3;
        $fields = "name";
        $path = sprintf("Network/alpha?count=%s&fields=%s", $count,
                        $fields);

        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

}

?>
