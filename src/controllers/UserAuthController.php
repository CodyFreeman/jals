<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\UserRepoInterface;
use freeman\jals\interfaces\UserSessionServiceInterface;
use freeman\jals\ApiResponseBody\ApiResponseBody;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserAuthController {

    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var  ApiResponseBody $apiResponseBody */
    protected $apiResponseBody;

    /** @var InputValidationServiceInterface $inputValidationService */
    protected $inputValidationService;

    /** @var  UserSessionServiceInterface $userSessionService */
    protected $userSessionService;

    /** @var UserRepoInterface $userRepo */
    protected $userRepo;


    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        ApiResponseBody $apiResponseBody,
        InputValidationServiceInterface $inputValidationService,
        UserSessionServiceInterface $userSessionService,
        UserRepoInterface $userRepo
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->apiResponseBody = $apiResponseBody;
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

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $email = $params['email'];
        $password = $params['password'];
        $userId = $this->userRepo->getUserId($email);

        if (!is_int($userId)) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHECKS EMAIL FORMAT
        if (!$this->inputValidationService->validateEmail($email)) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }
        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHECKS ID AND SETS SESSION COOKIE
        if(!$this->userSessionService->logIn($userId)){

            $this->apiResponseBody->addError('Unable to set cookie');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        $this->response->getBody()->write(json_encode($this->apiResponseBody));
        return $this->response->withStatus(200);
    }

    /**
     * Logs user out
     *
     * @return ResponseInterface
     */
    public function logOut() {
        $this->response->getBody()->write(json_encode($this->apiResponseBody));
        return $this->userSessionService->logOut() ? $this->response->withStatus(200) : $this->response->withStatus(400); //TODO: Reason phrase?
    }

}