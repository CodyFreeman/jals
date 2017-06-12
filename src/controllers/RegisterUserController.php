<?php
declare(strict_types=1);


namespace freeman\jals\controllers;

use freeman\jals\interfaces\EmailValidatorInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
use freeman\jals\interfaces\RegisterUserRepoInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class RegisterUserController {
    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var EmailValidatorInterface $emailValidator */
    protected $emailValidator;

    /** @var PasswordValidatorInterface $passwordValidator */
    protected $passwordValidator;

    /** @var RegisterUserRepoInterface $registerUserRepo */
    protected $registerUserRepo;


    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        EmailValidatorInterface $emailValidator,
        PasswordValidatorInterface $passwordValidator,
        RegisterUserRepoInterface $registerUserRepo
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->emailValidator = $emailValidator;
        $this->passwordValidator = $passwordValidator;
        $this->registerUserRepo = $registerUserRepo;
    }

    /**
     * Registers a new user
     */
    public function registerUser(): ResponseInterface {

        return $this->response;
    }

    protected function validateInput(string $email, string $password): bool {
        return
            $this->emailValidator->validateEmailFormat($email)
            && $this->passwordValidator->validatePassword($password);
    }
}