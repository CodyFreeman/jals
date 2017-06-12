<?php


namespace freeman\jals\interfaces;


interface RegisterUserRepoInterface {
    //TODO: change to use user object
    public function registerUser(string $email, string $password);
}