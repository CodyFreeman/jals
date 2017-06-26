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
        $params = $this->request->getQueryParams();
        // CHECKS IF NEEDED QUERY PARAMETERS ARE SET
        if (!isset($params['email'], $params['password'])) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $email = $params['email'];
        $password = $params['password'];

        // CHECKS EMAIL FORMAT
        if (!$this->inputValidationService->validateEmail($email)) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }
        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($email))) {
            return $this->response->withStatus(400); //TODO: Reason phrase?
        }

        // GETS USER ID
        $id = $this->userRepo->getUserId($email);

        // CHECKS ID AND SETS SESSION COOKIE
        if(!$id || $this->userSessionService->logIn($id['id'])){
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