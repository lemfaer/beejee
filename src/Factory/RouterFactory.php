<?php

namespace App\Factory;

use App\Core\Router;
use App\Core\FactoryInterface;
use Psr\Container\ContainerInterface as Container;

class RouterFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Container $container): object
    {
        $routes = $container->get("routes");

        $router = new Router($routes);

        return $router;
    }
}
