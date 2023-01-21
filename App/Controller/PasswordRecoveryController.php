<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Services\EMailService;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use App\Entities\User;
use Core\View;
use Laminas\Diactoros\Response\RedirectResponse;

class PasswordRecoveryController
{
    private EntityManager $em;
    private EMailService $emailer;

    public function __construct(EntityManager $manager, EMailService $emailer)
    {
        $this->em = $manager;
        $this->emailer = $emailer;
    }

    public function passwordRecovery(ServerRequestInterface $request): View
    {
        return new View('password-recovery');
    }

    public function linkSent(ServerRequestInterface $request): View
    {
        return new View('link-sent');
    }

    public function getLink(ServerRequestInterface $resquest): View|ResponseInterface
    {
        $data = $resquest->getParsedBody();
        $repository = $this->em->getRepository(User::class);
        $token = $repository->generatePasswordResetToken($data['email']);
        if (empty($token)) {
            return new View('password-recovery', ['errors' => ['email' => 'Theres is no account associated with this email']]);
        }
        $url = $resquest->getUri();
        $url = $url->withPath('/password-reset');
        $url = $url->withQuery("token={$token}");
        $fullurl = $url->__toString();
        $message = <<<M
            Click in the link to recover your password:
            {$fullurl}
M;

        $errors = $this->emailer->addMessage($message)->addAddress($data['email'])
            ->addSubject('password Reset')->sendEmail();
        if (isset($errors)) {
            return new View('password-recovery', compact('errors'));
        }

        return new RedirectResponse('/link-sent');
    }

    public function verifyToken(ServerRequestInterface $request): View|ResponseInterface
    {
        $params = $request->getQueryParams();
        if (!isset($params['token'])) {
            return new RedirectResponse('/login');
        }
        $result = $this->em->getRepository(User::class)->findOneBy(['reset_token' => $params['token']]);
        if (!isset($result)) {
            return new RedirectResponse('/login');
        }

        return new View('password-reset');
    }

    public function resetPassword(ServerRequestInterface $request): View|ResponseInterface
    {
        $params = $request->getQueryParams();
        if (!isset($params['token'])) {
            return new RedirectResponse('/login');
        }
        $data = $request->getParsedBody();
        $repository = $this->em->getRepository(User::class);
        $errors = $repository->changePassword($params['token'], $request->getParsedBody());
        if (isset($errors)) {
            return new View('password-reset', compact('errors'));
        }

        return new RedirectResponse('/login');
    }
}
