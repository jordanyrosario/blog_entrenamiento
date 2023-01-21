<?php

declare(strict_types=1);

namespace Core;

use Middlewares\Utils\RequestHandlerContainer;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Mezzio\Authentication\UserInterface;
use Mezzio\Session\SessionMiddleware;
use Mezzio\Csrf\CsrfMiddleware;

class RequestHandlerMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface Used to resolve the handlers
     */
    private $container;

    /**
     * @var bool
     */
    private $continueOnEmpty = false;

    /**
     * @var string Attribute name for handler reference
     */
    private $handlerAttribute = 'request-handler';

    /** @var App */
    private $app;

    public function __construct(App $app, ContainerInterface $container = null)
    {
        $this->app = $app;
        $this->container = $container ?: new RequestHandlerContainer();
    }

    /**
     * Set the attribute name to store handler reference.
     */
    public function handlerAttribute(string $handlerAttribute): self
    {
        $this->handlerAttribute = $handlerAttribute;

        return $this;
    }

    /**
     * Configure whether continue with the next handler if custom requestHandler is empty.
     */
    public function continueOnEmpty(bool $continueOnEmpty = true): self
    {
        $this->continueOnEmpty = $continueOnEmpty;

        return $this;
    }

    /**
     * Process a server request and return a response.
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $requestHandler = $request->getAttribute($this->handlerAttribute);

        if (empty($requestHandler)) {
            if ($this->continueOnEmpty) {
                return $handler->handle($request);
            }

            throw new \RuntimeException('Empty request handler');
        }

        if (is_string($requestHandler)) {
            $requestHandler = $this->container->get($requestHandler);
        }

        if (is_array($requestHandler) && is_string($requestHandler[0])) {
            $requestHandler[0] = $this->container->get($requestHandler[0]);
        }

        if ($requestHandler instanceof MiddlewareInterface) {
            return $requestHandler->process($request, $handler);
        }

        if ($requestHandler instanceof RequestHandlerInterface) {
            return $requestHandler->handle($request);
        }

        if (is_array($requestHandler) && 2 === count($requestHandler) && is_object($requestHandler[0])) {
            $response = $requestHandler[0]->{$requestHandler[1]}($request);
            if ($response instanceof ResponseInterface) {
                return $response;
            }
            $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
            $userInfo = [];
            if ($session && $session->has(UserInterface::class)) {
                $userInfo = $session->get(UserInterface::class);
            }
            $user = $request->getAttribute(UserInterface::class);
            $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
            $_csrf = $guard->generateToken();
            $data = compact('user', '_csrf', 'userInfo');

            return $response->addData($data)->render();
        }

        if (is_array($requestHandler) && 3 === count($requestHandler) && is_object($requestHandler[0])) {
            $response = $requestHandler[0]->{$requestHandler[1]}($request);
            if ($response instanceof ResponseInterface) {
                return $response;
            }
            $session = $request->getAttribute(SessionMiddleware::SESSION_ATTRIBUTE);
            $userInfo = [];
            if ($session && $session->has(UserInterface::class)) {
                $userInfo = $session->get(UserInterface::class);
            }
            $user = $request->getAttribute(UserInterface::class);
            $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
            $_csrf = $guard->generateToken();
            $user = $request->getAttribute(UserInterface::class);
            $data = compact('user', '_csrf', 'userInfo');

            return $response->addData($data)->render();
        }

        throw new \RuntimeException(sprintf('Invalid request handler: %s', gettype($requestHandler)));
    }
}
