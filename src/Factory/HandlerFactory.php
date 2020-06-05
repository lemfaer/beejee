<?php

namespace App\Factory;

use SplQueue;
use App\Core\FactoryInterface;
use App\Handler\RequestHandler;
use App\Handler\ExecuteController;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Server\RequestHandlerInterface;

class HandlerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Container $container): RequestHandlerInterface
    {
        $middleware = $this->createMiddleware($container);

        return new RequestHandler($middleware);
    }

    /**
     * Creates application middleware
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return SplQueue with \Psr\Http\Server\MiddlewareInterface
     */
    public function createMiddleware(Container $container): SplQueue
    {
        $queue = new SplQueue();

        $queue->enqueue(new ExecuteController($container));

        return $queue;
    }
}
