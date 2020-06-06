<?php

namespace App\Controller;

use Exception;
use App\Model\User;
use App\Core\Controller;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface as Container;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface;

class AuthController extends Controller
{
    /**
     * @var \Symfony\Component\Security\Csrf\TokenStorage\ClearableTokenStorageInterface
     */
    protected $storage;

    /**
     * Controller constructor
     *
     * @param \Psr\Container\ContainerInterface $container
     * @param \Psr\Http\Message\ServerRequestInterface $request
     */
    public function __construct(Container $container, Request $request)
    {
        parent::__construct($container, $request);

        $this->storage = $this->container->get(ClearableTokenStorageInterface::class);
    }

    /**
     * @example /login
     */
    public function login(): Response
    {
        return $this->template(200, "login");
    }

    /**
     * @example POST /login
     */
    public function loginSubmit(): Response
    {
        $data = $this->request->getParsedBody();

        if (!$this->isCsrfTokenValid("login", $data["token"])) {
            return $this->loginFail();
        }

        try {
            $user = $this->container
                ->get(UserRepository::class)
                ->getUserByLogin($data["login"]);
        } catch (Exception $e) {
            return $this->loginFail();
        }

        if (!$user->verifyPassword($data["password"])) {
            return $this->loginFail();
        }

        $this->setLoggedIn($user->login);

        return $this->redirect(200, "/");
    }

    /**
     * @example /logout
     */
    public function logout(): Response
    {
        $this->setLogout();

        return $this->redirect(200, "/");
    }

    protected function loginFail(): Response
    {
        $this->message->enqueue("Invalid login or password");

        return $this->redirect(200, "/login");
    }

    public function isLoggedIn(): bool
    {
        return $this->storage->hasToken("login");
    }

    public function getLoggedIn(): ?string
    {
        if ($this->isLoggedIn()) {
            return $this->storage->getToken("login");
        }

        return null;
    }

    public function setLoggedIn(string $login): void
    {
        $this->storage->setToken("login", $login);
    }

    public function setLogout(): void
    {
        $this->storage->removeToken("login");
        $this->storage->clear();
    }
}
