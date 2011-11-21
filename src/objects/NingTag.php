<?php

require_once('NingObject.php');

class NingTag extends NingObject {

	protected $objectKey = 'Tag';
	protected $defaultFields = array();
	protected $extraFields = array();

	/**
	 * Fetch the tags attached to the given content id.
	 *
	 * @see NingObject::fetchUnordered()
	 */
	public function fetchUnordered($id, $args = array()) {
		$args[parent::ATTACHED_TO] = $id;
		return parent::fetchUnordered($args);
	}

	/**
	 * Fetch the specified number of tags attached to the given content id.
	 *
	 * @see NingObject::fetchNUnordered()
	 */
	public function fetchNUnordered($id, $n = 1, $args = array()) {
		$args[parent::ATTACHED_TO] = $id;
		return parent::fetchNUnordered($n, $args);
	}

	/**
	 * Fetch the next page of tags attached to the content id.
	 *
	 * @see NingObject::fetchUnorderedNextPage()
	 */
	public function fetchUnorderedNextPage($id, $args = array()) {
		$args[parent::ATTACHED_TO] = $id;
		return parent::fetchUnorderedNextPage($args);
	}

}
