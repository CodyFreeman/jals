<?php
declare(strict_types=1);

use freeman\jals\interfaces\EmailValidatorInterface;
use freeman\jals\interfaces\PasswordRulesInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
use freeman\jals\interfaces\RegisterUserRepoInterface;
use freeman\jals\repositories\RegisterUserRepo;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use freeman\jals\controllers\RegisterUserController;
use freeman\jals\inputValidation\EmailValidator;
use freeman\jals\inputValidation\PasswordRules;
use freeman\jals\inputValidation\PasswordValidator;


return [

    /* INTERFACE BINDING */

    EmailValidatorInterface::class => \DI\get(EmailValidator::class),

    PasswordRulesInterface::class => \DI\get(PasswordRules::class),

    PasswordValidatorInterface::class => \DI\get(PasswordValidator::class),

    RegisterUserRepoInterface::class => \DI\get(RegisterUserRepo::class),

    ServerRequestInterface::class => \DI\get(ServerRequest::class),

    ResponseInterface::class => \DI\get(Response::class),

    /* DEFINITIONS */
    PDO::class => function() {
        $dbConfig = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'databaseConfig.json'));
        var_dump($dbConfig);
        return new PDO($dbConfig->dsn, $dbConfig->user, $dbConfig->password, $dbConfig->driverOptions);
    },

    ServerRequest::class => function () {
        return \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    },

    PasswordRules::class => function () {
        $rules = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'passwordRules.json'));
        return new PasswordRules(
            $rules->passwordRequirements->minSymbols,
            $rules->passwordRequirements->minNumbers,
            $rules->passwordRequirements->minUppercase,
            $rules->passwordRequirements->minLowercase,
            $rules->passwordRequirements->minCharacters,
            $rules->passwordRequirements->maxCharacters,
            $rules->passwordComponents->validSymbols,
            $rules->passwordComponents->validNumbers,
            $rules->passwordComponents->validUppercase,
            $rules->passwordComponents->validLowercase
        );
    },

    RegisterUserController::class => DI\object(RegisterUserController::class)

];