<?php

namespace Core;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Middlewares\Utils\Factory;
use Mezzio\Authentication\UserInterface;

class AuthorizationMiddleware
{
    private array $permissions;
    private ResponseFactoryInterface $responseFactory;

    public function __construct(array $permissions, ResponseFactoryInterface $responseFactory = null)
    {
        $this->permissions = $permissions;
        $this->responseFactory = $responseFactory ?: Factory::getResponseFactory();
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $session = $request->getAttribute('session');
        $user = $session->get(UserInterface::class);
        $userPermissions = $user['details']['permissions'];
        if (count($this->permissions) !== count(array_intersect($userPermissions, $this->permissions))) {
            return $this->responseFactory->createResponse(403);
        }

        return $handler->handle($request);
    }
}
