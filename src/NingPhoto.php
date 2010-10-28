<?php
class NingPhoto{
    public static function createPhoto($args){
        NingApi::instance()->put("photo", $args);
    }
}


?>