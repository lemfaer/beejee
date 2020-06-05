<?php

namespace App\Core;

use Exception;
use ArrayObject;

use function strcasecmp;
use function preg_match;
use function array_slice;

class Router extends ArrayObject
{
    /**
     * Dispatches against the provided HTTP method verb and URI.
     * Returns array with the following format: [ $handler, $args ]
     *
     * @param string $method
     * @param string $url
     *
     * @throws \Exception not allowed
     *
     * @return array
     */
    function dispatch(string $method, string $url): array
    {
        foreach ($this as [$allowed, $pattern, $handler]) {
            if (
                preg_match($pattern, $url, $args)
                && !strcasecmp($method, $allowed)
            ) {
                return [ $handler, array_slice($args, 1) ];
            }
        }

        // 404 http error if not found
        throw new Exception("Method not allowed", 404);
    }
}
