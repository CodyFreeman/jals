<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;

interface UserRepoInterface {

    public function userExists(string $email):bool;

    public function logIn(string $email, string $password);

    public function logOut(string $email);

    public function createUser(string $email, string $password): bool;

    public function changeEmail(string $email, string $newEmail): bool;

    public function changePassword(string $email, string $newPassword): bool;

    public function getPasswordHash(string $email): string;

    public function getUserId(string $email);
}