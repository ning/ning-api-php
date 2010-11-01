<?php

require_once("NingApi.php");
$parts = array(
    "title" => "Photo Title",
    "description" => "Photo Description",
    "file" => "@/Users/bplowman/Downloads/ning_picture.jpg"
);

$ningApi = new NingApi();

#result = array();
#$result['id'] = array('5029104:Photo:1347','5029104:Photo:1345');
#$types = 'title,description';
echo "About to call create()\n";
print_r($ningApi->photo->create($parts));
#print_r($ningApi->photos->fetchNRecent(2));
#print_r($ningApi->photos->getCount(array('createdAfter'=>'2010-10-23T02:46:21.190Z')));
#print_r($ningApi->photos->getCountCreatedInLastNDays(3));
?>