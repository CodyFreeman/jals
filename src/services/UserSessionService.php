<?php
declare(strict_types=1);

namespace freeman\jals\services;

use freeman\jals\interfaces\UserSessionServiceInterface;
use freeman\jals\interfaces\SessionHandlerInterface;

class UserSessionService implements UserSessionServiceInterface {

    protected $sessionHandler;

    public function __construct(SessionHandlerInterface $sessionHandler) {
        $this->sessionHandler = $sessionHandler;
    }

    public function logIn(int $id): bool {
        return $this->sessionHandler->write('user', $id, 'userId')
        && $this->sessionHandler->write('user', 1, 'loggedIn');
    }

    public function logOut(): bool {
        return $this->sessionHandler->destroy(); //TODO: Decide between unset and destroy
    }

    public function isLoggedIn(): bool {
        return $this->sessionHandler->read('user', 'loggedIn') === true;
    }

    public function getUserCookie(): array {
        return $this->sessionHandler->read('user') ?? [];
    }

    public function getUserId() {
        return $this->sessionHandler->read('user', 'id');
    }
}