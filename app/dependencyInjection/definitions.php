<?php

declare(strict_types=1);

use freeman\jals\interfaces\EmailValidatorInterface;
use freeman\jals\interfaces\PasswordRulesInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
use freeman\jals\interfaces\UserRepoInterface;
use freeman\jals\interfaces\InputValidationServiceInterface;
use freeman\jals\repositories\UserRepo;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\Response;
use freeman\jals\controllers\UserManipulationController;
use freeman\jals\controllers\UserAuthController;
use freeman\jals\inputValidation\EmailValidator;
use freeman\jals\inputValidation\PasswordRules;
use freeman\jals\inputValidation\PasswordValidator;
use freeman\jals\services\InputValidationService;


return [

    /* INTERFACE BINDING */

    EmailValidatorInterface::class => \DI\get(EmailValidator::class),

    PasswordRulesInterface::class => \DI\get(PasswordRules::class),

    PasswordValidatorInterface::class => \DI\get(PasswordValidator::class),

    UserRepoInterface::class => \DI\get(UserRepo::class),

    ServerRequestInterface::class => \DI\get(ServerRequest::class),

    ResponseInterface::class => \DI\get(Response::class),

    InputValidationServiceInterface::class => \DI\get(InputValidationService::class),

    /* FACTORIES */
    PDO::class => function() {
        $dbConfig = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'databaseConfig.json'));
        $pdo = new PDO($dbConfig->dsn, $dbConfig->user, $dbConfig->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        return $pdo;
    },

    ServerRequest::class => function () {
        return \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
    },

    PasswordRules::class => function () {
        $rules = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'passwordRules.json'));
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

    /* NON-FACTORY OBJECT CREATION */

    UserManipulationController::class => DI\object(UserManipulationController::class),

    UserAuthController::class => DI\object(UserAuthController::class),

    InputValidationService::class => DI\object(InputValidationService::class)

];