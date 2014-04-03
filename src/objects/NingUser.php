<?php

require_once('NingObject.php');

class NingUser extends NingObject {

    protected $objectKey = 'User';
    protected $extraFields = array(
        'title',
        'description',
        'url',
        'featuredDate',
        'email',
        'fullName',
        'thumbnailUrl',
        'birthdate',
        'gender',
        'displayAge',
        'displayGender',
        'location',
        'zip',
        'country',
        'isPrivate',
        'searchText',
        'isAdmin',
        'profileAddress',
        'memberStatus',
        'statusMessage',
        'roles',
        'memberActive',
        'profileQuestions',
        'author.fullName',
        'author.iconUrl',
        'author.url');

    public function addStatusMessage($userId, $message, $args = array()) {
        $args = array(
            "id" => $userId,
            "statusMessage" => $message
        );

        return parent::update($args);
    }

}