<?php

use Ozag\Skeleton\Infrastructure\Config\Repository\ConfigRepository;
use Interop\Container\ContainerInterface;
use Slim\CallableResolver;
use Slim\Handlers\NotAllowed;
use Slim\Handlers\NotFound;
use Slim\Handlers\Strategies\RequestResponse;
use Slim\Http\Environment;
use Slim\Http\Headers;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Router;
use Slim\Handlers\Error;

return [
    /*
     * This service MUST return an array or an
     * instance of \ArrayAccess.
     */
    'settings' => function (ContainerInterface $container) {
        $config = $container->get(ConfigRepository::class);
        return $config->get('slim');
    },

    /*
     * This service MUST return a shared instance
     * of \Slim\Interfaces\Http\EnvironmentInterface.
     */
    'environment' => function () {
        return new Environment($_SERVER);
    },

    /*
     * PSR-7 Request object
     */
    'request' => function (ContainerInterface $container) {
        $environment = $container->get('environment');

        return Request::createFromEnvironment($environment);
    },

    /*
     * PSR-7 Response object
     */
    'response' => function (ContainerInterface $container) {
        $config = $container->get(ConfigRepository::class);
        $slimConfig = $config->get('slim');

        $headers = new Headers(['Content-Type' => 'text/html']);
        $response = new Response(200, $headers);

        return $response->withProtocolVersion($slimConfig['httpVersion']);
    },

    /*
     * This service MUST return a SHARED instance
     * of \Slim\Interfaces\RouterInterface.
     */
    'router' => function () {
        return new Router;
    },

    /*
     * This service MUST return a SHARED instance
     * of \Slim\Interfaces\InvocationStrategyInterface.
     */
    'foundHandler' => function () {
        return new RequestResponse;
    },

    /*
     * This service MUST return a callable
     * that accepts three arguments:
     *
     * 1. Instance of \Psr\Http\Message\ServerRequestInterface
     * 2. Instance of \Psr\Http\Message\ResponseInterface
     * 3. Instance of \Exception
     *
     * The callable MUST return an instance of
     * \Psr\Http\Message\ResponseInterface.
     */
    'errorHandler' => function (ContainerInterface $container) {
        $config = $container->get(ConfigRepository::class);
        $slimConfig = $config->get('slim');

        return new Error($slimConfig['displayErrorDetails']);
    },

    /*
     * This service MUST return a callable
     * that accepts two arguments:
     *
     * 1. Instance of \Psr\Http\Message\ServerRequestInterface
     * 2. Instance of \Psr\Http\Message\ResponseInterface
     *
     * The callable MUST return an instance of
     * \Psr\Http\Message\ResponseInterface.
     */
    'notFoundHandler' => function () {
        return new NotFound;
    },

    /*
     * This service MUST return a callable
     * that accepts three arguments:
     *
     * 1. Instance of \Psr\Http\Message\ServerRequestInterface
     * 2. Instance of \Psr\Http\Message\ResponseInterface
     * 3. Array of allowed HTTP methods
     *
     * The callable MUST return an instance of
     * \Psr\Http\Message\ResponseInterface.
     */
    'notAllowedHandler' => function () {
        return new NotAllowed;
    },

    /*
     * Instance of \Slim\Interfaces\CallableResolverInterface
     */
    'callableResolver' => function (ContainerInterface $container) {
        return new CallableResolver($container);
    },
];
