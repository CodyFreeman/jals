<?php
declare(strict_types=1);

namespace freeman\jals\responseBodyTemplate;

class ResponseStatus {

    /** @var bool $hasError defines if errors are present */
    public $hasError = false;

    /** @var array $errors defines which errors were encountered */
    public $errors = [];

    /**
     * Adds am error to the error array and sets $hasError to true
     *
     * @param $msg string Error message
     * @param mixed $key Key of error array to insert message into
     */
    public function addError(string $msg, $key = null): void{
        $this->setHasError(true);
        !$key ? $this->errors[] = $msg : $this->errors[$key] = $msg;
    }

    /**
     * Sets $hasError to true or false
     *
     * @param bool $hasError
     */
    public function setHasError(bool $hasError): void{
        $this->hasError = $hasError;
    }
}