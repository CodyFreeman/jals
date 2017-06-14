<?php
declare(strict_types=1);


namespace freeman\jals\controllers;

use freeman\jals\interfaces\EmailValidatorInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
use freeman\jals\interfaces\UserRepoInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CreateUserController {
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
}