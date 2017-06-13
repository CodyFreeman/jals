<?php

declare(strict_types=1);

namespace freeman\jals\interfaces;

interface UserRepoInterface {

    public function logIn(string $email, string $password);

    public function logOut(string $email);

    public function createUser(string $email, string $password);

    public function changeEmail(string $email, string $newEmail);

    public function changePassword(string $email, string $newPassword);
}