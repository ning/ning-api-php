<?php

require_once('NingObject.php');

class NingUser extends NingObject {

    protected $objectKey = 'User';

    public function fetch($args) {
        return parent::fetch($args);
    }

    public function update($args) {
        return parent::update($args);
    }

    public function fetchNRecent($n = 1, $args = array()) {
        return parent::fetchNRecent($n, $args);
    }

    public function fetchRecent($args = array()) {
        return parent::fetchRecent($args);
    }

    public function fetchAlphabetical($args = array()) {
        return parent::fetchAlphabetical($args);
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