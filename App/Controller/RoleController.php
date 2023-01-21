<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use App\Entities\Role;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use App\Entities\Permission;
use Core\View;
use Doctrine\Common\Collections\ArrayCollection;

class RoleController
{
    private $em;
    private $repository;

    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
        $this->repository = $manager->getRepository(Role::class);
    }

    public function index(ServerRequestInterface $request): View
    {
        $roles = $this->repository->findAll();

        return new View('roles/index', compact('roles'));
    }

    public function create(ServerRequestInterface $request): View
    {
        $permissions = $this->em->getRepository(Permission::class)->findAll();

        return new View('roles/create', compact('permissions'));
    }

    public function store(ServerRequestInterface $request): View|ResponseInterface
    {
        $data = $request->getParsedBody();

        $errors = $this->validateRole($data);
        if (!is_null($errors)) {
            $role = (object) $data;

            return new View('roles/create', compact('role', 'errors'));
        }

        $permissions = $this->em->getRepository(Permission::class)->findby(['id' => $data['permissions']]);
        $role = new role();
        $role->name = trim($data['name']);
        $role->name = $data['name'];
        $role->description = $data['description'];
        $role->permissions = new ArrayCollection($permissions);
        $this->em->persist($role);
        $this->em->flush();

        return new RedirectResponse('/admin/roles');
    }

    public function view(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $role = $this->repository->find($id);
        if (is_null($role)) {
            return NotFound();
        }

        return new View('roles/role', compact('role'));
    }

    public function edit(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $role = $this->repository->find($id);
        if (is_null($role)) {
            return NotFound();
        }
        $permissions = $this->em->getRepository(Permission::class)->findAll();

        return new View('roles/edit', compact('role', 'permissions'));
    }

    public function update(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $errors = $this->validateRole($data, $id);
        if (!empty($errors)) {
            return $errors;
        }
        $role = $this->repository->find($id);
        $role->name = trim($data['name']);
        $permissions = $this->em->getRepository(Permission::class)->findby(['id' => $data['permissions']]);
        $role->permissions = new ArrayCollection($permissions);

        $role->description = $data['description'];
        $this->em->persist($role);
        $this->em->flush();

        return new RedirectResponse('/admin/roles');
    }

    public function destroy(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $role = $this->repository->find($id);

        $this->em->remove($role);
        $this->em->flush();

        return new RedirectResponse('/admin/roles');
    }

    private function validateRole(array $data, int $id = null): mixed
    {
        $validator = v::key('name', v::notEmpty()->StringType()->Length(null, 100))
            ->key('description', v::optional(v::length(null, 254)->StringType()))
            ->key('permissions', v::arrayType()->Each(v::alnum()))
        ;

        try {
            $validator->assert($data);
            $result = $this->repository->findOneBy(['name' => trim($data['name'])]);
            if ((!empty($result) && is_null($id)) || (!is_null($id) && !empty($result) && $result->getId() != $id)) {
                return ['slug' => 'Role  already exists'];
            }
        } catch (NestedValidationException $th) {
            return $th->getMessages();
        }

        return null;
    }
}
