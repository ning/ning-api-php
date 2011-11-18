<?php

require_once('NingObject.php');

class NingUser extends NingObject {

    protected $objectKey = 'User';
    protected $extraFields = array('email', 'fullName', 'iconUrl', 'birthdate', 'commentCount',
        'gender', 'location', 'isOwner', 'isAdmin', 'isMember', 'isBlocked', 'state', 'statusMessage',
        'profileQuestions', 'author.fullName', 'author.iconUrl', 'author.url');

    public function __construct() {
        return parent::__construct();
    }

    public function fetch($args) {
        return parent::fetch($args);
    }

    public function update($args) {
        return parent::update($args);
    }

    public function addStatusMessage($userId, $message, $args = array()) {
        $args = array(
            "id" => $userId,
            "statusMessage" => $message
        );

        return parent::update($args);
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

    public function fetchAlphabetical($args = array()) {
        return parent::fetchAlphabetical($args);
    }

    public function fetchNAlphabetical($n, $args = array()) {
        return parent::fetchNAlphabetical($n, $args);
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