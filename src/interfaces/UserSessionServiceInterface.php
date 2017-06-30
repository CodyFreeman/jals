<?php
declare(strict_types=1);


namespace freeman\jals\interfaces;


interface UserSessionServiceInterface {

    public function logIn(int $id): bool;

    public function logOut(): bool;

    public function isLoggedIn(): bool;

    public function getUserCookie(): array;

    public function getUserId();
}