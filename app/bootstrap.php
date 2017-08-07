<?php
declare(strict_types=1);
session_start();
require_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

// CREATING CONTAINER
$containerBuilder = new \DI\ContainerBuilder();
$containerBuilder->useAutowiring(true);
$containerBuilder->useAnnotations(false);
$containerBuilder->addDefinitions(__DIR__ . DIRECTORY_SEPARATOR . 'dependencyInjection' . DIRECTORY_SEPARATOR . 'definitions.php');
$container = $containerBuilder->build();

// CREATE REQUEST
$request = $container->get(\Psr\Http\Message\ServerRequestInterface::class);

// REQUIRING AND SETTING UP ROUTER
require_once __DIR__ . DIRECTORY_SEPARATOR . 'router' . DIRECTORY_SEPARATOR . 'routes.php';
$routeInfo = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

// ROUTING TO DISPATCHER
switch ($routeInfo[0]) {
    case \FastRoute\Dispatcher::NOT_FOUND:
            // TODO: NOT FOUND ERROR HANDLING
        break;

    case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $response = $container->get(\Psr\Http\Message\ResponseInterface::class);
        if ($request->getMethod() == 'OPTIONS') {
            $response = $response->withHeader('access-control-allow-methods', $routeInfo[1]); // TODO: Check if routeInfo can be array
        } else {
            $response = $response->withStatus(405);
            $response = $response->withHeader('allow', $routeInfo[1]); // TODO: Check if routeInfo can be array
        }
        $emitter = new \Zend\Diactoros\Response\SapiEmitter();
        $emitter->emit($response);
        break;

    case \FastRoute\Dispatcher::FOUND:
        $parameters = $routeInfo[2];
        $response = $container->call($routeInfo[1], $routeInfo[2]);
        $response->withHeader('Content-Type', 'application/json');
        $emitter = new \Zend\Diactoros\Response\SapiEmitter();
        $emitter->emit($response);
        break;
    default:
        //TODO: figure out if reachable, and handle potential error
}
