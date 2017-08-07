<?php
declare(strict_types=1);

namespace freeman\jals\inputValidation;

use freeman\jals\interfaces\PasswordRulesInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;

class PasswordValidator implements PasswordValidatorInterface {

    /** @var PasswordRulesInterface */
    protected $rules;

    public function __construct(PasswordRulesInterface $rules) {
        $this->rules = $rules;
    }

    /**
     * Validates that $password conforms to configured rules
     *
     * @param string $password
     * @return bool True if password is between min and max, false otherwise
     */
    public function validatePassword(string $password): bool {
        return $this->validateLengthRules($password) && $this->validateCharacterRequirements($password);
    }

    /**
     * Validates length of $password conforms to max and min rules configured
     *
     * @param string $password
     * @return bool True if password is at or between min and max requirements
     */
    protected function validateLengthRules(string $password):bool {
       return strlen($password) >= $this->rules->getMinLength() && strlen($password) < $this->rules->getMaxLength();
    }

    /**
     * Validates that $password conforms to supplied rules governing valid characters and minimum requirements
     *
     * @param $password
     * @return bool True if password conforms to all character requirements
     */
    protected function validateCharacterRequirements($password):bool {
        return
            $this->validateCharacterRule($password, $this->rules->getReqSymbols(), $this->rules->getValidSymbols())
            && $this->validateCharacterRule($password, $this->rules->getReqNumbers(), $this->rules->getValidNumbers())
            && $this->validateCharacterRule($password, $this->rules->getReqUpper(), $this->rules->getValidUpper())
            && $this->validateCharacterRule($password, $this->rules->getReqLower(), $this->rules->getValidLower())
            && $this->validateCharacterRule($password, strlen($password),
                $this->rules->getValidLower() . $this->rules->getValidUpper() . $this->rules->getValidNumbers() . $this->rules->getValidSymbols());
    }

    /**
     * Validates that the supplied $password contains the required amount of allowed characters
     *
     * @param string $password The string to be searched
     * @param int $requirementCount The minimum amount of times an allowed character should occur
     * @param string $allowedCharacters The allowed characters
     * @return bool True if $password conforms to rules, false otherwise
     */
    protected function validateCharacterRule(string $password, int $requirementCount, string $allowedCharacters):bool {
        return $requirementCount === 0 || (int) preg_match_all('/[' . preg_quote($allowedCharacters) . ']/', $password) >= $requirementCount;
    }
}