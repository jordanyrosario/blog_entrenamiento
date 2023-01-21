<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use App\Entities\Category;
use Core\View;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Validator as v;
use Respect\Validation\Exceptions\NestedValidationException;

class CategoryController
{
    private $em;
    private $repository;

    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
        $this->repository = $manager->getRepository(Category::class);
    }

    public function index(ServerRequestInterface $request): View
    {
        $categories = $this->repository->findAll();

        return new View('category/index', compact('categories'));
    }

    public function create(ServerRequestInterface $request): View
    {
        return new View('category/create');
    }

    public function store(ServerRequestInterface $request): View|ResponseInterface
    {
        $data = $request->getParsedBody();
        $errors = $this->validateCategory($data);
        if (!is_null($errors)) {
            $category = (object) $data;

            return new View('category/create', compact('category', 'errors'));
        }
        $category = new Category();
        $category->name = $data['name'];
        $category->description = $data['description'];
        $category->slug = $data['slug'];
        $this->em->persist($category);
        $this->em->flush();

        return new RedirectResponse('/admin/category');
    }

    public function view(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $category = $this->repository->find($id);
        if (is_null($category)) {
            return NotFound();
        }

        return new View('category/show', compact('category'));
    }

    public function edit(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $category = $this->repository->find($id);
        if (is_null($category)) {
            return NotFound();
        }

        return new View('category/edit', compact('category'));
    }

    public function update(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $errors = $this->validateCategory($data, $id);
        if (!is_null($errors)) {
            $category = (object) $data;

            return new View('category/edit', compact('category', 'errors'));
        }
        $category = $this->repository->find($id);
        $category->name = $data['name'];
        $category->description = $data['description'];
        $category->body = $data['slug'];
        $this->em->persist($category);
        $this->em->flush();

        return new RedirectResponse('/admin/category');
    }

    public function destroy(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $category = $this->repository->find($id);

        $this->em->remove($category);
        $this->em->flush();

        return new RedirectResponse('/admin/category');
    }

    private function validateCategory(array $data, int $id = null): mixed
    {
        $validator = v::key('name', v::notEmpty())
            ->key('slug', v::slug())
            ->key('description', v::notEmpty())
        ;

        try {
            $validator->assert($data);
            $result = $this->repository->findOneBy(['slug' => $data['slug']]);

            if ((!empty($result) && is_null($id)) || (!is_null($id) && !empty($result) && $result->getId() != $id)) {
                return ['slug' => 'Slug already taken'];
            }
        } catch (NestedValidationException $th) {
            return $th->getMessages();
        }

        return null;
    }
}
