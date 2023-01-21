<?php

namespace App\Controller;

use Core\View;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Mezzio\Session\SessionInterface;
use Laminas\Diactoros\Uri;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\AuthenticationInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Csrf\CsrfMiddleware;
use Laminas\Diactoros\Response\EmptyResponse;

class LoginController
{
    private const REDIRECT_ATTRIBUTE = 'authentication:redirect';

    /** @var EntityManager */
    private $em;

    /** @var AuthenticationInterface */
    private $adapter;

    public function __construct(EntityManager $manager, AuthenticationInterface $adapter)
    {
        $this->em = $manager;
        $this->adapter = $adapter;
    }

    public function login(ServerRequestInterface $request): View
    {
        return new View('login');
    }

    public function logout(ServerRequestInterface $request): ResponseInterface
    {
        $session = $request->getAttribute(SessionInterface::class);
        $session->unset(UserInterface::class);
        $session->clear();
        session_destroy();

        return new RedirectResponse('/login');
    }

    public function auth(ServerRequestInterface $request): View|ResponseInterface
    {
        $session = $request->getAttribute('session');
        $redirect = $this->getRedirect($request, $session);

        // Handle submitted credentials
        if ('POST' === $request->getMethod()) {
            return $this->handleLoginAttempt($request, $session, $redirect);
        }

        // Display initial login form
        $session->set(self::REDIRECT_ATTRIBUTE, $redirect);

        return new View(
            'login'
        );
    }

    private function getRedirect(
        ServerRequestInterface $request,
        SessionInterface $session
    ): string {
        $redirect = $session->get(self::REDIRECT_ATTRIBUTE);

        if (!$redirect) {
            $redirect = new Uri($request->getHeaderLine('Referer'));
            if (in_array($redirect->getPath(), ['', '/login'], true)) {
                $redirect = '/';
            }
        }

        return $redirect;
    }

    private function handleLoginAttempt(
        ServerRequestInterface $request,
        SessionInterface $session,
        string $redirect
    ): View|ResponseInterface {
        $session->unset(UserInterface::class);
        $data = $request->getParsedBody();
        $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
        if (empty($data['_csrf']) || !$guard->validateToken($data['_csrf'])) {
            return new EmptyResponse(412);
        }
        // Login was successful
        if ($this->adapter->authenticate($request)) {
            $session->unset(self::REDIRECT_ATTRIBUTE);

            return new RedirectResponse($redirect);
        }

        // Login failed
        return new View(
            'login',
            ['error' => 'Invalid credentials']
        );
    }
}
