<?php

declare(strict_types=1);

namespace freeman\jals\inputValidation;

use freeman\jals\interfaces\PasswordRulesInterface;
use Exception;

class PasswordRules implements PasswordRulesInterface {

    protected $reqPassSymbols;
    protected $reqPassNumbers;
    protected $reqPassUpper;
    protected $reqPassLower;
    protected $reqPassMinLength;
    protected $reqPassMaxLength;
    protected $validPassSymbols;
    protected $validPassNumbers;
    protected $validPassUpper;
    protected $validPassLower;

    public function __construct(
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
    ) {
        $this->reqPassSymbols = $reqPassSymbols;
        $this->reqPassNumbers = $reqPassNumbers;
        $this->reqPassUpper = $reqPassUpper;
        $this->reqPassLower = $reqPassLower;
        $this->reqPassMinLength = $reqPassMinLength;
        $this->reqPassMaxLength = $reqPassMaxLength;
        $this->validPassSymbols = $validPassSymbols;
        $this->validPassNumbers = $validPassNumbers;
        $this->validPassUpper = $validPassUpper;
        $this->validPassLower = $validPassLower;
    }

    //TODO: consider moving validation of rules to separate class
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
        //TODO: Write better error messages
        if(min($reqPassSymbols, $reqPassNumbers, $reqPassUpper, $reqPassLower) < 0){
            throw new Exception('Error in supplied password rules');
        }

        if($reqPassMinLength > $reqPassMaxLength || min($reqPassMinLength, $reqPassMaxLength) < 1){
            throw new Exception('Error in supplied password rules');
        }

        $totalMinChars = $reqPassSymbols + $reqPassNumbers + $reqPassUpper + $reqPassLower;

        if($totalMinChars < $reqPassMaxLength){
            throw new Exception('Error in supplied password rules');
        }

        if($this->validateRule($reqPassSymbols, $validPassSymbols)){
            throw new Exception('Error in supplied password rules');
        }

        if($this->validateRule($reqPassNumbers, $validPassNumbers)){
            throw new Exception('Error in supplied password rules');
        }

        if($this->validateRule($reqPassUpper, $validPassUpper)){
            throw new Exception('Error in supplied password rules');
        }

        if($this->validateRule($reqPassLower, $validPassLower)){
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
        //TODO: Make up a better names for this function!
        return $requirement > 0 && ! empty($validCharacters) || $requirement === 0;
    }

    /**
     * Returns an int of how many symbol characters are needed in a valid password
     *
     * @return int
     */
    public function getReqPassSymbols(): int {
        return $this->reqPassSymbols;
    }

    /**
    * Returns an int of how many numbers are needed in a valid password
     *
     * @return int
     */
    public function getReqPassNumbers(): int {
        return $this->reqPassNumbers;
    }

    /**
     * Returns an int of how many uppercase characters are needed in a valid password
     *
     * @return int
     */
    public function getReqPassUpper(): int {
        return $this->reqPassUpper;
    }

    /**
     * Returns an int of how many lowercase characters are needed in a valid password
     *
     * @return int
     */
    public function getReqPassLower(): int {
        return $this->reqPassLower;
    }

    /**
     * Returns an int of lowest length of a password that is valid
     *
     * @return int
     */
    public function getReqPassMinLength(): int {
        return $this->reqPassMinLength;
    }

    /**
     * Returns an int of highest length of a password that is valid
     *
     * @return int
     */
    public function getReqPassMaxLength(): int {
        return $this->reqPassMaxLength;
    }

    /**
     * Returns a string of valid symbols for passwords
     *
     * @return string
     */

    public function getValidPassSymbols(): string {
        return $this->validPassSymbols;
    }
    /**
     * Returns a string of valid numbers for passwords
     *
     * @return string
     */
    public function getValidPassNumbers(): string {
        return $this->validPassNumbers;
    }

    /**
     * Returns a string of valid uppercase characters for passwords
     *
     * @return string
     */
    public function getValidPassUpper(): string {
        return $this->validPassUpper;
    }

    /**
     * Returns a string of valid lowercase characters for passwords
     *
     * @return string
     */
    public function getValidPassLower(): string {
        return $this->validPassLower;
    }
}