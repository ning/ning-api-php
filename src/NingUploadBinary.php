<?php

/**
 * Upload representing literal data in a variable
 */
class NingUploadBinary extends NingUpload {

    /**
     * The "filename" to send to the server for the uploaded data
     * @var string
     */
    protected $filename = NULL;

    /**
     * Keep track of the data and MIME type but also an
     * optional filename.
     *
     * @param $data string Literal binary data to upload
     * @param $type string optional MIME type
     * @param $filename string optional Filename to send to server
     */
    public function __construct($data, $type = null, $filename = null) {
        parent::__construct($data, $type);
        $this->filename = null;
    }

    /**
     * Write the binary data to a temp file and then produce the
     * right value for cURL to upload that temp file
     * @return string
     */
    public function __toString() {
        $tempFile = tempnam(sys_get_temp_dir(), "binaryupload");
        file_put_contents($tempFile, $this->data);
        /* Make sure the temp file gets deleted when we're done */
        register_shutdown_function('unlink',$tempFile);
        $s = '@' . $tempFile;
        $s = $this->appendParam($s, 'type');
        $s = $this->appendParam($s, 'filename');
        return $s;
    }
}