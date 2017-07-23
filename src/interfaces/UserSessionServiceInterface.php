<?php
declare(strict_types=1);


namespace freeman\jals\interfaces;


interface UserSessionServiceInterface {

    public function logIn(int $id): void;

    public function logOut(): void;

    public function isLoggedIn(): bool;

    public function getUser(): array;

    public function getUserId();
}