<?php
declare(strict_types=1);


require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// CREATING CONTAINER
$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(__DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'dependencyInjection.php');
$container = $containerBuilder->build();

// CREATE REQUEST
$request = $container->get(\Psr\Http\Message\ServerRequestInterface::class);

// REQUIRING AND SETTING UP ROUTER
require_once __DIR__ . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'routes.php';
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

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
        $parameters = $routeInfo[2];
        $response = $container->call($routeInfo[1]);
        $emitter = new \Zend\Diactoros\Response\SapiEmitter();
        $emitter->emit($response);
        echo 'eob';
        break;
    default:
        //TODO: figure out if reachable, and handle potential error
}
