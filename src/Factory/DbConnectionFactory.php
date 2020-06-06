<?php

namespace App\Factory;

use PDO;
use App\Core\FactoryInterface;
use Psr\Container\ContainerInterface as Container;

class DbConnectionFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function create(Container $container): object
    {
        $config = $container->get("config");

        return new PDO(
            sprintf(
                "mysql:host=%s;dbname=%s",
                $config["db_host"],
                $config["db_base"]
            ),
            $config["db_user"],
            $config["db_pass"],
            $this->getOptions()
        );
    }

    protected function getOptions(): array
    {
        return [
            PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8",
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];
    }
}
