<?php

require_once('NingTestHelper.php');

/**
 * @group Photo
 */
class NingPhotoTest extends PHPUnit_Framework_TestCase {

    public static function photoData() {
        $parts = array(
            "title" => "Photo Title",
            "description" => "Photo Description",
            "tag" => "tests",
            "file" => "@" . __DIR__ . "/files/sample.jpg"
        );
        return $parts;
    }

    public function testCreate() {
        $result = NingApi::instance()->photo->create(self::photoData());
        $this->assertTrue($result['success']);
    }

    public function testUpdate() {
        $newPhoto = NingApi::instance()->photo->create(self::photoData());
        $result = NingApi::instance()->photo->update($newPhoto);
        $this->assertTrue($result['success']);
    }

    public function testUpdateById() {
        $newPhoto = NingApi::instance()->photo->create(self::photoData());
        $args = array('id' => $newPhoto['id']);
        $result = NingApi::instance()->photo->updateById($args);
        $this->assertTrue($result['success']);
    }

    public function testFetch() {
        $newPhoto = NingApi::instance()->photo->create(self::photoData());
        $args = array('id' => $newPhoto['id']);
        $result = NingApi::instance()->photo->fetch($args);
        $this->assertTrue($result['success']);
    }

    public function testFetchNRecent() {
        $result = NingApi::instance()->photo->fetchNRecent();
        $this->assertTrue($result['success']);
    }

    public function testFetchRecent() {
        $result = NingApi::instance()->photo->fetchRecent();
        $this->assertTrue($result['success']);
    }

    public function testDelete() {
        $newPhoto = NingApi::instance()->photo->create(self::photoData());
        $result = NingApi::instance()->photo->delete($newPhoto);
        $this->assertTrue($result['success']);
    }

    public function testGetCount() {
        $args = array('createdAfter' => gmdate('c'));
        $result = NingApi::instance()->photo->getCount($args);
        $this->assertTrue($result['success']);
    }

    public function testGetCountCreatedAfter() {
        $result = NingApi::instance()->photo->getCountCreatedAfter(gmdate('c'));
        $this->assertTrue($result['success']);
    }

    public function testGetCountCreatedInLastNDays() {
        $result = NingApi::instance()->photo->getCountCreatedInLastNDays(3);
        $this->assertTrue($result['success']);
    }

    private function create() {
        $parts = array(
            "title" => "Photo Title",
            "description" => "Photo Description",
            "file" => "@" . __DIR__ . "/files/sample.jpg"
        );

        $result = NingApi::instance()->post("Photo", $parts);
        $this->assertTrue($result['success']);

        return $result;
    }

    public function testRecent_old() {
        $count = 3;
        $fields = "title,author.url";
        $path = sprintf("Photo/recent?count=%s&fields=%s", $count,
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
            "title" => "Updated Photo Title",
            "description" => "Updated Photo Description",
            "id" => $result['id']
        );

        $result = NingApi::instance()->put("Photo", $parts);
        $this->assertTrue($result['success']);
    }

    public function testDelete_old() {
        $result = $result = $this->create();

        $path = sprintf("Photo?id=%s", $result['id']);

        $result = NingApi::instance()->delete($path);
        $this->assertTrue($result['success']);
    }

    public function testCount_old() {
        date_default_timezone_set("UTC");
        $date = date("Y-m-d\TH:i:s\Z", strtotime("-2 days"));

        $path = sprintf("Photo/count?createdAfter=%s", $date);
        $result = NingApi::instance()->get($path);
        $this->assertTrue($result['success']);
    }

}

?>
