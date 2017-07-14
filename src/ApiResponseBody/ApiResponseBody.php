<?php
declare(strict_types=1);

namespace freeman\jals\ApiResponseBody;

use JsonSerializable;

class ApiResponseBody implements JsonSerializable {

    /** @var bool $hasError defines if errors are present */
    protected $hasError = false;

    /** @var array $errors defines which errors were encountered */
    protected $errors = [];

    /** @var array $data container of data to return */
    protected $data = [];

    public function jsonSerialize() {
        return [
            "status" => [
                "hasError" => $this->hasError,
                "errors" => $this->errors,
            ],
            "data" => $this->data
        ];
    }

    /**
     * Adds am error to the error array and sets $hasError to true
     *
     * @param $msg string Error message
     * @param mixed $key Key of error array to insert message into
     */
    public function addError(string $msg, $key = null): void {

        $this->setHasError(true);
        !$key ? $this->errors[] = $msg : $this->errors[$key] = $msg;
    }

    /**
     * Sets $hasError to true or false
     *
     * @param bool $hasError
     */
    public function setHasError(bool $hasError): void {

        $this->hasError = $hasError;
    }

    /**
     * Adds value to data array
     *
     * @param string $key
     * @param $value
     */
    public function addData(string $key, $value): void {

        $this->data[$key] = $value;
    }
}