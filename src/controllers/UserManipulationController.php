<?php
declare(strict_types=1);


namespace freeman\jals\controllers;

use freeman\jals\interfaces\EmailValidatorInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
use freeman\jals\interfaces\UserRepoInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserManipulationController {
    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var EmailValidatorInterface $emailValidator */
    protected $emailValidator;

    /** @var PasswordValidatorInterface $passwordValidator */
    protected $passwordValidator;

    /** @var UserRepoInterface $userRepo */
    protected $userRepo;


    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        EmailValidatorInterface $emailValidator,
        PasswordValidatorInterface $passwordValidator,
        UserRepoInterface $userRepo
    ) {
        $this->request = $request;
        $this->response = $response->withHeader('Content-Type', 'application/json');
        $this->emailValidator = $emailValidator;
        $this->passwordValidator = $passwordValidator;
        $this->userRepo = $userRepo;
    }

    /**
     * Validates and registers a new user based on request's parameters
     */
    public function createUser(): ResponseInterface {

        if (!isset($this->request->getQueryParams()['email'], $this->request->getQueryParams()['password'])) {
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        $email = $this->request->getQueryParams()['email'];
        $password = $this->request->getQueryParams()['password'];

        if (!$this->emailValidator->validateEmailFormat($email) && $this->passwordValidator->validatePassword($password)) {
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        if ($this->userRepo->userExists($email)){
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        $password = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->userRepo->createUser($email, $password)) {
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        return $this->response->withStatus(201);
    }

    /**
     * Changes the email of a user
     *
     * @return ResponseInterface
     */
    public function changeEmail(): ResponseInterface {
        //TODO: ALL THIS COULD BE FUNCTIONS! I'M FEELING WET!
        $params = $this->request->getQueryParams();
        // CHECKS IF NEEDED QUERY PARAMETERS ARE SET
        if (!isset($params['email'], $params['newEmail'], $params['password'])) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $email = $params['email'];
        $newEmail = $params['newEmail'];
        $password = $params['password'];

        // CHECKS EMAIL FORMAT
        if (!$this->emailValidator->validateEmailFormat($email) || !$this->emailValidator->validateEmailFormat($newEmail)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($email))) {
            return $this->response->withStatus(400); //TODO: Reason phrase? Maybe use fobidden?
        }

        // CHANGE EMAIL
        if (!$this->userRepo->changeEmail($email, $newEmail)) {
            return $this->response->withStatus(400); //TODO: Reason phrase? Maybe use fobidden?
        }

        return $this->response->withStatus(200); //TODO: Reason phrase?
    }

    /**
     * Changes the password of a user
     *
     * @return ResponseInterface
     */
    public function changePassword(): ResponseInterface {
        //TODO: ALL THIS COULD BE FUNCTIONS! I'M FEELING WET!
        $params = $this->request->getQueryParams();

        if (!isset($params['email'], $params['newPassword'], $params['password'])) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // SETS NEEDED VARIABLES FROM PARAMETERS
        $email = $params['email'];
        $password = $params['password'];
        $newPassword = $params['newPassword'];

        // CHECKS EMAIL FORMAT
        if (!$this->emailValidator->validateEmailFormat($email) || !$this->emailValidator->validateEmailFormat($email)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // CHECKS PASSWORD FORMAT
        if (!$this->passwordValidator->validatePassword($newPassword)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($email))) {
            return $this->response->withStatus(400); //TODO: Reason phrase? Maybe use fobidden?
        }

        // CHANGES PASSWORD
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->userRepo->changePassword($email, $hash)) {
            return $this->response->withStatus(400); //TODO: Reason phrase? Maybe use fobidden?
        }

        return $this->response->withStatus(200); //TODO: Reason phrase?
    }
}