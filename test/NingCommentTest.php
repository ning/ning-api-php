<?php

require_once('NingTestHelper.php');

/**
 * @group Comment
 */
class NingCommentTest extends PHPUnit_Framework_TestCase {

    /**
     * @group create
     */
    public function testCreate() {
        $args = array();
        $args['description'] = "This is a test comment.";
        $blogPosts = NingApi::instance()->blogPost->fetchNRecent();
        $args['attachedTo'] = $blogPosts['entry'][0]['id'];
        $result = NingApi::instance()->comment->create($args);
        $this->assertTrue($result['success']);
    }

    public function testFetchNRecent() {
        $blogPosts = NingApi::instance()->blogPost->fetchNRecent();
        $args = array('attachedTo' => $blogPosts['entry'][0]['id']);
        $result = NingApi::instance()->comment->fetchNRecent(1, $args);
        $this->assertTrue($result['success']);
    }

    public function testFetchRecent() {
        $blogPosts = NingApi::instance()->blogPost->fetchNRecent();
        $args = array('attachedTo' => $blogPosts['entry'][0]['id']);
        $result = NingApi::instance()->comment->fetchRecent($args);
        $this->assertTrue($result['success']);
    }

    /**
     * @group delete
     */
    public function testDelete() {
        $args = array();
        $blogPosts = NingApi::instance()->blogPost->fetchNRecent();
        $args['attachedTo'] = $blogPosts['entry'][0]['id'];
        $args['description'] = "This is a test comment.";
        $newComment = NingApi::instance()->comment->create($args);
        $args = array('id' => $newComment['id']);
        $result = NingApi::instance()->comment->delete($args);
        $this->assertTrue($result['success']);
    }

}

?>
