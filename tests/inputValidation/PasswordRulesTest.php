<?php
declare(strict_types=1);

namespace freeman\jals\tests\inputValidation;

use PHPUnit\Framework\TestCase;
use freeman\jals\inputValidation\PasswordRules;

class PasswordRulesTest extends TestCase {

    public function provideValidRuleset() {

        return [
            [2, 2, 2, 2, 8, 16, '@-_?', '1234567890', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'],
            [0, 0, 0, 0, 2, 160, '@-_?', '1234567890', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'],
            [0, 0, 0, 0, 4, 16, '', '', '', ''],
            [0, 0, 2, 2, 4, 16, '', '', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz']
        ];
    }
    public function provideInvalidRuleset() {

        return [
            [2, 2, 2, 2, 6, 2, '@-_?', '1234567890', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'], //MAX BELOW MIN
            [2, 2, 2, 2, 1, 7, '@-_?', '1234567890', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'], //MAX CHARS TOO LOW
            [-1, 0, 0, 0, 1, 16, '@-_?', '1234567890', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'], //REQUIREMENT BELOW ZERO
            [1, 0, 0, 0, 1, 16, '', '1234567890', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'], //MISSING VALID SYMBOLS
            [0, 1, 0, 0, 1, 16, '@-_?', '', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', 'abcdefghijklmnopqrstuvwxyz'], //MISSING VALID NUMBERS
            [0, 0, 1, 0, 1, 16, '@-_?', '1234567890', '', 'abcdefghijklmnopqrstuvwxyz'], //MISSING VALID UPPERCASE
            [0, 0, 0, 1, 1, 16, '@-_?', '1234567890', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ', ''], //MISSING VALID LOWERCASE
        ];
    }

    /**
     * @dataProvider provideValidRuleset
     */
    public function testCreatePasswordRules(
        int $reqSymbols,
        int $reqNumbers,
        int $reqUpper,
        int $reqLower,
        int $reqMinLength,
        int $reqMaxLength,
        string $validSymbols,
        string $validNumbers,
        string $validUpper,
        string $validLower
    ) {

        $rules = new PasswordRules($reqSymbols, $reqNumbers, $reqUpper, $reqLower, $reqMinLength, $reqMaxLength, $validSymbols, $validNumbers, $validUpper, $validLower);
        self::assertInstanceOf(PasswordRules::class, $rules);
    }

    /**
     * @dataProvider provideInvalidRuleset
     * @expectedException \Exception
     */
    public function testFailedCreatePasswordRules(
        int $reqSymbols,
        int $reqNumbers,
        int $reqUpper,
        int $reqLower,
        int $reqMinLength,
        int $reqMaxLength,
        string $validSymbols,
        string $validNumbers,
        string $validUpper,
        string $validLower
    ) {
        $rules = new PasswordRules($reqSymbols, $reqNumbers, $reqUpper, $reqLower, $reqMinLength, $reqMaxLength, $validSymbols, $validNumbers, $validUpper, $validLower);
    }

}