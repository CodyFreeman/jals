<?php
declare(strict_types=1);


namespace freeman\jals\controllers;


use freeman\jals\interfaces\EmailValidatorInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
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


    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        EmailValidatorInterface $emailValidator,
        PasswordValidatorInterface $passwordValidator
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->emailValidator = $emailValidator;
        $this->passwordValidator = $passwordValidator;
    }

    /**
     * Registers a new user
     * @param string $email
     * @param string $password
     * @return mixed
     */
    public function registerUser(string $email, string $password) {
        echo 'reached';
        if (!$this->validateInput($email, $password)) {
            return false;
        }
        return true;
    }

    protected function validateInput(string $email, string $password): bool {
        return
            $this->emailValidator->validateEmailFormat($email)
            && $this->passwordValidator->validatePassword($password);
    }
}