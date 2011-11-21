<?php

require_once('NingTestHelper.php');

/**
 * @group Tags
 */
class NingTagTest extends PHPUnit_Framework_TestCase {

	const FETCH_COUNT = 3;

	public function testFetchUnordered() {
		$contentId = self::getContentId();
		$result = NingApi::instance()->tag->fetchUnordered($contentId);
		$this->assertTrue($result['success']);
	}

	public function testFetchNUnordered() {
		$contentId = self::getContentId();
		$result = NingApi::instance()->tag->fetchNUnordered($contentId, self::FETCH_COUNT);
		$this->assertTrue($result['success']);
	}

	public function testFetchUnorderedNextPage() {
		$contentId = self::getContentId();
		$result = NingApi::instance()->tag->fetchUnorderedNextPage($contentId);
		$this->assertTrue($result['success']);
	}

	private function getContentId() {
		$client = NingApi::instance();
		$result = $client->blogPost->fetchNRecent(self::FETCH_COUNT);
		return $result['entry'][0]['id'];
	}

}
