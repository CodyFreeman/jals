<?php
declare(strict_types=1);

use freeman\jals\interfaces\EmailValidatorInterface;
use freeman\jals\interfaces\PasswordRulesInterface;
use freeman\jals\interfaces\PasswordValidatorInterface;
use Zend\Diactoros\ServerRequest;
use freeman\jals\inputValidation\EmailValidator;
use freeman\jals\inputValidation\PasswordRules;
use freeman\jals\inputValidation\PasswordValidator;
use freeman\jals\controllers\RegisterUserController;


return [
    EmailValidatorInterface::class => \DI\get(EmailValidator::class),

    PasswordRulesInterface::class => \DI\get(PasswordRules::class),

    PasswordValidatorInterface::class => \DI\get(PasswordValidator::class),

    \Psr\Http\Message\ServerRequestInterface::class => \DI\get(ServerRequest::class),

    \Psr\Http\Message\ResponseInterface::class => \DI\get(\Zend\Diactoros\Response::class),

    PasswordRules::class =>
        function (\DI\Container $container) {
            $rules = json_decode(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR .'passwordRules.json'));
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

    RegisterUserController::class => DI\object(\freeman\jals\controllers\RegisterUserController::class),

];