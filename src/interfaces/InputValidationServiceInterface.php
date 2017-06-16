<?php

namespace freeman\jals\interfaces;

interface InputValidationServiceInterface {

    public function validateEmail(string $email);

    public function validatePasswordRules(string $password);

}