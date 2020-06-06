<?php

namespace App\Factory;

use App\Core\AlertQueue;
use App\Core\FactoryInterface;
use Psr\Container\ContainerInterface as Container;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;

class AlertQueueFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Container $container): object
    {
        $storage = $container->get(ClearableTokenStorageInterface::class);

        return new AlertQueue($storage);
    }
}
