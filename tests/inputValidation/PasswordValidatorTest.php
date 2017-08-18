<?php namespace freeman\jals\tests\inputValidation;

use freeman\jals\inputValidation\PasswordRules;
use freeman\jals\inputValidation\PasswordValidator;
use PHPUnit\Framework\TestCase;

class PasswordValidatorTest extends TestCase {

    public function setUp() {

        $rulesMock = $this->createMock(PasswordRules::class);

        $rulesMock->method('getReqSymbols')->willReturn(2);
        $rulesMock->method('getReqNumbers')->willReturn(2);
        $rulesMock->method('getReqUpper')->willReturn(2);
        $rulesMock->method('getReqLower')->willReturn(2);
        $rulesMock->method('getMinLength')->willReturn(8);
        $rulesMock->method('getMaxLength')->willReturn(16);
        $rulesMock->method('getValidSymbols')->willReturn('@-_?');
        $rulesMock->method('getValidNumbers')->willReturn('1234567890');
        $rulesMock->method('getValidUpper')->willReturn('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $rulesMock->method('getValidLower')->willReturn('abcdefghijklmnopqrstuvwxyz');

        $this->rulesMock = $rulesMock;
    }

    public function tearDown() {
        unset($this->rulesMock);
    }

    public function provideValidPasswords() {

        return [
            ['AAaa11@@'],
            ['422@@_kOlaEf']
        ];
    }

    public function provideInvalidPasswords() {

        return [
            [''],   //TOO SHORT
            ['aaAA@@11aaAA@@11aaAA@@11'], //TOO LONG
            ['aaAA11aa'], //CONTAINS NO SYMBOLS
            ['aaAA@@aa'], //CONTAINS NO NUMBERS
            ['AA@@11@@'], //CONTAINS NO LOWERCASE
            ['aa@@aa11'], //CONTAINS NO UPPERCASE
            ['aA@1&&&&&&&'] //CONTAINS INVALID SYMBOLS
        ];
    }

    /**
     * @dataProvider provideValidPasswords
     */
    public function testValidatePasswordValidPassword(string $password) {

        $passwordValidator = new PasswordValidator($this->rulesMock);
        self::assertTrue($passwordValidator->validatePassword($password));
    }

    /**
     * @dataProvider provideInvalidPasswords
     */
    public function validatePasswordInvalidPassword(string $password) {

        $passwordValidator = new PasswordValidator($this->rulesMock);
        self::assertFalse($passwordValidator->validatePassword($password));
    }
}