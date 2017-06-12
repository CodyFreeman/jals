<?php
declare(strict_types=1);


require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// CREATING REQUEST AND RESPONSE OBJECTS
$request = \Zend\Diactoros\ServerRequestFactory::fromGlobals($_SERVER, $_GET, $_POST, $_COOKIE, $_FILES);
$response = new \Zend\Diactoros\Response();

// CREATING CONTAINER
$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dependencyInjection.php');
$container = $containerBuilder->build();

// REQUIRING AND SETTING UP ROUTER
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());
var_dump($routeInfo); //TODO: REMOVE

// ROUTING TO DISPATCHER
switch ($routeInfo[0]){
    case \FastRoute\Dispatcher::NOT_FOUND:
        //TODO: 404 error
        break;
    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        //TODO: 405 error
        //NTS: 405 errors require responding with allowed methods which is stored $routeInfo[1]
        break;
    case \FastRoute\Dispatcher::FOUND:
        $method = $routeInfo[1][1];
        $parameters = $routeInfo[2];
        $container->get($routeInfo[1])->$routeInfo[1][1];

        break;
}