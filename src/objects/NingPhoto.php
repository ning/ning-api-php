<?php

require_once('NingObject.php');

class NingPhoto extends NingObject {

    protected $objectKey = 'Photo';
    protected $extraFields = array('author', 'commentCount', 'createdDate', 'description', 'id',
        'image', 'tags', 'title', 'updatedDate', 'url', 'visibility', 'author.fullName',
        'author.url', 'author.iconUrl', 'image.url', 'image.width', 'image.height');

    public function create($args = array()) {
        return parent::create($args);
    }

    public function update($args = array()) {
        return parent::update($args);
    }

    public function updateById($id, $args = array()) {
        return parent::updateById($id, $args);
    }

    public function fetch($args = array()) {
        return parent::fetch($args);
    }

    public function fetchNRecent($n = 1, $args = array()) {
        return parent::fetchNRecent($n, $args);
    }

    public function fetchRecent($args = array()) {
        return parent::fetchRecent($args);
    }

    public function fetchRecentNextPage($args = array()) {
        return parent::fetchRecentNextPage($args);
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