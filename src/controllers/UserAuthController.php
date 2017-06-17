<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\interfaces\UserRepoInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UserAuthController {

    //NTS: Consider moving methods modifying user to UserManipulationController and rename it ManipulateUserController

    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var InputValidationServiceInterface $inputValidationService */
    protected $inputValidationService;

    /** @var UserRepoInterface $userRepo */
    protected $userRepo;


    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        InputValidationServiceInterface $inputValidationService,
        UserRepoInterface $userRepo
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->inputValidationService = $inputValidationService;
        $this->userRepo = $userRepo;
    }

    public function logIn() {

    }

    public function logOut() {

    }

}