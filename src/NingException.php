<?php

class NingException extends Exception {

    // HTTP status code
    private $httpStatus;
    // Ning API error code
    private $ningCode;
    // Ning API error subcode
    private $ningSubcode;
    // Ning API trace number
    private $ningTrace;

    public function __construct($message, $status=NULL, $code=NULL, $subcode=NULL, $trace=NULL) {

        $this->httpStatus = $status;
        $this->ningCode = $code;
        $this->ningSubcode = $subcode;
        $this->ningTrace = $trace;

        parent::__construct($message);
    }

    public function getHttpStatus() {
        return $this->httpStatus;
    }

    public function getNingCode() {
        return $this->ningCode;
    }

    public function getNingSubcode() {
        return $this->ningSubcode;
    }

    public function getNingTrace() {
        return $this->ningTrace;
    }

    public function __toString() {
        $errorMessage = $this->getMessage();
        if ($this->getHttpStatus()) {
            $errorMessage = sprintf("%s (%s)", $errorMessage,
                            $this->getHttpStatus());
        }

        if ($this->getNingCode() && $this->getNingSubcode()) {
            $errorMessage = sprintf("%s %s-%s", $errorMessage,
                            $this->getNingCode(), $this->getNingSubcode());
        }

        return $errorMessage;
    }

    public static function generate($response) {
        return new NingException($response['reason'],
                $response['status'], $response['code'], $response['subcode']);
    }

}