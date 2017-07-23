<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use freeman\jals\interfaces\UserRepoInterface;
use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\UserSessionServiceInterface;
use freeman\jals\ApiResponseBody\ApiResponseBody;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserManipulationController {
    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var  ApiResponseBody $apiResponseBody */
    protected $apiResponseBody;

    /** @var  InputValidationServiceInterface */
    protected $inputValidationService;

    /** @var UserSessionServiceInterface */
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
     * Validates and registers a new user based on Request's parameters
     */
    public function createUser(): ResponseInterface {

        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['email'], $params['password'])) {
            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        $email = $params['email'];
        $password = $params['password'];

        // CHECKS EMAIL AND PASSWORD CONFORMS TO RULES
        if (!$this->inputValidationService->validateEmail($email) || !$this->inputValidationService->validatePasswordRules($password)) {
            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // CHECKS IF USER ALREADY EXISTS
        if ($this->userRepo->userExists($email)){
            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // HASHES PASSWORD AND CREATES USER
        $password = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->userRepo->createUser($email, $password)) {
            $this->apiResponseBody->addError('Invalid parameters');
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

        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['newEmail'], $params['password'])) {
            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // SETTING NEEDED VARIABLES FROM PARAMETERS
        $newEmail = $params['newEmail'];
        $password = $params['password'];

        // CHECKS EMAIL FORMAT
        if (!$this->inputValidationService->validateEmail($newEmail)) {
            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // GETS USER ID
        $userId = $this->userSessionService->getUserId();

        if (!is_int($userId)) {
            $this->apiResponseBody->addError('Unable to read cookie');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }
        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {
            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));
            return $this->response->withStatus(400);
        }

        // CHANGES EMAIL
        if (!$this->userRepo->changeEmail($userId, $newEmail)) {
            $this->apiResponseBody->addError('Unable to change email');
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

        $params = $this->request->getParsedBody();

        // CHECKS IF NEEDED PARAMETERS ARE SET
        if (!isset($params['email'], $params['newPassword'], $params['password'])) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // SETS NEEDED VARIABLES FROM PARAMETERS
        $password = $params['password'];
        $newPassword = $params['newPassword'];


        // CHECKS NEW PASSWORD FORMAT
        if (!$this->inputValidationService->validatePasswordRules($newPassword)) {

            $this->apiResponseBody->addError('Invalid password format');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        $userId = $this->userSessionService->getUserId();

        if (!is_int($userId)) {

            $this->apiResponseBody->addError('Unable to read cookie');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // VALIDATES PASSWORD IS CORRECT
        if (!password_verify($password, $this->userRepo->getPasswordHash($userId))) {

            $this->apiResponseBody->addError('Invalid parameters');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        // CHANGES PASSWORD
        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$this->userRepo->changePassword($userId, $hash)) {

            $this->apiResponseBody->addError('Unable to change password');
            $this->response->getBody()->write(json_encode($this->apiResponseBody));

            return $this->response->withStatus(400);
        }

        $this->response->getBody()->write(json_encode($this->apiResponseBody));
        return $this->response->withStatus(200);
    }
}