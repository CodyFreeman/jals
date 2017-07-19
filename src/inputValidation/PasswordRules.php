<?php

declare(strict_types=1);

namespace freeman\jals\inputValidation;

use freeman\jals\interfaces\PasswordRulesInterface;
use Exception;

class PasswordRules implements PasswordRulesInterface {

    protected $reqSymbols;
    protected $reqNumbers;
    protected $reqUpper;
    protected $reqLower;
    protected $minLength;
    protected $maxLength;
    protected $validSymbols;
    protected $validNumbers;
    protected $validUpper;
    protected $validLower;

    public function __construct(
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
        $this->validateRules($reqSymbols, $reqNumbers, $reqUpper, $reqLower, $reqMinLength, $reqMaxLength, $validSymbols, $validNumbers, $validUpper, $validLower);
        $this->reqSymbols = $reqSymbols;
        $this->reqNumbers = $reqNumbers;
        $this->reqUpper = $reqUpper;
        $this->reqLower = $reqLower;
        $this->minLength = $reqMinLength;
        $this->maxLength = $reqMaxLength;
        $this->validSymbols = $validSymbols;
        $this->validNumbers = $validNumbers;
        $this->validUpper = $validUpper;
        $this->validLower = $validLower;
    }

    protected function validateRules(
        int $reqPassSymbols,
        int $reqPassNumbers,
        int $reqPassUpper,
        int $reqPassLower,
        int $reqPassMinLength,
        int $reqPassMaxLength,
        string $validPassSymbols,
        string $validPassNumbers,
        string $validPassUpper,
        string $validPassLower
    ): void {

        if(min($reqPassSymbols, $reqPassNumbers, $reqPassUpper, $reqPassLower) < 0){
            throw new Exception('Error in supplied password rules');
        }

        if($reqPassMinLength > $reqPassMaxLength || min($reqPassMinLength, $reqPassMaxLength) < 1){
            throw new Exception('Error in supplied password rules');
        }

        $totalMinChars = $reqPassSymbols + $reqPassNumbers + $reqPassUpper + $reqPassLower;

        if($totalMinChars > $reqPassMaxLength){
            throw new Exception('Error in supplied password rules');
        }

        if(!$this->validateRule($reqPassSymbols, $validPassSymbols)){
            throw new Exception('Error in supplied password rules');
        }

        if(!$this->validateRule($reqPassNumbers, $validPassNumbers)){
            throw new Exception('Error in supplied password rules');
        }

        if(!$this->validateRule($reqPassUpper, $validPassUpper)){
            throw new Exception('Error in supplied password rules');
        }

        if(!$this->validateRule($reqPassLower, $validPassLower)){
            throw new Exception('Error in supplied password rules');
        }
    }

    /**
     * Validates $validCharacters is not empty if $requirement is above 0
     *
     * @param int $requirement Minimum number of this type of character
     * @param string $validCharacters Valid Characters
     * @return bool True if valid, false otherwise
     */
    protected function validateRule(int $requirement, string $validCharacters):bool{
        return $requirement > 0 && ! empty($validCharacters) || $requirement === 0;
    }

    /**
     * Returns an int of how many symbol characters are needed in a valid password
     *
     * @return int
     */
    public function getReqSymbols(): int {
        return $this->reqSymbols;
    }

    /**
    * Returns an int of how many numbers are needed in a valid password
     *
     * @return int
     */
    public function getReqNumbers(): int {
        return $this->reqNumbers;
    }

    /**
     * Returns an int of how many uppercase characters are needed in a valid password
     *
     * @return int
     */
    public function getReqUpper(): int {
        return $this->reqUpper;
    }

    /**
     * Returns an int of how many lowercase characters are needed in a valid password
     *
     * @return int
     */
    public function getReqLower(): int {
        return $this->reqLower;
    }

    /**
     * Returns an int of lowest length of a password that is valid
     *
     * @return int
     */
    public function getMinLength(): int {
        return $this->minLength;
    }

    /**
     * Returns an int of highest length of a password that is valid
     *
     * @return int
     */
    public function getMaxLength(): int {
        return $this->maxLength;
    }

    /**
     * Returns a string of valid symbols for passwords
     *
     * @return string
     */

    public function getValidSymbols(): string {
        return $this->validSymbols;
    }
    /**
     * Returns a string of valid numbers for passwords
     *
     * @return string
     */
    public function getValidNumbers(): string {
        return $this->validNumbers;
    }

    /**
     * Returns a string of valid uppercase characters for passwords
     *
     * @return string
     */
    public function getValidUpper(): string {
        return $this->validUpper;
    }

    /**
     * Returns a string of valid lowercase characters for passwords
     *
     * @return string
     */
    public function getValidLower(): string {
        return $this->validLower;
    }
}