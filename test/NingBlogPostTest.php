<?php

require_once('NingTestHelper.php');

/**
 * @group BlogPost
 */
class NingBlogPostTest extends PHPUnit_Framework_TestCase {

    public function createNewBlogPost() {
        $args = array();
        $args['description'] = "Test blog post description.";
        $args['title'] = "Test blog post title.";
        $args['tag'] = "tests";
        return NingApi::instance()->blogPost->create($args);
    }

    public function testCreate() {
        $result = $this->createNewBlogPost();
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $newBlogPost = $this->createNewBlogPost();
        $args = array('id' => $newBlogPost['id']);
        $result = NingApi::instance()->blogPost->delete($args);
        $this->assertTrue($result['success']);
    }

    public function testUpdate() {
        $newBlogPost = $this->createNewBlogPost();
        $args = array('id' => $newBlogPost['id']);
        $result = NingApi::instance()->blogPost->update($args);
        $this->assertTrue($result['success']);
    }

    public function testFetch() {
        $newBlogPost = $this->createNewBlogPost();
        $args = array('id' => $newBlogPost['id']);
        $result = NingApi::instance()->blogPost->fetch($args);
        $this->assertTrue($result['success']);
    }

    public function testGetCount() {
        $args = array('createdAfter' => gmdate('c',@strtotime("-2 days")));
        $result = NingApi::instance()->blogPost->getCount($args);
        $this->assertTrue($result['success']);
    }

    public function testGetCountCreatedAfter() {
        $result = NingApi::instance()->blogPost->getCountCreatedAfter(gmdate('c',@strtotime("-2 days")));
        $this->assertTrue($result['success']);
    }

    public function testGetCountCreatedInLastNDays() {
        $result = NingApi::instance()->blogPost->getCountCreatedInLastNDays(3);
        $this->assertTrue($result['success']);
    }

    public function testFetchRecent() {
        $result = NingApi::instance()->blogPost->fetchRecent();
        $this->assertTrue($result['success']);
    }

    public function testFetchNRecent() {
        $result = NingApi::instance()->blogPost->fetchNRecent(3);
        $this->assertTrue($result['success']);
    }

    public function testFetchRecentNextPage() {
        $result = NingApi::instance()->blogPost->fetchRecentNextPage();
        $this->assertTrue($result['success']);
    }

    private function create() {
        $parts = array(
            "title" => "BlogPost Title",
            "description" => "BlogPost Description",
            "tag" => "tests"
        );

        $result = NingApi::instance()->post("BlogPost", $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    public function testRecent_old() {
        $count = 3;
        $fields = "title,author.url";
        $path = sprintf("BlogPost/recent?count=%s&fields=%s", $count,
                        $fields);

        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

    public function testCreate_old() {
        $result = $this->create();
    }

    public function testUpdate_old() {
        $result = $result = $this->create();

        $parts = array(
            "title" => "Updated BlogPost Title",
            "description" => "Updated BlogPost Description",
            "id" => $result['id']
        );

        $result = NingApi::instance()->put("BlogPost", $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete_old() {
        $result = $result = $this->create();

        $path = sprintf("BlogPost?id=%s", $result['id']);

        $result = NingApi::instance()->delete($path);
        $this->assertTrue($result['success']);
    }

    public function testCount_old() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));

        $path = sprintf("BlogPost/count?createdAfter=%s", $date);
        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

}

?>
