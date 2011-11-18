<?php

require_once('NingApi.php');
require_once('NingActivityItem.php');
require_once('NingBlogPost.php');
require_once('NingBroadcastMessage.php');
require_once('NingComment.php');
require_once('NingNetwork.php');
require_once('NingPhoto.php');
require_once('NingUser.php');
require_once('NingVideo.php');

abstract class NingObject {

    protected $objectKey;
    protected $defaultFields = array('id', 'author', 'createdDate');
    protected $extraFields = array();
    protected $lastAnchor = null;
    protected $pageNumber = 1;

    const AUTHOR = 'author';
    const RECENT = 'recent';
    const COUNT = 'count';
    const FIELDS = 'fields';
    const SUCCESS = 'success';
    const CREATED_AFTER = 'createdAfter';
    const ID = 'id';
    const ALPHA = 'alpha';
    const ANCHOR = 'anchor';

    const DEFAULT_COUNT = 100;

    public function __construct() {
        $this->defaultFields = array_merge($this->defaultFields, $this->extraFields);
    }

    protected function create($args = array()) {
        return NingApi::instance()->post($this->objectKey, $args);
    }

    protected function fetch($args = array()) {
        $this->addDefaultFields($args);
        return NingApi::instance()->get($this->objectKey, $args);
    }

    protected function fetchN($n, $args = array()) {
        $args[self::COUNT] = $n;
        return $this->fetch($args);
    }

    protected function update($args = array()) {
        return NingApi::instance()->put($this->objectKey, $args);
    }

    protected function delete($args = array()) {
        return NingApi::instance()->delete($this->objectKey, $args);
    }

    protected function updateById($id, $args=array()) {
        $args[self::ID] = $id;
        return $this->update($args);
    }

    protected function deleteById($id, $args=array()) {
        $args[self::ID] = $id;
        return $this->delete($args);
    }

    protected function fetchRecent($args = array()) {
        $this->addDefaultFields($args);
        $params = http_build_query($args);
        $path = $this->objectKey . '/' . self::RECENT . '?' . $params;
        $result = NingApi::instance()->get($path);
        self::saveAnchor($result);
        return $result;
    }

    protected function fetchNRecent($n=1, $args = array()) {
        $args[self::COUNT] = $n;
        return $this->fetchRecent($args);
    }

    protected function fetchByAuthor($author, $args = array()) {
        $args[self::AUTHOR] = $author;
        return $this->fetch($args);
    }

    protected function getCount($args = array()) {
        $path = $this->objectKey . '/' . self::COUNT;
        return NingApi::instance()->get($path, $args);
    }

    protected function fetchRecentNextPage($args = array()) {
        $this->addLastAnchor($args);
        $result = $this->fetchNRecent(self::DEFAULT_COUNT, $args);
        $result['pageNumber'] = $this->pageNumber;
        $result['pageFrom'] = ($this->pageNumber - 1) * self::DEFAULT_COUNT;
        $result['pageTo'] = $result['pageFrom'] + count($result['entry']);
        $this->pageNumber++;
        if ($result['lastPage'] == 1) {
            $this->pageNumber = 1;
        }

        return $result;
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
        $path = $this->objectKey . '/' . self::ALPHA;
        $this->addDefaultFields($args);
        return NingApi::instance()->get($path, $args);
    }

    protected function fetchNAlphabetical($n, $args = array()) {
        $args[self::COUNT] = $n;
        return $this->fetchAlphabetical($args);
    }

    /**
     * Adds the request fields that are common to all Ning API requests.
     */
    private function addDefaultFields(&$args) {
        if (!isset($args[self::FIELDS])) {
            $args[self::FIELDS] = implode(',', $this->defaultFields);
        }
    }

    /**
     * Adds the most recent anchor to the request arguments
     */
    private function addLastAnchor(&$args) {
        if (!is_null($this->lastAnchor)) {
            $args[self::ANCHOR] = $this->lastAnchor;
        }
    }

    /**
     * Saves a local copy of the anchor value in the result
     */
    private function saveAnchor($result) {
        if ($result[self::SUCCESS]) {
            $this->lastAnchor = $result[self::ANCHOR];
        }
    }
}
