<?php
/**
 * Abstract base class for file uploads of NingUploadBinary and NingUploadFile
 *
 */
abstract class NingUpload {
    /**
     * The data representing the upload, either a file path or literal data.
     * @var string
     */
    protected $data = NULL;
    /**
     * MIME type of the data
     * @var string
     */
    protected $type = NULL;

    /**
     * Keep track of the data and the type
     *
     * @param $data string data to upload (or file path)
     * @param $type string optional MIME type
     */
    public function __construct($data, $type = null) {
        $this->data = $data;
        $this->type = $type;
    }

    /**
     * Append a ;type or ;filename parameter as
     * necessary to a parameter being provided to cURL
     *
     * @param $s string Parameter before modification
     * @param $name string Property name of param to append
     * @return string
     */
    protected function appendParam($s, $name) {
        if (! is_null($this->$name)) {
            return $s . ";$name=" . $this->$name;
        } else {
            return $s;
        }
    }
}
