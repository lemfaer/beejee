<?php

namespace App\Core;

use Psr\Container\ContainerInterface as Container;

interface FactoryInterface
{
    /**
     * Factory method to create instance of class using app container
     *
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return object
     */
    public function create(Container $container): object;
}
