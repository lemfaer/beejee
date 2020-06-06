<?php

namespace App\Core;

use Twig\Environment;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\StreamFactoryInterface as StreamFactory;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Security\Csrf\{CsrfTokenManagerInterface, CsrfToken};
use App\Core\AlertQueue;

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
     * @var \App\Core\AlertQueue
     */
    protected $message;

    /**
     * Controller constructor
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(Container $container, Request $request, AlertQueue $message)
    {
        $this->container = $container;
        $this->request = $request;
        $this->message = $message;
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
     * Redirect to another page
     *
     * @param int $code http code
     * @param string $path url
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function redirect(int $code = 200, string $path = '/'): Response
    {
        return $this->container
            ->get(ResponseFactory::class)
            ->createResponse($code)
            ->withHeader("Location", $path);
    }

    /**
     * Twig template response
     *
     * @param int $code http code
     * @param mixed $name The template name
     * @param array $context An array of parameters to pass to the template
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function template(int $code, $name, array $context = []): Response
    {
        return $this->response(
            $code,
            $this->container
                ->get(Environment::class)
                ->render("$name.twig", $context)
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

    /**
     * Checks the validity of a CSRF token.
     *
     * @param string      $id    The id used when generating the token
     * @param string|null $token The actual token sent with the request that should be validated
     */
    protected function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->container
            ->get(CsrfTokenManagerInterface::class)
            ->isTokenValid(new CsrfToken($id, $token));
    }
}
