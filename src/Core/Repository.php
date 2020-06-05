<?php

namespace App\Core;

use PDO;
use Psr\Container\ContainerInterface as Container;

abstract class Repository
{
    /**
     * MySQL database connection
     *
     * @var \PDO
     */
    protected $db;

    /**
     * Repository construct
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(Container $connection)
    {
        $this->db = $container->get(PDO::class);
    }
}
