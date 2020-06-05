<?php

namespace App\Factory;

use PDO;
use Exception;
use App\Core\Repository;
use App\Core\FactoryInterface;
use Psr\Container\ContainerInterface as Container;

class RepositoryFactory implements FactoryInterface
{
    /**
     * Repository class
     *
     * @var string
     */
    protected string $class;

    /**
     * RepositoryFactory construct
     *
     * @param string $class name
     */
    public function __construct(string $class)
    {
        if (!is_subclass_of($class, Repository::class)) {
            throw new Exception("Wrong class for RepositoryFactory");
        }

        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Container $container): Repository
    {
        $class = $this->class;

        return new $class($container);
    }
}
