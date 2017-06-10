<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;

interface EmailValidatorInterface {
    public function validateEmailFormat(string $email):bool;
}