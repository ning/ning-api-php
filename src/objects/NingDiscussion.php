<?php

require_once('NingObject.php');

class NingDiscussion extends NingObject {

    protected $objectKey = 'Discussion';
    protected $extraFields = array(
        'title',
        'description',
        'url',
        'featureTime',
        'slug',
        'tagNames',
        'categoryNames',
        'author.fullName',
        'author.url',
        'author.iconUrl'
        );

}