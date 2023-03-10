<?php

namespace App\Repositories;

use App\Entities\Role;
use Mezzio\Authentication\UserInterface;
use Mezzio\Authentication\UserRepositoryInterface;
use Respect\Validation\Validator as v;
use App\Entities\User;
use Respect\Validation\Exceptions\NestedValidationException;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * UserRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class UserRepository extends \Doctrine\ORM\EntityRepository implements UserRepositoryInterface
{
    public function authenticate(string $credential, $password = null): ?UserInterface
    {
        $user = $this->findOneBy(['email' => $credential]);

        if (isset($user) && password_verify($password, $user->password)) {
            return $user;
        }

        return null;
    }

    public function generatePasswordResetToken(string $email): ?string
    {
        $result = $this->_em->getRepository(User::class)
            ->findOneBy(['email' => $email])
        ;

        if (empty($result)) {
            return null;
        }
        $token = bin2hex(random_bytes(64));
        $result->reset_token = $token;
        $result->reset_token_date = new \DateTime();
        $this->_em->persist($result);
        $this->_em->flush();

        return $token;
    }

    public function changePassword(string $token, array $data): ?array
    {
        $result = $this->_em->getRepository(User::class)->findOneBy(['reset_token' => $token]);

        if (empty($result)) {
            return null;
        }
        if ($data['password'] !== $data['password_confirm']) {
            return ['password' => 'Password does not match'];
        }
        $result->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $result->reset_token = null;
        $result->reset_token_date = null;
        $this->_em->persist($result);
        $this->_em->flush();

        return null;
    }

    public function create(array $data): ?array
    {
        $data['roles'] = null;

        $errors = $this->validateUser($data);
        if ($errors) {
            return $errors;
        }
        $query = $this->_em->createQuery('select u FROM App\Entities\User u WHERE u.email = :email ');
        $query->setParameter('email', $data['email']);
        $result = $query->getResult();
        if (!empty($result)) {
            return ['email' => 'There is an account associated with this email already'];
        }
        if ($data['password'] !== $data['password_confirm']) {
            return ['password' => 'Password must match'];
        }

        $user = new User();

        $role = $this->_em->getRepository(Role::class)->findOneBy(['name' => 'user']);
        $user->getAllRoles()->add($role);
        $user->name = $data['name'];
        $user->lastName = $data['lastname'];
        $user->email = $data['email'];

        $user->password = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->_em->persist($user);
        $this->_em->flush();

        return null;
    }

    public function update(array $data, int $id): ?array
    {
        $errors = $this->validateUser($data, $id);
        if ($errors) {
            return $errors;
        }
        $user = $this->_em->repository->find($id);

        if (empty($user)) {
            return ['id' => 'Coudm\'t find the user'];
        }

        $roles = $this->_em->getRepository(Role::class)->findBy(['id' => $data['roles']]);
        $user->name = $data['name'];
        $user->lastName = $data['lastname'];
        $user->email = $data['email'];
        $user->roles = new ArrayCollection($roles);
        $this->_em->persist($user);
        $this->_em->flush();

        return null;
    }

    private function validateUser(array $data, int $id = null): ?array
    {
        try {
            $validator = v::create()->key('name', v::notEmpty()->length(null, 100))
                ->key('lastname', v::notEmpty()->length(null, 100))
                ->key('email', v::email()->notEmpty()->length(null, 254))
                ->key('password', v::notEmpty()->length(8, 24))
                ->key('roles', v::optional(v::arrayType()->each(v::alnum())))
            ;

            return $validator->assert($data);
        } catch (NestedValidationException $th) {
            return $th->getMessages();
        }
    }
}
