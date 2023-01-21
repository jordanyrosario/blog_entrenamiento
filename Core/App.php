<?php

namespace Core;

use Laminas\Diactoros\ServerRequest;
use Laminas\Diactoros\Stream;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\{RequestHandlerInterface, MiddlewareInterface};

class App implements RequestHandlerInterface
{
    private $request;
    private $response;
    private $container;
    private $middlewares = [];
    private $fallbackHandler;

    public function __construct(\Psr\Container\ContainerInterface $container, ServerRequest $request = null, ResponseInterface $response = null)
    {
        $this->container = $container;
        $this->request = $request ?? ServerRequestFactory::fromGlobals();
        $this->response = $response ?? (new \Laminas\Diactoros\Response\EmptyResponse());
    }

    public function setRquest(ServerRequest $request)
    {
        $this->request = $request;
    }

    public function getRquest(): ServerRequestInterface
    {
        return $this->request;
    }

    public function __invoke(ServerRequestInterface $request): ResponseInterface
    {
        return $this->handle($request);
    }

    public function run(MiddlewareInterface $middlewares = null): ResponseInterface
    {
        $app = $this;
        $request = $this->request;
        $container = $this->container;
        if (!empty($middlewares)) {
            $app = new self($container, $request, $this->response);
            $app->use($middlewares);
        }

        return (empty($app->middlewares))
            ? throw new \Laminas\Diactoros\Exception\InvalidArgumentException('Can\'t run, no middleware found')
            : $app->handle($request);
    }

    public function use(array|callable|MiddlewareInterface $middleware): self
    {
        $app = $this;
        $this->middlewares = array_merge(
            $middleware,
            $app->middlewares,
        );

        return $this;
    }

    public function handle(ServerRequestInterface $request, array $middlewares = null): ResponseInterface
    {
        $this->setRquest($request);
        if ($middlewares) {
            $this->use($middlewares);
        }
        $middleware = array_shift($this->middlewares);
        if (is_string($middleware)) {
            $middleware = $this->container->get($middleware);
        }

        if ($middleware) {
            $this->response = $middleware->process($request, $this);
        }

        // @see https://tools.ietf.org/html/rfc7231#section-4.3.2
        if ('HEAD' === $request->getMethod()) {
            $this->response = $this->response
                ->withBody(
                    new Stream('')
                )
            ;
        }

        return $this->response;
    }
}
