<?php
declare(strict_types=1);

use freeman\jals\controllers\UserManipulationController;
use freeman\jals\controllers\UserAuthController;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector){

    $routeCollector->addRoute('POST', '/users/register', [UserManipulationController::class, 'createUser']);

    $routeCollector->addRoute('PATCH', '/users/changeEmail', [UserManipulationController::class, 'changeEmail']);

    $routeCollector->addRoute('PATCH', '/users/changePassword', [UserManipulationController::class, 'changePassword']);

});