<?php

require_once('NingObject.php');

class NingComment extends NingObject {

    protected $objectKey = 'Comment';
    protected $extraFields = array('approved', 'attachedTo', 'attachedToAuthor', 'attachedToType',
        'author', 'createdDate', 'description', 'id', 'updatedDate', 'attachedToAuthor.fullName',
        'attachedToAuthor.url', 'attachedToAuthor.iconUrl', 'author.fullName', 'author.url',
        'author.iconUrl');

    public function fetchNRecent($n = 1, $args = array()) {
        return parent::fetchNRecent($n, $args);
    }

    public function fetchRecent($args = array()) {
        return parent::fetchRecent($args);
    }

    public function delete($args = array()) {
        return parent::delete($args);
    }

    public function deleteById($id, $args = array()) {
        parent::deleteById($id, $args);
    }

    public function create($args = array()) {
        return parent::create($args);
    }

}