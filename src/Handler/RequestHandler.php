<?php

namespace App\Handler;

use SplQueue;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class RequestHandler implements RequestHandlerInterface
{
    /**
     * List of middleware to run
     *
     * @var \SplQueue
     */
    protected $middleware;

    /**
     * RequestHandler construct
     *
     * @param SplQueue $middleware
     */
    public function __construct(SplQueue $middleware)
    {
        $this->middleware = $middleware;
    }

    /**
     * Handles a request and produces a response.
     *
     * May call other collaborating code to generate the response.
     */
    public function handle(Request $request): Response
    {
        return $this->middleware->dequeue()->process($request, $this);
    }
}
