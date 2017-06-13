<?php
declare(strict_types=1);

use freeman\jals\controllers\CreateUserController;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector){
    $routeCollector->addRoute('POST', '/users/register', [CreateUserController::class, 'createUser']);
});