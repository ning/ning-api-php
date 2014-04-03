<?php

require_once('NingObject.php');

class NingComment extends NingObject {

    protected $objectKey = 'Comment';
    protected $extraFields = array(
        'approved',
        'attachedTo',
        'attachedToAuthor',
        'attachedToType',
        'description',
        'attachedToAuthor.fullName',
        'attachedToAuthor.url',
        'attachedToAuthor.iconUrl',
        'author.fullName',
        'author.url',
        'author.iconUrl');

}