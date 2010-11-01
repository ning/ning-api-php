<?php

require_once('NingObject.php');

class NingComment extends NingObject {

    protected $objectKey = 'Comment';

    public function fetchNRecent($n = 1, $args = array()) {
        return parent::fetchNRecent($n, $args);
    }

    public function fetchRecent($args = array()) {
        return parent::fetchRecent($args);
    }

    public function delete($args) {
        return parent::delete($args);
    }

}