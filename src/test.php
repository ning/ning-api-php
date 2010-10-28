<?php
require_once("NingApi.php");
$parts = array(
    "title" => "Updated Photo Title",
    "description" => "Updated Photo Description",
    "id" => "3011345:Photo:3968"
);

print_r(NingPhoto::createPhoto($parts));

?>