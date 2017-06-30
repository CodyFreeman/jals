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
        $sql = 'INSERT INTO `users` (`email`,`password`) VALUES (:email,:password)';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('email', $email, PDO::PARAM_STR);
        $statement->bindValue('password', $password, PDO::PARAM_STR);
        $statement->execute();
        return $this->userExists($email);
    }

    public function logIn(int $userId, string $password) {

    }

    public function logOut(int $userId) {

    }

    /**
     * Checks if user exists in database based on user's email
     *
     * @param string $email Email to look for
     * @return bool True if email found, false otherwise
     */
    public function userExists(string $email): bool {
        $sql = 'SELECT COUNT(*) FROM `users` WHERE `email` = :email';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('email', $email, PDO::PARAM_STR);
        $statement->execute();
        $result = $statement->fetch();

        return (bool) $result[0] ?? false;
    }

    /**
     * Changes email of user
     *
     * @param int $userId user's id
     * @param string $newEmail user's new email
     * @return bool True if email changed, false otherwise
     */
    public function changeEmail(int $userId, string $newEmail): bool {
        $sql = 'UPDATE `users` SET `email`=:newEmail WHERE `id`=:id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('id', $userId, PDO::PARAM_INT);
        $statement->bindValue('newEmail', $newEmail, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Changes password of user
     *
     * @param int $userId user's id
     * @param string $newPassword user's new password
     * @return bool True if password changed, false otherwise
     */
    public function changePassword(int $userId, string $newPassword): bool {
        $sql = 'UPDATE `users` SET `password`=:newPassword WHERE `id`=:id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('id', $userId, PDO::PARAM_INT);
        $statement->bindValue('newPassword', $newPassword, PDO::PARAM_STR);

        return $statement->execute();
    }

    /**
     * Gets user's password hash
     *
     * @param int $userId user's id
     * @return string Hash of password or empty string if not found
     */
    public function getPasswordHash(int $userId): string {
        $sql = 'SELECT `password` FROM `users` WHERE `id`=:id';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('id', $userId, PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch();

        return $result['password'] ?? '';
    }

    /**
     * Gets user's id.
     *
     * @param string $email Email of user
     * @return int|null Returns user id or null
     */

    public function getUserId(string $email) {
        $sql = 'SELECT `id` FROM `users` WHERE `email`=:email';
        $statement = $this->pdo->prepare($sql);
        $statement->bindValue('email', $email, PDO::PARAM_STR);
        $statement->execute();
        $id = $statement->fetch();

        return $id['id'] ?? null;
    }

}