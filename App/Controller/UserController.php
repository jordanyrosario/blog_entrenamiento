<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use App\Entities\User;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use App\Entities\Role;
use Doctrine\Common\Collections\ArrayCollection;
use Core\View;
use Doctrine\ORM\Query\Parameter;

class UserController
{
    private $em;
    private $repository;

    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
        $this->repository = $manager->getRepository(User::class);
    }

    public function index(ServerRequestInterface $request): View|ResponseInterface
    {
        // Search method
        $data = $request->getQueryParams();

        if (null != $data) {
            $q = $this->em->createQueryBuilder();
            $q->select('u')->
                from('App\Entities\User', 'u')->
                where($q->expr()->orX(
                    $q->expr()->like('u.name', ':name'),
                    $q->expr()->like('u.lastName', ':lastName'),
                    $q->expr()->like('u.email', ':email')
                ));
            // Bind parameters
            $q->setParameters(new ArrayCollection([
                new Parameter('name', '%'.$data['search'].'%'),
                new Parameter('lastName', '%'.$data['search'].'%'),
                new Parameter('email', '%'.$data['search'].'%'),
            ]));

            $result = $q->getQuery()->getResult();

            return new View('user/index', compact('result'));
        }

        $users = $this->repository->findAll();

        return new View('user/index', compact('users'));
    }

    public function create(ServerRequestInterface $request): View|ResponseInterface
    {
        return new View('user/create');
    }

    public function store(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();
        $user = new User();
        $user->name = $data['name'];
        $user->lastName = $data['last_name'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->image = $data['image'];
        $this->em->persist($user);
        $this->em->flush();

        return new RedirectResponse('/admin/');
    }

    public function view(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $user = $this->repository->find($id);
        if (is_null($user)) {
            return NotFound();
        }

        return new View('user/user', compact('user'));
    }

    public function edit(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $user = $this->repository->find($id);
        if (is_null($user)) {
            return NotFound();
        }
        $roles = $this->em->getRepository(Role::class)->findAll();

        return new View('user/edit', compact('user', 'roles'));
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();

        $user = $this->repository->find($id);
        $user->name = $data['name'];
        $user->lastName = $data['lastName'];
        $user->email = $data['email'];
        $user->password = $data['password'];
        $user->image = $data['image'];
        $roles = $this->em->getRepository(Role::class)->findBy(['id' => $data['role']]);
        $user->role = new ArrayCollection($roles);
        $this->em->persist($user);
        $this->em->flush();

        return new RedirectResponse('/admin/users');
    }

    public function destroy(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $user = $this->repository->find($id);

        $this->em->remove($user);
        $this->em->flush();

        return new RedirectResponse('/admin/users');
    }
}
