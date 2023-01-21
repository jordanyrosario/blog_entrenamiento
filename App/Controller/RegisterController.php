<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Laminas\Diactoros\Response\RedirectResponse;
use App\Entities\User;
use Core\View;
use Laminas\Diactoros\Response\EmptyResponse;
use Mezzio\Csrf\CsrfMiddleware;

class RegisterController
{
    /** @var EntityManager */
    private $em;

    public function __construct(EntityManager $entityManger)
    {
        $this->em = $entityManger;
    }

public function index(ServerRequestInterface $request): View
{
    return new View('register');
}

public function register(ServerRequestInterface $request): View|ResponseInterface
{
    $data = $request->getParsedBody();
    $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
    if (empty($data['_csrf']) || !$guard->validateToken($data['_csrf'])) {
        return new EmptyResponse(412);
    }
    $userRepository = $this->em->getRepository(User::class);
    $errors = $userRepository->create($data);
    if ($errors) {
        return new View('register', compact('errors'));
    }
    $token = $userRepository->generatePasswordResetToken($data['email']);

    return new RedirectResponse('/login');
}
}
