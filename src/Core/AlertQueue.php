<?php

namespace App\Core;

use SplQueue;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;

class AlertQueue extends SplQueue
{
    /**
     * @var \Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface
     */
    protected $storage;

    /**
     * Import values from session
     */
    public function __construct(ClearableTokenStorageInterface $storage)
    {
        $this->storage = $storage;

        if ($this->storage->hasToken("alerts")) {
            $this->unserialize($this->storage->getToken("alerts"));
        }
    }

    /**
     * Export values to session
     */
    public function __destruct()
    {
        $this->storage->setToken("alerts", $this->serialize());
    }
}
