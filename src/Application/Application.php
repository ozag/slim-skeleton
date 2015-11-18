<?php

namespace Ozag\Skeleton\Application;

use DI\ContainerBuilder;
use Ozag\Skeleton\Infrastructure\Config\Repository\ConfigRepository;
use Interop\Container\ContainerInterface as Container;
use Slim\App as Slim;
use Slim\Interfaces\RouterInterface as Router;

class Application
{
    public function bootstrap()
    {
        // Load configuration files into ConfigRepository
        $config = new ConfigRepository(base_path() . '/config');

        // Initialize container for dependency injection
        $container = $this->initContainer($config);

        // Instantiate Slim
        $slim = new Slim($container);

        // Load routes into Slim router
        $this->initRoutes($config, $container, $slim->router);

        // Run app
        $slim->run();
    }

    /**
     * @param ConfigRepository $config
     * @return \DI\Container
     */
    private function initContainer(ConfigRepository $config)
    {
        $builder = new ContainerBuilder();

        // Register ConfigRepository
        $builder->addDefinitions([
            ConfigRepository::class => $config,
        ]);

        // Register container definitions
        $definitions = $config->get('container')['definitions'];
        foreach ($definitions as $definition) {
            $builder->addDefinitions($definition);
        }

        $container = $builder->build();

        return $container;
    }

    /**
     * @param ConfigRepository $config
     * @param Container $container
     * @param Router $router
     */
    private function initRoutes(ConfigRepository $config, Container $container, Router $router)
    {
        $routes = $config->get('routes');

        foreach ($routes as $request => $controller) {
            $request = explode(' ', $request);

            $method = $request[0];
            $url = $request[1];

            $action = $controller[1];
            $controller = $container->get($controller[0]);

            $router->map([$method], $url, function ($req, $res, $args) use ($controller, $action) {
                return $controller->$action($req, $res, $args);
            });
        }
    }
}
