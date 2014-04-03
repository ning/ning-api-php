<?php

/**
 * Upload representing a local file
 */
class NingUploadFile extends NingUpload {

    /**
     * cURL's syntax for uploading a file is
     *  @pathname;type=mime-type
     *
     * @return string
     */
    public function __toString() {
        $s = '@' . $this->data;
        $s = $this->appendParam($s, 'type');
        return $s;
    }
}
