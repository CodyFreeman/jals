<?php
declare(strict_types=1);

use freeman\jals\controllers\UserManipulationController;
use freeman\jals\controllers\UserAuthController;
use freeman\jals\controllers\TokenController;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector){

    $routeCollector->addRoute('POST', '/users/register', [UserManipulationController::class, 'createUser']);

    $routeCollector->addRoute('PATCH', '/users/changeemail', [UserManipulationController::class, 'changeEmail']);

    $routeCollector->addRoute('PATCH', '/users/changepassword', [UserManipulationController::class, 'changePassword']);

    $routeCollector->addRoute('POST', '/users/login', [UserAuthController::class, 'logIn']);

    $routeCollector->addRoute('POST', '/users/logout', [UserAuthController::class, 'logOut']);

    $routeCollector->addRoute('GET', '/gettoken', [TokenController::class, 'getToken']);
});