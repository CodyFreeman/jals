<?php
declare(strict_types=1);

namespace freeman\jals\services;

use freeman\jals\interfaces\SessionHandlerInterface;
use freeman\jals\interfaces\TokenHandlerServiceInterface;

class TokenHandlerService implements TokenHandlerServiceInterface {

    /** @var SessionHandlerInterface $sessionHandler */
    protected $sessionHandler;

    public function __construct(SessionHandlerInterface $sessionHandler) {
        $this->sessionHandler = $sessionHandler;
    }

    /**
     * Generates cryptographically secure token and sets it with timestamp in session cookie
     *
     * @return string Generated token
     */
    public function setToken(): string {

        $token = bin2hex(random_bytes(16));
        $this->sessionHandler->write('token', $token);
        $this->sessionHandler->write('tokenTimestamp', time());

        return $token;
    }

    /**
     * Validates token supplied against token in session cookie
     *
     * @param string $token
     * @return bool True if valid, false if invalid
     */
    public function validateToken(string $token): bool{

        $sessionToken = $this->sessionHandler->read('token');
        $timeStamp = $this->sessionHandler->read('tokenTimestamp');

        if (!isset($sessionToken) || $sessionToken !== $token || $timeStamp + 300 < time()) { // TODO: MOVE TIME VALID TO CONFIG
            return false;
        }

        return true;
    }

}