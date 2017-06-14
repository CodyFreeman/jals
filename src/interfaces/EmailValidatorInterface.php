<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;

interface EmailValidatorInterface {

    /**
     * @param string $email
     * @return bool
     */
    public function validateEmailFormat(string $email):bool;
}