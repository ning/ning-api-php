<?php

require_once('NingObject.php');

class NingNetwork extends NingObject {

    protected $objectKey = 'Network';
    protected $extraFields = array(
        'subdomain',
        'name',
        'iconUrl',
        'defaultUserIconUrl',
        'apiVersions',
        'profileQuestions'
        );

}