<?php
declare(strict_types=1);

namespace freeman\jals\services;

use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
use freeman\jals\interfaces\EmailValidatorInterface;

class InputValidationService implements InputValidationServiceInterface {

    /** @var EmailValidatorInterface $emailValidator */
    protected $emailValidator;

    /** @var PasswordValidatorInterface $passwordValidator */
    protected $passwordValidator;

    public function __construct(EmailValidatorInterface $emailValidator, PasswordValidatorInterface $passwordValidator) {
        $this->emailValidator = $emailValidator;
        $this->passwordValidator = $passwordValidator;
    }

    /**
     * Validates input is an email
     *
     * @param string $email Email to validate
     * @return bool True if conforming to email format, false otherwise
     */
    public function validateEmail(string $email):bool {
        return $this->emailValidator->validateEmailFormat($email);
    }

    /**
     * Validates input conforms to configured password rules
     *
     * @param string $password Password to validate
     * @return bool True if input conforms to password rules, false otherwise
     */
    public function validatePasswordRules(string $password):bool {
        return $this->passwordValidator->validatePassword($password);
    }
}