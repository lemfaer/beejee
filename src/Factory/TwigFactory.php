<?php

namespace App\Factory;

use App\Core\AlertQueue;
use App\Core\FactoryInterface;
use App\Controller\AuthController;
use Psr\Container\ContainerInterface as Container;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Twig\TwigFunction;
use Twig\Environment;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class TwigFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Container $container): object
    {
        $config = $container->get("config");

        $templatesPath = $config["app_views_dir"];
        $cachePath = $config["app_cache_dir"] . "/twig";

        $cachePath = false;

        $twig = new Environment(
            new FilesystemLoader($templatesPath),
            [
                'debug' => true,
                "cache" => $cachePath,
            ]
        );

        $twig->addExtension(new DebugExtension());

        $twig->addFunction(
            new TwigFunction(
                "csrf_token",
                function (string $tokenId) use ($container): string {
                    return $container
                        ->get(CsrfTokenManagerInterface::class)
                        ->getToken($tokenId)
                        ->getValue();
                }
            )
        );

        $twig->addGlobal("user", $container->get(AuthController::class)->getLoggedIn());
        $twig->addGlobal("message", $container->get(AlertQueue::class));

        return $twig;
    }
}
