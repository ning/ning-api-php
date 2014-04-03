<?php

require_once('NingObject.php');

class NingGroup extends NingObject {

    protected $objectKey = 'Group';
    protected $extraFields = array(
        'title',
        'description',
        'url',
        'featureTime',
        'slug',
        'termForMembers',
        'imageUrl',
        'coverPhotoUrl',
        'approved',
        'moderateMembers',
        'membersCanSendInvites',
        'joinSetting',
        'adminsCanAddAndDeletePages',
        'adminsCanMessageMembers',
        'layoutPermissionType',
        'adminsCanUploadCoverPhotos',
        'customPaths',
        'navigationTree',
        'homepageId',
        'categoryNames',
        'author.fullName',
        'author.url',
        'author.iconUrl'
        );

}