<?php

require_once('NingObject.php');

class NingVideo extends NingObject {

    protected $objectKey = 'Video';
    protected $extraFields = array('approved', 'author', 'commentCount', 'conversionStatus',
        'createdDate', 'description', 'duration', 'embedCode', 'id', 'previewFrame', 'tags',
        'title', 'updatedDate', 'url', 'videoAttachmentUrl', 'videoSizeInBytes', 'visibility',
        'author.fullName', 'author.url', 'author.iconUrl', 'previewFrame.url', 'previewFrame.width',
        'previewFrame.height');

    public function fetch($args = array()) {
        return parent::fetch($args);
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
