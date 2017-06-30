<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\UserRepoInterface;
use freeman\jals\interfaces\UserSessionServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserAuthController {

    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var InputValidationServiceInterface $inputValidationService */
    protected $inputValidationService;

    /** @var  UserSessionServiceInterface $userSessionService */
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
     * Logs user in
     *
     * @return ResponseInterface
     */
    public function logIn() {
        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['email'], $params['password'])) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $email = $params['email'];
        $password = $params['password'];
        $userId = $this->userRepo->getUserId($email);

        if (!is_int($userId)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // CHECKS EMAIL FORMAT
        if (!$this->inputValidationService->validateEmail($email)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }
        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // CHECKS ID AND SETS SESSION COOKIE
        if(!$this->userSessionService->logIn($userId)){
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        return $this->response->withStatus(200); //TODO: Reason phrase?
    }

    /**
     * Logs user out
     *
     * @return ResponseInterface
     */
    public function logOut() {
        return $this->userSessionService->logOut() ? $this->response->withStatus(200) : $this->response->withStatus(400); //TODO: Reason phrase?
    }

}