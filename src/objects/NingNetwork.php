<?php

require_once('NingObject.php');

class NingNetwork extends NingObject {

    protected $objectKey = 'Network';

    public function fetch($args) {
        return parent::fetch($args);
    }

    public function fetchAlphabetical($args = array()) {
        return parent::getAlphabetical($args);
    }

}