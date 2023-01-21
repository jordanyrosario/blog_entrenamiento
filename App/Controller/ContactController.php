<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use App\Services\EMailService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;
use Laminas\Diactoros\Response;

class ContactController
{
    private EntityManager $em;
    private EMailService $emailer;

    public function __construct(EntityManager $manager, EMailService $emailer)
    {
        $this->em = $manager;
        $this->emailer = $emailer;
    }

    public function index(): ResponseInterface
    {
        return View('contact');
    }

    public function send(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $errors = $this->validateContact($data);

        if ($errors) {
            return View('contact', compact('errors'));
        }
        extract($data);
        $message = "Name: {$name} \n Email: {$email} \n Body: {$body}";

        $this->emailer->addSubject('Blog Contact :: '.$data['subject'])
            ->addAddress($_ENV['CONTACT_EMAIL'])
            ->replyTo($data['email'])
            ->addMessage($message)
        ;

        $result = $this->emailer->sendEmail();
        if (isset($result)) {
            return new Response('500');
        }

        return View('contact-sent');
    }

    private function validateContact(array $data): ?array
    {
        $validator = v::key('name', v::notEmpty()->stringType()->length(null, 100))
            ->key('subject', v::notEmpty()->stringType()->length(null, 100))
            ->key('body', v::notEmpty()->stringType()->length(null, 500))
            ->key('email', v::notEmpty()->email())
        ;

        try {
            return $validator->assert($data);
        } catch (NestedValidationException $th) {
            return $th->getMessages();
        }
    }
}
