<?php

require_once('NingApi.php');
require_once(dirname(__FILE__) . '/objects/NingActivityItem.php');
require_once(dirname(__FILE__) . '/objects/NingBlogPost.php');
require_once(dirname(__FILE__) . '/objects/NingBroadcastMessage.php');
require_once(dirname(__FILE__) . '/objects/NingComment.php');
require_once(dirname(__FILE__) . '/objects/NingNetwork.php');
require_once(dirname(__FILE__) . '/objects/NingPhoto.php');
require_once(dirname(__FILE__) . '/objects/NingUser.php');

abstract class NingObject {

    protected $objectKey;
    protected $defaultFields = array('id', 'author', 'createdDate', 'updatedDate', 'title', 'description',
        'visibility', 'approved', 'commentCount', 'url', 'tags', 'author.fullName', 'author.iconUrl',
        'author.url');
    protected $extraFields = array();

    const RECENT = 'recent';
    const COUNT = 'count';
    const FIELDS = 'fields';
    const CREATED_AFTER = 'createdAfter';
    const ID = 'id';
    const ALPHA = 'alpha';

    public function __construct() {
        $this->defaultFields = array_merge($this->defaultFields, $this->extraFields);
    }

    protected function create($args) {
        return NingApi::instance()->post($this->objectKey, $args);
    }

    protected function fetch($args) {
        return NingApi::instance()->get($this->objectKey, $args);
    }

    protected function update($args) {
        return NingApi::instance()->put($this->objectKey, $args);
    }

    protected function delete($args) {
        return NingApi::instance()->delete($this->objectKey, $args);
    }

    protected function updateById($id, $args=array()) {
        $args[self::ID] = $id;
        return $this->update($args);
    }

    protected function deleteById($id, $args=array()) {
        $args[self::ID] = $id;
        return $this->update($args);
    }

    protected function fetchRecent($args = array()) {
        if (!isset($args[self::FIELDS])) {
            $args[self::FIELDS] = implode(',', $this->defaultFields);
        }
        $params = http_build_query($args);
        $path = $this->objectKey . '/' . self::RECENT . '?' . $params;
        return $this->get($path);
    }

    protected function fetchNRecent($n=1, $args = array()) {
        $args[self::COUNT] = $n;
        return $this->fetchRecent($args);
    }

    protected function getCount($args = array()) {
        $path = $this->objectKey . '/' . self::COUNT;
        return $this->get($path, $args);
    }

    protected function getCountCreatedAfter($date, $args = array()) {
        $args[self::CREATED_AFTER] = $date;
        return $this->getCount($args);
    }

    protected function getCountCreatedInLastNDays($n, $args = array()) {
        $timeOffset = $n * 24 * 60 * 60;
        $nDaysAgoTimestamp = time() - $timeOffset;
        $nDaysAgoDate = gmdate('c', $nDaysAgoTimestamp);
        return $this->getCountCreatedAfter($nDaysAgoDate);
    }

    protected function fetchAlphabetical($args = array()) {
        $path = $this->objectKey . '/' . self::COUNT;
        return $this->get($path, $args);
    }

}