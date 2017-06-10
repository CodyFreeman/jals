<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;


interface PasswordRulesInterface {
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
    );
    /**
     * @return int
     */
    public function getReqPassSymbols(): int;

    /**
     * @return int
     */
    public function getReqPassNumbers(): int;

    /**
     * @return int
     */
    public function getReqPassUpper(): int;

    /**
     * @return int
     */
    public function getReqPassLower(): int;

    /**
     * @return int
     */
    public function getReqPassMinLength(): int;

    /**
     * @return int
     */
    public function getReqPassMaxLength(): int;

    /**
     * @return string
     */
    public function getValidPassSymbols(): string;

    /**
     * @return string
     */
    public function getValidPassNumbers(): string;

    /**
     * @return string
     */
    public function getValidPassUpper(): string;

    /**
     * @return string
     */
    public function getValidPassLower(): string;

}