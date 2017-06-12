<?php
declare(strict_types=1);

use freeman\jals\controllers\RegisterUserController;

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $routeCollector){
    $routeCollector->addRoute('GET', '/users/register', [RegisterUserController::class, 'registerUser']);
});