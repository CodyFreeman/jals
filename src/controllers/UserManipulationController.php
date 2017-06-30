<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use freeman\jals\interfaces\UserRepoInterface;
use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\UserSessionServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserManipulationController {
    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var  InputValidationServiceInterface */
    protected $inputValidationService;

    /** @var UserSessionServiceInterface */
    protected $userSessionService;

    /** @var UserRepoInterface $userRepo */
    protected $userRepo;


    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        InputValidationServiceInterface $inputValidationService,
        UserSessionServiceInterface $userSessionService,
        UserRepoInterface $userRepo

    ) {
        $this->request = $request;
        $this->response = $response;
        $this->inputValidationService = $inputValidationService;
        $this->userSessionService = $userSessionService;
        $this->userRepo = $userRepo;
    }

    /**
     * Validates and registers a new user based on Request's parameters
     */
    public function createUser(): ResponseInterface {

        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['email'], $params['password'])) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        $email = $params['email'];
        $password = $params['password'];

        // CHECKS EMAIL AND PASSWORD CONFORMS TO RULES
        if (!$this->inputValidationService->validateEmail($email) || !$this->inputValidationService->validatePasswordRules($password)) {
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        // CHECKS IF USER ALREADY EXISTS
        if ($this->userRepo->userExists($email)){
            return $this->response->withStatus(400); //TODO: reason phrase?
        }

        // HASHES PASSWORD AND CREATES USER
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
        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['newEmail'], $params['password'])) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $newEmail = $params['newEmail'];
        $password = $params['password'];
        $userId = $this->userSessionService->getUserId();

        if (!is_int($userId)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // CHECKS EMAIL FORMAT
        if (!$this->inputValidationService->validateEmail($newEmail)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {
            return $this->response->withStatus(400); //TODO: Reason phrase? Maybe use fobidden?
        }

        // CHANGES EMAIL
        if (!$this->userRepo->changeEmail($userId, $newEmail)) {
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
        $params = $this->request->getParsedBody();

        if (!isset($params['email'], $params['newPassword'], $params['password'])) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // SETS NEEDED VARIABLES FROM PARAMETERS
        $password = $params['password'];
        $newPassword = $params['newPassword'];
        $userId = $this->userSessionService->getUserId();

        if (!is_int($userId)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // CHECKS NEW PASSWORD FORMAT
        if (!$this->inputValidationService->validatePasswordRules($newPassword)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {
            return $this->response->withStatus(400); //TODO: Reason phrase? Maybe use fobidden?
        }

        // CHANGES PASSWORD
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->userRepo->changePassword($userId, $hash)) {
            return $this->response->withStatus(400); //TODO: Reason phrase? Maybe use fobidden?
        }

        return $this->response->withStatus(200); //TODO: Reason phrase?
    }
}