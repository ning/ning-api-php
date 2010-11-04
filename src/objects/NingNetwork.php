<?php

require_once('NingObject.php');

class NingNetwork extends NingObject {

    protected $objectKey = 'Network';
    protected $extraFields = array('subdomain', 'name', 'iconUrl', 'defaultUserIconUrl',
        'blogPostModeration', 'userModeration', 'photoModeration', 'eventModeration',
        'groupModeration', 'videoModeration', 'author.fullName', 'author.iconUrl', 'author.url');

    public function fetch($args = array()) {
        return parent::fetch($args);
    }

    public function fetchAlphabetical($args = array()) {
        return parent::fetchAlphabetical($args);
    }

}