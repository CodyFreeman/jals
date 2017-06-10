<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;

interface PasswordValidatorInterface {
    public function __construct(PasswordRulesInterface $rules);

    public function validatePassword(string $password):bool;

}