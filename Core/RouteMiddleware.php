<?php

namespace Core;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Middlewares\Utils\Factory;
use FastRoute\Dispatcher;
use Psr\Http\Server\RequestHandlerInterface;

class RouteMiddleware implements MiddlewareInterface
{
    /** @var Dispatcher */
    private $router;

    /** @var ResponseFactoryInterface */
    private $responseFactory;

    /** @var App */
    private $app;

    public function __construct(Dispatcher $router, ResponseFactoryInterface $responseFactory = null)
    {
        $this->router = $router;
        $this->responseFactory = $responseFactory ?: Factory::getResponseFactory();
    }

    /**
     * Process an incoming server request.
     * Processes an incoming server request in order to produce a response.
     * If unable to produce the response itself, it may delegate to the provided
     * request handler to do so.
     */
    public function process(\Psr\Http\Message\ServerRequestInterface $request, RequestHandlerInterface $handler): \Psr\Http\Message\ResponseInterface
    {
        $match = $this->router->dispatch($request->getMethod(), rawurldecode($request->getUri()->getPath()));

        if (Dispatcher::NOT_FOUND === $match[0]) {
            return $this->responseFactory->createResponse(404);
        }
        if (Dispatcher::METHOD_NOT_ALLOWED === $match[0]) {
            return $this->responseFactory->createResponse(405);
        }

        $request = $request->withAttribute('request-handler', $match[1]);
        foreach ($match[2] as $name => $value) {
            $request = $request->withAttribute($name, $value);
        }

        if (3 === count($match[1])) {
            return $handler->handle($request, $match[1]['middlewares']);
        }

        return $handler->handle($request);
    }
}
