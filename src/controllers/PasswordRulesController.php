<?php
declare(strict_types=1);


namespace freeman\jals\controllers;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use freeman\jals\ApiResponseBody\ApiResponseBody;

class PasswordRulesController {

    /** @var ServerRequestInterface $request */
    protected $request;

    /** @var ResponseInterface $response */
    protected $response;

    /** @var  ApiResponseBody $apiResponseBody */
    protected $apiResponseBody;

    public function __construct(
        ServerRequestInterface $request,
        ResponseInterface $response,
        ApiResponseBody $apiResponseBody
    ) {
        $this->request = $request;
        $this->response = $response;
        $this->apiResponseBody = $apiResponseBody;
    }

    /**
     * Returns password validation rules
     *
     * @return ResponseInterface
     */
    public function getPasswordRules(): ResponseInterface {

        $this->apiResponseBody->addData('passwordRules',json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'passwordRules.json')));
        $this->response->getBody()->write(json_encode($this->apiResponseBody));

        return $this->response->withStatus(200);
    }

}