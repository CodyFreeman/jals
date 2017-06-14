<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;

interface PasswordValidatorInterface {
    public function __construct(PasswordRulesInterface $rules);

    /**
     * @param string $password
     * @return bool
     */
    public function validatePassword(string $password):bool;

}