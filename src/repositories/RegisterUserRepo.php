<?php
declare(strict_types=1);

namespace freeman\jals\repositories;

use freeman\jals\interfaces\RegisterUserRepoInterface;
use PDO;

class RegisterUserRepo implements RegisterUserRepoInterface {

    /** @var PDO $pdo */
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
        echo 'db';
    }

    /**
     * Registers a user
     *
     * @param string $email User's email
     * @param string $password User's password
     * @return bool True if user created, false otherwise
     */
    //TODO: change to use user object?
    public function registerUser(string $email, string $password): bool {
        //TODO: Persistence
    }
}