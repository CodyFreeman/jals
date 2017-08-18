<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\TokenHandlerServiceInterface;
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

    /** @var TokenHandlerServiceInterface $tokenHandlerService */
    protected $tokenHandlerService;

    /** @var UserRepoInterface $userRepo */
    protected $userRepo;


    public function __construct(

        ServerRequestInterface $request,
        ResponseInterface $response,
        ApiResponseBody $apiResponseBody,
        InputValidationServiceInterface $inputValidationService,
        UserSessionServiceInterface $userSessionService,
        TokenHandlerServiceInterface $tokenHandlerService,
        UserRepoInterface $userRepo
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->apiResponseBody = $apiResponseBody;
        $this->inputValidationService = $inputValidationService;
        $this->userSessionService = $userSessionService;
        $this->tokenHandlerService = $tokenHandlerService;
        $this->userRepo = $userRepo;
    }

    /**
     * Logs user in
     *
     * @return ResponseInterface
     */
    public function logIn(): ResponseInterface {

        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['email'], $params['password'], $params['token'])) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $email = $params['email'];
        $password = $params['password'];
        $token = $params['token'];

        // CHECKS TOKEN IS VALID
        if (!$this->tokenHandlerService->validateToken($token)) {

            $this->apiResponseBody->addError('Invalid token');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHECKS EMAIL FORMAT
        if (!$this->inputValidationService->validateEmail($email)) {

            $this->apiResponseBody->addError('Invalid email password combination');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // SETS AND CHECKS USERID
        $userId = $this->userRepo->getUserId($email);

        if (!is_int($userId)) {

            $this->apiResponseBody->addError('User not found');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {

            $this->apiResponseBody->addError('Invalid email password combination');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // SETS SESSION COOKIE
        $this->userSessionService->logIn($userId);

        $this->response->getBody()->write(json_encode($this->apiResponseBody));

        return $this->response->withStatus(200);
    }

    /**
     * Checks if user is logged in
     *
     * @return ResponseInterface
     */
    public function isLoggedIn(): ResponseInterface {

        $this->apiResponseBody->addData('loggedIn', $this->userSessionService->isLoggedIn());
        $this->response->getBody()->write(json_encode($this->apiResponseBody));

        return $this->response->withStatus(200);

    }

    /**
     * Logs user out
     *
     * @return ResponseInterface
     */
    public function logOut(): ResponseInterface {

        $this->userSessionService->logOut();
        $this->response->getBody()->write(json_encode($this->apiResponseBody));

        return $this->response->withStatus(200);
    }

}