<?php

namespace App\Core;

use Throwable;
use Exception;
use Psr\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class Container implements ContainerInterface
{
    /**
     * Assoc array of defined entries
     *
     * @var array
     */
    protected $entries;

    /**
     * Key - class name, Value - factory funcion
     *
     * @see \App\Core\FactoryInterface::create
     *
     * @var array
     */
    protected $factories;

    /**
     * Container construct
     *
     * @param array $entries
     * @param array $factories
     */
    public function __construct(array $entries = [], array $factories = [])
    {
        $this->entries = $entries;
        $this->factories = $factories;
    }

    /**
     * Finds an entry of the container by its identifier and returns it.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @throws NotFoundExceptionInterface  No entry was found for **this** identifier.
     * @throws ContainerExceptionInterface Error while retrieving the entry.
     *
     * @return mixed Entry.
     */
    public function get($id)
    {
        try {
            if (array_key_exists($id, $this->entries)) {
                return $this->entries[$id];
            }

            if (array_key_exists($id, $this->factories)) {
                $entry = $this->factories[$id]($this);
                $this->entries[$id] = $entry;

                return $entry;
            }
        } catch (Throwable $e) {
            $m = $e->getMessage();
            $c = $e->getCode();

            throw new class($m, $c, $e)
                extends Exception
                implements ContainerExceptionInterface {};
        }

        throw new class("Container entry $id not found")
            extends Exception
            implements NotFoundExceptionInterface {};
    }

    /**
     * Returns true if the container can return an entry for the given identifier.
     * Returns false otherwise.
     *
     * `has($id)` returning true does not mean that `get($id)` will not throw an exception.
     * It does however mean that `get($id)` will not throw a `NotFoundExceptionInterface`.
     *
     * @param string $id Identifier of the entry to look for.
     *
     * @return bool
     */
    public function has($id)
    {
        return array_key_exists($id, $this->entries);
    }
}
