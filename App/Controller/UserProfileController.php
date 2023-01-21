<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Mezzio\Authentication\UserInterface;
use Respect\Validation\Validator as v;
use Laminas\Diactoros\Response\RedirectResponse;
use Respect\Validation\Exceptions\NestedValidationException;
use App\Entities\User;
use Core\View;
use Mezzio\Csrf\CsrfMiddleware;
use Laminas\Diactoros\Response\EmptyResponse;

class UserProfileController
{
    private EntityManager $em;

    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
    }

    public function profile(ServerRequestInterface $request): View
    {
        $user = $request->getAttribute(UserInterface::class);

        return new View('profile/user-profile', compact('user'));
    }

    public function edit(ServerRequestInterface $request): View
    {
        $user = $request->getAttribute(UserInterface::class);

        return new View('profile/edit-profile', compact('user'));
    }

    public function save(ServerRequestInterface $request): View|ResponseInterface
    {
        $mediaType = ['png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp'];

        $user = $request->getAttribute(UserInterface::class);
        $data = $request->getParsedBody();
        $guard = $request->getAttribute(CsrfMiddleware::GUARD_ATTRIBUTE);
        if (empty($data['_csrf']) || !$guard->validateToken($data['_csrf'])) {
            return new EmptyResponse(412);
        }
        $image = $request->getUploadedFiles();
        $type = $image['image']->getClientMediaType();
        $errors = $this->validateData($data);
        if (isset($image['image']) && !(UPLOAD_ERR_OK === $image['image']->getError() && in_array($type, $mediaType))) {
            $errors['image'] = 'Invalid image';

            return new View('profile/edit-profile', compact('user', 'errors'));
        }
        isset($image[0]) ?? $image = $image[0]->getStream()->getContents();

        if (isset($errors)) {
            return new View('profile/edit-profile', compact('user', 'errors'));
        }
        extract($data);
        if (isset($password) && !($password === $password_confirm)) {
            $errors['password'] = "passwords don't match";

            return new View('profile/edit-profile', compact('user', 'errors'));
        }
        $repository = $this->em->getRepository(User::class);
        $result = $repository->authenticate($user->email, $password_old);
        if (!isset($result)) {
            $errors['password_old'] = 'Incorrect password';

            return new View('profile/edit-profile', compact('user', 'errors'));
        }
        isset($passsword) ?? $user->password = password_hash($passsword, PASSWORD_DEFAULT);
        $user->name = $name;
        $user->lastName = $lastName;
        $user->email = $email;
        if (UPLOAD_ERR_OK === $image['image']->getError()) {
            $image = $image['image']->getStream();
        }
        $user->image = base64_encode($image->getContents());
        $this->em->persist($user);
        $this->em->flush();

        return new RedirectResponse('/user');
    }

    private function validateData(array $data): ?array
    {
        $validator = v::key('name', v::notEmpty()->length(null, 100))
            ->key('lastName', v::notEmpty()->length(null, 100))
            ->key('email', v::email()->notEmpty()->length(null, 100))
            ->key('password', v::optional(v::notEmpty()->length(8, 100)))
            ->key('password_confirm', v::optional(v::notEmpty()->length(8, 100)))
            ->key('password_old', v::notEmpty())
        ;

        try {
            $validator->assert($data);
        } catch (NestedValidationException $th) {
            return $th->getMessages(['password_old' => 'Type your current password']);
        }

        return null;
    }
}
