<?php
declare(strict_types=1);

namespace freeman\jals\controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use freeman\jals\ApiResponseBody\ApiResponseBody;
use freeman\jals\services\TokenHandlerService;

class TokenController {

    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var  TokenHandlerService $tokenHandlerService */
    protected $tokenHandlerService;

    /** @var  ApiResponseBody $apiResponseBody */
    protected $apiResponseBody;

    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        TokenHandlerService $tokenHandlerService,
        ApiResponseBody $apiResponseBody
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->tokenHandlerService = $tokenHandlerService;
        $this->apiResponseBody = $apiResponseBody;
    }

    /**
     * Generates and sets a token
     *
     * @return ResponseInterface
     */
    public function getToken(): ResponseInterface {

        $token = $this->tokenHandlerService->setToken();
        $this->apiResponseBody->addData('token', $token);
        $this->response->getBody()->write(json_encode($this->apiResponseBody));

        return $this->response->withStatus(200);
    }

}