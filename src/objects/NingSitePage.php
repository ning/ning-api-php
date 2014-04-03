<?php

require_once('NingObject.php');

class NingSitePage extends NingObject {

    protected $objectKey = 'SitePage';
    protected $extraFields = array(
        'title',
        'viewerTypes',
        'tabLabel',
        'targetType',
        'target',
        'windowTarget',
        'published',
        'categoryNames',
        'author.fullName',
        'author.url',
        'author.iconUrl'
        );

}