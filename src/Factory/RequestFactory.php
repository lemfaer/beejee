<?php

namespace App\Factory;

use App\Core\FactoryInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Container\ContainerInterface as Container;

class RequestFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Container $container): object
    {
        return ServerRequestFactory::fromGlobals(
            $_SERVER,
            $_GET,
            $_POST,
            $_COOKIE,
            $_FILES
        );
    }
}
