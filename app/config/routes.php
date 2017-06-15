<?php
declare(strict_types=1);

use freeman\jals\controllers\CreateUserController;
use freeman\jals\controllers\UserController;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector){

    $routeCollector->addRoute('POST', '/users/register', [CreateUserController::class, 'createUser']);

    $routeCollector->addRoute('PATCH', '/users/changeEmail', [UserController::class, 'changeEmail']);

    $routeCollector->addRoute('PATCH', '/users/changePassword', [UserController::class, 'changePassword']);

});