<?php
declare(strict_types=1);

namespace freeman\jals\repositories;

use freeman\jals\interfaces\UserRepoInterface;
use PDO;

class UserRepo implements UserRepoInterface {

    /** @var PDO $pdo */
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Registers a user
     *
     * @param string $email User's email
     * @param string $password User's password
     * @return bool True if user created, false otherwise
     */
    public function createUser(string $email, string $password): bool {
        $sql = 'INSERT INTO users (email,password) VALUES (:email,:password)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('email', $email, PDO::PARAM_STR);
        $statement->bindValue('password', $password, PDO::PARAM_STR);
        return $statement->execute();
    }

    public function logIn(string $email, string $password) {

    }

    public function logOut(string $email) {

    }

    public function userExists(string $email): bool {
        $sql = 'SELECT * FROM users WHERE email = :email';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('email', $email, PDO::PARAM_STR);
        return $this->pdo->execute() ? true : false;
    }

    public function changeEmail(string $email, string $newEmail) {

    }

    public function changePassword(string $email, string $newPassword) {

    }
}