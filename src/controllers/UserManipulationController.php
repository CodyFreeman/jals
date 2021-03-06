<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use freeman\jals\interfaces\UserRepoInterface;
use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\UserSessionServiceInterface;
use freeman\jals\ApiResponseBody\ApiResponseBody;
use freeman\jals\interfaces\TokenHandlerServiceInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserManipulationController {
    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var  ApiResponseBody $apiResponseBody */
    protected $apiResponseBody;

    /** @var  InputValidationServiceInterface $inputValidationService */
    protected $inputValidationService;

    /** @var UserSessionServiceInterface $userSessionService */
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
     * Validates and registers a new user based on Request's parameters
     *
     * @return ResponseInterface
     */
    public function createUser(): ResponseInterface {

        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['email'], $params['password'], $params['token'])) {
            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        $email = $params['email'];
        $password = $params['password'];
        $token = $params['token'];

        // CHECKS TOKEN IS VALID
        if(!$this->tokenHandlerService->validateToken($token)){

            $this->apiResponseBody->addError('Invalid token');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHECKS EMAIL AND PASSWORD CONFORMS TO RULES
        if (!$this->inputValidationService->validateEmail($email) || !$this->inputValidationService->validatePasswordRules($password)) {
            $this->apiResponseBody->addError('Invalid password or email');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // CHECKS IF USER ALREADY EXISTS
        if ($this->userRepo->userExists($email)){
            $this->apiResponseBody->addError('User already exists');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // HASHES PASSWORD AND CREATES USER
        $password = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->userRepo->createUser($email, $password)) {

            $this->apiResponseBody->addError('Creation failed');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        $this->response->getBody()->write(json_encode($this->apiResponseBody));
        return $this->response->withStatus(201);
    }

    /**
     * Changes the email of a user
     *
     * @return ResponseInterface
     */
    public function changeEmail(): ResponseInterface {

        // MANUAL SUBSTITUTE FOR PARSED BODY NOT WORKING WITH PATCH METHOD
        $body = explode('&', $this->request->getBody()->getContents());
        $params = [];
        foreach($body as $param){
            $e = explode('=', $param);
            $params[urldecode($e[0])] = urldecode($e[1] ?? "");
        }

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['newEmail'], $params['password'], $params['token'])) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $newEmail = $params['newEmail'];
        $password = $params['password'];
        $token = $params['token'];

        // CHECKS TOKEN IS VALID
        if(!$this->tokenHandlerService->validateToken($token)){

            $this->apiResponseBody->addError('Invalid token');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHECKS EMAIL FORMAT
        if (!$this->inputValidationService->validateEmail($newEmail)) {
            $this->apiResponseBody->addError('Invalid email');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // GETS USER ID
        $userId = $this->userSessionService->getUserId();

        if (!is_int($userId)) {
            $this->apiResponseBody->addError('Invalid cookie');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {
            $this->apiResponseBody->addError('Invalid password');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // CHANGES EMAIL
        if (!$this->userRepo->changeEmail($userId, $newEmail)) {
            $this->apiResponseBody->addError('Email change failed');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        $this->response->getBody()->write(json_encode($this->apiResponseBody));
        return $this->response->withStatus(200);
    }

    /**
     * Changes the password of a user
     *
     * @return ResponseInterface
     */
    public function changePassword(): ResponseInterface {

        // MANUAL SUBSTITUTE FOR PARSED BODY NOT WORKING WITH PATCH METHOD
        $body = explode('&', $this->request->getBody()->getContents());
        $params = [];
        foreach($body as $param){
            $e = explode('=', $param);
            $params[urldecode($e[0])] = urldecode($e[1] ?? "");
        }

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['newPassword'], $params['password'], $params['token'])) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // SETS NEEDED VARIABLES FROM PARAMETERS
        $password = $params['password'];
        $newPassword = $params['newPassword'];
        $token = $params['token'];

        // CHECKS TOKEN IS VALID
        if(!$this->tokenHandlerService->validateToken($token)){

            $this->apiResponseBody->addError('Invalid token');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHECKS NEW PASSWORD FORMAT
        if (!$this->inputValidationService->validatePasswordRules($newPassword)) {

            $this->apiResponseBody->addError('Invalid new password');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        $userId = $this->userSessionService->getUserId();

        if (!is_int($userId)) {

            $this->apiResponseBody->addError('Invalid cookie');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {

            $this->apiResponseBody->addError('Invalid password');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHANGES PASSWORD
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->userRepo->changePassword($userId, $hash)) {

            $this->apiResponseBody->addError('Password change failed');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        $this->response->getBody()->write(json_encode($this->apiResponseBody));
        return $this->response->withStatus(200);
    }
}