<?php

require_once('NingObject.php');

class NingBlogPost extends NingObject {

    protected $objectKey = 'BlogPost';

    public function fetch($args) {
        return parent::fetch($args);
    }

    public function update($args) {
        return parent::update($args);
    }

    public function create($args) {
        return parent::create($args);
    }

    public function delete($args) {
        return parent::delete($args);
    }

    public function fetchNRecent($n = 1, $args = array()) {
        return parent::fetchNRecent($n, $args);
    }

    public function fetchRecent($args = array()) {
        return parent::fetchRecent($args);
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