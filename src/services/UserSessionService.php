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
        $this->sessionHandler->regenSession();
        return $this->sessionHandler->write('user', $id, 'userId')
        && $this->sessionHandler->write('user', true, 'loggedIn');
    }

    public function logOut(): bool {
        $this->sessionHandler->regenSession();
        return $this->sessionHandler->destroy(); //TODO: Decide between unset and destroy
    }

    public function isLoggedIn(): bool {

        if($this->sessionHandler->read('user', 'loggedIn') !== true){
            return false;
        }
        $this->sessionHandler->regenSession();

        return true;
    }

    public function getUser(): array {
        return $this->sessionHandler->read('user') ?? [];
    }

    public function getUserId() {
        return $this->sessionHandler->read('user', 'id');
    }
}