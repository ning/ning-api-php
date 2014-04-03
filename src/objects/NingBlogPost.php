<?php

require_once('NingObject.php');

class NingBlogPost extends NingObject {

    protected $objectKey = 'BlogPost';
    protected $extraFields = array(
        'title',
        'description',
        'url',
        'excerpt',
        'imageUrl',
        'bundleId',
        'publishTime',
        'publishStatus',
        'featureTime',
        'slug',
        'tagNames',
        'approved',
        'imageId',
        'imageWidth',
        'imageHeight',
        'imageRotation',
        'categoryNames',
        'author.fullName',
        'author.url',
        'author.iconUrl'
        );

}