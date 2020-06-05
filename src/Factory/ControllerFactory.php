<?php

namespace App\Factory;

use Exception;
use App\Core\Controller;
use App\Core\FactoryInterface;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ServerRequestInterface as Request;

class ControllerFactory implements FactoryInterface
{
    /**
     * Controller class
     *
     * @var string
     */
    protected $class;

    /**
     * ControllerFactory construct
     *
     * @param string $class name
     */
    public function __construct(string $class)
    {
        if (!is_subclass_of($class, Controller::class)) {
            throw new Exception("Wrong class for ControllerFactory");
        }

        $this->class = $class;
    }

    /**
     * {@inheritDoc}
     */
    public function create(Container $container): Controller
    {
        $class = $this->class;
        $request = $container->get(Request::class);

        return new $class($container, $request);
    }
}
