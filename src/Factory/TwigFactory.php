<?php

namespace App\Factory;

use App\Core\FactoryInterface;
use Psr\Container\ContainerInterface as Container;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Twig\Environment;

class TwigFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Container $container): Environment
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

        return $twig;
    }
}
