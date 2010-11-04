<?php

require_once('NingObject.php');

class NingActivityItem extends NingObject {

    protected $objectKey = 'Activity';
    protected $extraFields = array('attachedTo', 'attachedToAuthor', 'attachedToType', 'author',
        'contentId', 'createdDate', 'description', 'id', 'image', 'type', 'title', 'url',
        'attachedToAuthor.fullName', 'attachedToAuthor.url', 'attachedToAuthor.iconUrl',
        'author.fullName', 'author.url', 'author.iconUrl', 'image.url', 'image.width',
        'image.height');

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
        return parent::deleteById($id, $args);
    }

    public function getCount($args = array()) {
        return parent::getCount($args);
    }

    public function getCountCreatedAfter($date, $args = array()) {
        return parent::getCountCreatedAfter($date, $args);
    }

    public function getCountCreatedInLastNDays($n, $args = array()) {
        return parent::getCountCreatedInLastNDays($n, $args);
    }

}