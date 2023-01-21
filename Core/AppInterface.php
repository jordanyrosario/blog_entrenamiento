<?php

namespace Core;

use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Server\MiddlewareInterface;

interface AppInterface extends RequestHandlerInterface
{
    public function use(callable|array|MiddlewareInterface $middlewares): self;
}
