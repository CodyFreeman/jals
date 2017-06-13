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
     * Registers a new user
     */
    public function createUser(): ResponseInterface {

        if (!isset($this->request->getQueryParams()['email'], $this->request->getQueryParams()['password'])) {
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        $email = $this->request->getQueryParams()['email'];
        $password = $this->request->getQueryParams()['password'];

        if (!$this->validateInput($email, $password)) {
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        if (!$this->userRepo->createUser($email, $password)) {
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        $this->response = $this->response->withStatus(201);

        return $this->response;
    }

    protected function validateInput(string $email, string $password): bool {
        return
            $this->emailValidator->validateEmailFormat($email)
            && $this->passwordValidator->validatePassword($password);
    }
}