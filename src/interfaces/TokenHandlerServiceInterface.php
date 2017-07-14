<?php
declare(strict_types=1);

namespace freeman\jals\interfaces;

interface TokenHandlerServiceInterface {
    /**
     * Generates cryptographically secure token and sets it with timestamp in session cookie
     *
     * @return string Generated token
     */
    public function setToken(): string;

    /**
     * Validates token supplied against token in session cookie
     *
     * @param string $token
     * @return bool True if valid, false if invalid
     */
    public function validateToken(string $token): bool;
}