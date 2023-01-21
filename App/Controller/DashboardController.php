<?php

namespace App\Controller;

use Core\View;
use Psr\Http\Message\ServerRequestInterface;

class DashboardController
{
    public function index(ServerRequestInterface $request): View
    {
        return new View('dashboard');
    }
}
