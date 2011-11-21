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
require_once('NingFriend.php');
require_once('NingTag.php');

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
    const ATTACHED_TO = 'attachedTo';
    const FRIEND = 'friend';

    const SORT_RECENT = 'recent';
    const SORT_ALPHA = 'alpha';
    const SORT_UNORDERED = 'list';

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

    protected function fetchSorted($sortType, $args = array()) {
        $this->addDefaultFields($args);
        $params = http_build_query($args);
        $path = $this->objectKey . '/' . $sortType . '?' . $params;
        $result = NingApi::instance()->get($path);
        self::saveAnchor($result);
        return $result;
    }

    protected function fetchRecent($args = array()) {
        return self::fetchSorted(self::SORT_RECENT, $args);
    }

    protected function fetchAlpha($args = array()) {
        return self::fetchSorted(self::SORT_ALPHA, $args);
    }

    protected function fetchUnordered($args = array()) {
        return self::fetchSorted(self::SORT_UNORDERED, $args);
    }

    protected function fetchNRecent($n=1, $args = array()) {
        $args[self::COUNT] = $n;
        return self::fetchSorted(self::SORT_RECENT, $args);
    }

    protected function fetchNAlpha($n=1, $args = array()) {
        $args[self::COUNT] = $n;
        return self::fetchSorted(self::SORT_ALPHA, $args);
    }

    protected function fetchNUnordered($n=1, $args = array()) {
        $args[self::COUNT] = $n;
        return self::fetchSorted(self::SORT_UNORDERED, $args);
    }

    protected function fetchSortedNextPage($sortType, $args = array()) {
        $this->addLastAnchor($args);
        $this->addDefaultCount($args);
        $result = $this->fetchSorted($sortType, $args);
        $result['pageNumber'] = $this->pageNumber;
        $result['pageFrom'] = ($this->pageNumber - 1) * self::DEFAULT_COUNT;
        $result['pageTo'] = $result['pageFrom'] + count($result['entry']);
        $this->pageNumber++;
        if ($result['lastPage'] == 1) {
            $this->pageNumber = 1;
        }

        return $result;
    }

    protected function fetchRecentNextPage($args = array()) {
        return self::fetchSortedNextPage(self::SORT_RECENT, $args);
    }

    protected function fetchAlphaNextPage($args = array()) {
        return self::fetchSortedNextPage(self::SORT_ALPHA, $args);
    }

    protected function fetchUnorderedNextPage($args = array()) {
        return self::fetchSortedNextPage(self::SORT_UNORDERED, $args);
    }

    protected function fetchByAuthor($author, $args = array()) {
        $args[self::AUTHOR] = $author;
        return $this->fetch($args);
    }

    protected function getCount($args = array()) {
        $path = $this->objectKey . '/' . self::COUNT;
        return NingApi::instance()->get($path, $args);
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

    /**
     * Adds a default count parameter if it hasn't been set
     */
    private function addDefaultCount(&$args) {
        if (!isset($args[self::COUNT])) {
            $args[self::COUNT] = self::DEFAULT_COUNT;
        }
    }
}
