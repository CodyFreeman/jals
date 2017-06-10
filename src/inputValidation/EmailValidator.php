<?php
declare(strict_types=1);


namespace freeman\jals\inputValidation;

use freeman\jals\interfaces\EmailValidatorInterface;

class EmailValidator implements EmailValidatorInterface {

    /**
     * Validates email address format
     *
     * @param string $email email to validate the format of
     * @return bool True the $email has the format of an email, otherwise false
     */
    public function validateEmailFormat(string $email): bool {
        $sanitizedEmail = filter_var($email, FILTER_SANITIZE_EMAIL);
        if ($email !== $sanitizedEmail || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }
}