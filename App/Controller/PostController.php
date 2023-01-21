<?php

namespace App\Controller;

use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use App\Entities\Post;
use Laminas\Diactoros\Response\RedirectResponse;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;
use App\Entities\Category;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Parameter;
use Core\View;

class PostController
{
    private $em;
    private $repository;

    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
        $this->repository = $manager->getRepository(Post::class);
        date_default_timezone_set('America/Santo_Domingo');
    }

    public function index(ServerRequestInterface $request): View
    {
        // Search method
        $data = $request->getQueryParams();

        if (null != $data) {
            $q = $this->em->createQueryBuilder();
            $q->select('p')->
                from('App\Entities\Post', 'p')->
                where($q->expr()->orX(
                    $q->expr()->like('p.title', ':title'),
                    $q->expr()->like('p.description', ':description'),
                    $q->expr()->like('p.slug', ':slug'),
                    $q->expr()->like('p.body', ':body')
                ));
            // Bind parameters for SQL Query
            $q->setParameters(new ArrayCollection([
                new Parameter('title', '%'.trim($data['search']).'%'),
                new Parameter('description', '%'.trim($data['search']).'%'),
                new Parameter('slug', '%'.trim($data['search']).'%'),
                new Parameter('body', '%'.trim($data['search']).'%'),
            ]));

            if (null != $data['order']) {
                $q->orderBy('p.created', $data['order']);
            }

            $result = $q->getQuery()->getResult();

            return new View('post/index', compact('result'));
        }

        $posts = $this->repository->findby([], ['created' => 'DESC']);

        return new View('post/index', compact('posts'));
    }

    public function create(ServerRequestInterface $request): View
    {
        $categories = $this->em->getRepository(Category::class)->findAll();

        return new View('post/create', compact('categories'));
    }

    public function store(ServerRequestInterface $request): View|ResponseInterface
    {
        $data = $request->getParsedBody();
        $errors = $this->validatePost($data);
        if (!is_null($errors)) {
            $post = (object) $data;
            $post->category = $this->em->getRepository(Category::class)->find($data['category']);
            $categories = $this->em->getRepository(Category::class)->findAll();
            $post->publishDate = null;

            return new View('post/create', compact('post', 'errors', 'categories'));
        }
        $category = $this->em->getRepository(Category::class)->find($data['category']);
        $date = \DateTime::createFromFormat('Y-m-d\\TH:i', $data['publishDate']);
        $post = new Post();
        $post->category = $category;
        $post->title = $data['title'];
        $post->description = $data['description'];
        $post->body = $data['body'];
        $post->slug = $data['slug'];
        $post->publishDate = $date ? $date : null;
        $this->em->persist($post);
        $this->em->flush();

        return new RedirectResponse('/admin/post');
    }

    public function view(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $post = $this->repository->find($id);
        if (is_null($post)) {
            return NotFound();
        }

        return new View('post/post', compact('post'));
    }

    public function edit(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');

        $post = $this->repository->find($id);
        $categories = $this->em->getRepository(Category::class)->findAll();
        if (is_null($post)) {
            return NotFound();
        }

        return new View('post/edit', compact('post', 'categories'));
    }

    public function update(ServerRequestInterface $request): View|ResponseInterface
    {
        $id = $request->getAttribute('id');
        $data = $request->getParsedBody();
        $date = \DateTime::createFromFormat('Y-m-d\\TH:i', $data['publishDate']);
        $errors = $this->validatePost($data, $id);
        if (!is_null($errors)) {
            $post = (object) $data;
            $post->category = $this->em->getRepository(Category::class)->find($data['category']);
            $post->publishDate = null;
            $categories = $this->em->getRepository(Category::class)->findAll();

            return new View('post/edit', compact('post', 'errors', 'categories'));
        }
        $post = $this->repository->find($id);
        $category = $this->em->getRepository(Category::class)->find($data['category']);
        $post->category = $category;
        $post->title = $data['title'];
        $post->description = $data['description'];
        $post->body = $data['body'];
        $post->slug = $data['slug'];
        $post->publishDate = $date;
        $this->em->persist($post);
        $this->em->flush();

        return new RedirectResponse('/admin/post');
    }

    public function destroy(ServerRequestInterface $request): ResponseInterface
    {
        $id = $request->getAttribute('id');

        $post = $this->repository->find($id);

        $this->em->remove($post);
        $this->em->flush();

        return new RedirectResponse('/admin/post');
    }

    private function validatePost(array $data, int $id = null): mixed
    {
        $validator = v::key('title', v::notEmpty())
            ->key('slug', v::slug())
            ->key('body', v::notEmpty())
            ->key('category', v::notEmpty())
            ->key('description', v::notEmpty())
        ;

        try {
            $validator->assert($data);

            $result = $this->repository->findOneBy(['slug' => $data['slug']]);
            $category = $this->em->getRepository(Category::class)->find($data['category']);
            $date = strtotime($data['publishDate']);
            $time = time();

            if ((!empty($result) && is_null($id)) || (!is_null($id) && !empty($result) && $result->getId() != $id)) {
                return ['slug' => 'Slug already taken'];
            }
            if (empty($category)) {
                return ['category' => 'Category does not exists'];
            }
            if ($date < $time && !empty($date)) {
                return ['publishDate' => 'You cant publish before the current date'];
            }
        } catch (NestedValidationException $th) {
            return $th->getMessages();
        }

        return null;
    }
}
