<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;

interface UserRepoInterface {

    public function userExists(string $email):bool;

    public function createUser(string $email, string $password): bool;

    public function changeEmail(int $userId, string $newEmail): bool;

    public function changePassword(int $userId, string $newPassword): bool;

    public function getPasswordHash(int $userId): string;

    public function getUserId(string $email);
}