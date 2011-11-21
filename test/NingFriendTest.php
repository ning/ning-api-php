<?php

require_once('NingTestHelper.php');

/**
 * @group Friend
 */
class NingFriendTest extends PHPUnit_Framework_TestCase {

	public function testFetchRecent() {
		$result = NingApi::instance()->friend->fetchRecent();
		$this->assertTrue($result['success']);
	}

	public function testFetchNRecent() {
		$result = NingApi::instance()->friend->fetchNRecent(3);
		$this->assertTrue($result['success']);
	}

	public function testFetchRecentNextPage() {
		$result = NingApi::instance()->friend->fetchRecentNextPage();
		$this->assertTrue($result['success']);
	}

}
