<?php
require_once('NingObject.php');

class NingFriend extends NingObject {

	protected $objectKey = 'Friend';
	protected $defaultFields = array();
	protected $extraFields = array('author', 'friend', 'createdDate', 'updatedDate');

	public function create($args = array()) {
		return parent::create($args);
	}

	public function delete($author, $args = array()) {
		$args[parent::ID] = $author;
		return parent::delete($args);
	}

	public function fetchRecent($args = array()) {
		return parent::fetchRecent($args);
	}

	public function fetchNRecent($n = 1, $args = array()) {
		return parent::fetchNRecent($n, $args);
	}

	public function fetchRecentNextPage($args = array()) {
		return parent::fetchRecentNextPage($args);
	}

}
