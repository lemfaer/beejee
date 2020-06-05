<?php

namespace App\Core;

use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;

use function json_encode;

use const JSON_THROW_ON_ERROR;

abstract class Controller
{
    /**
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * Controller constructor
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(Container $container, Request $request)
    {
        $this->container = $container;
        $this->request = $request;
    }

    /**
     * Create a response from string
     *
     * @param int $code http code
     * @param string $body
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function response(int $code = 200, string $body = ''): Response
    {
        return $this->container
            ->get(ResponseFactory::class)
            ->createResponse($code)
            ->withBody(
                $this->container
                    ->get(StreamFactory::class)
                    ->createStream($body)
            );
    }

    /**
     * Create a json response
     *
     * @param int $code http code
     * @param mixed $body
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function jsonResponse(int $code = 200, $body = null): Response
    {
        $body = json_encode($body, JSON_THROW_ON_ERROR);

        return $this
            ->response($code, $body)
            ->withHeader("Content-Type", "application/json");
    }
}
