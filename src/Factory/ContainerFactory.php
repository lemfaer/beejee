<?php

namespace App\Factory;

use PDO;
use App\Core\{Router, Container};
use App\Handler\RequestHandler;
use App\Controller\{HelloController, TaskController};
use App\Repository\{UserRepository, TaskRepository};
use Psr\Container\ContainerInterface;
use Psr\Http\Message\{ServerRequestInterface, ResponseFactoryInterface, StreamFactoryInterface};
use Psr\Http\Server\RequestHandlerInterface;
use Laminas\Diactoros\{ResponseFactory, StreamFactory};
use Laminas\HttpHandlerRunner\Emitter\{EmitterInterface, SapiEmitter};
use Twig\Environment;

class ContainerFactory extends Container
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    protected static $container = null;

    /**
     * Container default entries
     *
     * @return array
     */
    public static function getEntries(): array
    {
        return [
            ResponseFactoryInterface::class => new ResponseFactory(),
            StreamFactoryInterface::class => new StreamFactory(),
            EmitterInterface::class => new SapiEmitter(),
        ];
    }

    /**
     * Key - class name, Value - factory funcion
     *
     * @return array
     */
    public static function getFactories(): array
    {
        return [
            ServerRequestInterface::class => [new RequestFactory(), "create"],
            RequestHandlerInterface::class => [new HandlerFactory(), "create"],
            HelloController::class => [new ControllerFactory(HelloController::class), "create"],
            TaskController::class => [new ControllerFactory(TaskController::class), "create"],
            UserRepository::class => [new RepositoryFactory(UserRepository::class), "create"],
            TaskRepository::class => [new RepositoryFactory(TaskRepository::class), "create"],
            Environment::class => [new TwigFactory(), "create"],
            Router::class => [new RouterFactory(), "create"],
            PDO::class => [new DbConnectionFactory(), "create"],
        ];
    }

    /**
     * Get application container
     *
     * @param array $entries container entries
     *
     * @return \Psr\Container\ContainerInterface
     */
    public static function getContainer(array $entries = []): ContainerInterface
    {
        if (!isset(static::$container)) {
            static::$container = static::createContainer($entries);
        }

        return static::$container;
    }

    protected static function createContainer(array $entries = []): ContainerInterface
    {
        $entries += static::getEntries();
        $factories = static::getFactories();
        $container = new static($entries, $factories);

        $container->entries[ContainerInterface::class] = $container;

        return $container;
    }
}
