<?php

namespace freeman\jals\tests\inputValidation;

use PHPUnit\Framework\TestCase;
use freeman\jals\inputValidation\EmailValidator;

class EmailValidatorTest extends TestCase {

    /* SETTING UP DATA PROVIDERS */
    //TODO: Consider: do i even need a data provider? Am i not just testing filter_var?
    public function provideInvalidEmails() {

        return [
            ['.thisIsInvalid@example.com'],
            ['NoTopLevel@example'],
            ['']
        ];
    }

    /* TESTING PUBLIC METHOD validateEmail */
    /**
     * @dataProvider provideInvalidEmails
     */
    public function testValidateEmailInvalidEmail($email) {

        $validator = new EmailValidator();
        self::assertFalse($validator->validateEmailFormat($email));
    }

    public function testValidateEmailValidEmail() {

        $validator = new EmailValidator();
        $email = 'example@example.com';
        self::assertTrue($validator->validateEmailFormat($email));
    }
}
