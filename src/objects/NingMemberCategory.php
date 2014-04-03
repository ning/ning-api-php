<?php

require_once('NingObject.php');

class NingMemberCategory extends NingObject {

    protected $objectKey = 'MemberCategory';
    protected $extraFields = array(
        'title',
        'slug',
        'hasSubmenu',
        'submenuTitle',
        'hasBadge',
        'textColor',
        'bgColor',
        'opacity',
        'badgePosition',
        'imageId',
        'imageUrl',
        'author.fullName',
        'author.url',
        'author.iconUrl'
        );

}