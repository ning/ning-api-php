<?php

require_once('NingObject.php');

class NingBroadcastMessage extends NingObject {

    protected $objectKey = 'BroadcastMessage';
    protected $extraFields = array('subject', 'body', 'messageId');

    public function create($args = array()) {
        return parent::create($args);
    }

    public function createMessage($subject, $body) {
        $args = array();
        $args['subject'] = $subject;
        $args['body'] = $body;
        $args['messageId'] = md5($subject . $body);
        return $this->create($args);
    }

}