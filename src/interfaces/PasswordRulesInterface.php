<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;


interface PasswordRulesInterface {
    public function __construct(
        int $reqSymbols,
        int $reqPassNumbers,
        int $reqPassUpper,
        int $reqPassLower,
        int $reqPassMinLength,
        int $reqPassMaxLength,
        string $validPassSymbols,
        string $validPassNumbers,
        string $validPassUpper,
        string $validPassLower
    );
    /**
     * @return int
     */
    public function getReqSymbols(): int;

    /**
     * @return int
     */
    public function getReqNumbers(): int;

    /**
     * @return int
     */
    public function getReqUpper(): int;

    /**
     * @return int
     */
    public function getReqLower(): int;

    /**
     * @return int
     */
    public function getMinLength(): int;

    /**
     * @return int
     */
    public function getMaxLength(): int;

    /**
     * @return string
     */
    public function getValidSymbols(): string;

    /**
     * @return string
     */
    public function getValidNumbers(): string;

    /**
     * @return string
     */
    public function getValidUpper(): string;

    /**
     * @return string
     */
    public function getValidLower(): string;

}