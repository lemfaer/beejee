<?php

namespace App;

use App\Handler\ErrorHandler;
use App\Factory\ContainerFactory;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Laminas\HttpHandlerRunner\Emitter\EmitterInterface as Emitter;

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// composer autoloader
require_once __DIR__ . "/../vendor/autoload.php";

set_exception_handler([new ErrorHandler(), "exceptionHandler"]);

$container = ContainerFactory::getContainer([
    "routes" => require_once __DIR__ . "/../etc/routes.php",
    "config" => require_once __DIR__ . "/../etc/config.php"
]);

$container
    ->get(Emitter::class)
    ->emit(
        $container
            ->get(RequestHandler::class)
            ->handle(
                $container->get(Request::class)
            )
        );
