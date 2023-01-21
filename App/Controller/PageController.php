<?php

namespace App\Controller;

use Core\Exceptions\NotFoundException;
use Psr\Http\Message\ResponseInterface;
use Doctrine\ORM\EntityManager;
use App\Entities\Post;
use Core\View;
use Psr\Http\Message\ServerRequestInterface;
use App\Entities\Category;
use App\Entities\Comments;
use App\Entities\User;
use Laminas\Diactoros\Response\RedirectResponse;
use Mezzio\Authentication\UserInterface;
use Doctrine\ORM\Query\Parameter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PageController
{
    private $em;
    private $repository;
    private $categoryRepository;
    private $userRepository;
    private $commentRepository;

    public function __construct(EntityManager $manager)
    {
        $this->em = $manager;
        $this->repository = $manager->getRepository(Post::class);
        $this->categoryRepository = $manager->getRepository(Category::class);
        $this->userRepository = $manager->getRepository(User::class);
        $this->commentRepository = $manager->getRepository(Comments::class);
    }

    public function index(ServerRequestInterface $request): View
    {
        $page = 1;
        $data = $request->getQueryParams();

        $query = $this->repository->createQueryBuilder('p')->where('p.publishDate < :time')
            ->setParameter('time', date('Y-m-d H:i:s', time()))
            ->getQuery()
        ;
        $pageSize = 5;
        $paginator = new Paginator($query, $fetchJoinCollection = true);
        $totalPosts = count($paginator);
        $pageCount = ceil($totalPosts / $pageSize);
        if (isset($data['page']) && is_numeric($data['page']) && $data['page'] <= $pageCount && $data['page'] > 0) {
            $page = $data['page'];
        }
        $posts = $paginator->getquery()->setFirstResult($pageSize * ($page - 1))->setMaxResults($pageSize)->execute();
        $categories = $this->categoryRepository->findAll();

        return new View('index', compact('posts', 'categories', 'pageCount', 'page'));
    }

    public function show(ServerRequestInterface $request): View|ResponseInterface
    {
        $session = $request->getAttribute('session');
        $userSession = $session->get(UserInterface::class);

      

        $qb = $this->em->createQueryBuilder();

        $slug = $request->getAttribute('slug');

        $post = $this->repository->findOneBy(['slug' => $slug]);
        if (is_null($post)) {
            return NotFound();
        }

        $qb->select('c')->from('App\Entities\Comments', 'c')->where('c.post = :postid')->orderBy('c.id')->orderBy('c.parent');

        $qb->setParameter('postid', $post->getId()); // Get the comments of the post

        $comments = $qb->getQuery()->execute();

        $categories = $this->categoryRepository->findAll();

        $postCategory = $this->categoryRepository->find($post->category);

        return new View('post', compact('post', 'categories', 'postCategory', 'comments'));
    }

    public function comment(ServerRequestInterface $request): ResponseInterface
    {
        $session = $request->getAttribute('session');
        $userSession = $session->get(UserInterface::class);

        $user = $this->userRepository->findOneBy(['email' => $userSession['username']]);

        $slug = $request->getAttribute('slug');

        $post = $this->repository->findOneBy(['slug' => $slug]);
        if (is_null($post)) {
            return NotFound();
        }

        $data = $request->getParsedBody();

        $comment = new Comments();
        $comment->body = $data['body'];
        $comment->post = $post;
        $comment->user = $user;
        if (isset($data['parent']) && null != $data['parent']) {
            $parent = $this->commentRepository->findOneBy(['id' => $data['parent']]);
            $parent->addChild($comment);
        }

        $this->em->persist($comment);
        $this->em->flush();

        return new RedirectResponse('/view/'.$slug);
    }

    public function deleteComment(ServerRequestInterface $request): ResponseInterface
    {
        $data = $request->getParsedBody();

        $id = $data['id'];

        $slug = $request->getAttribute('slug');

        $comment = $this->commentRepository->find($id);

        $comment->isVisible = false;

        $this->em->persist($comment);

        $this->em->flush();

        return new RedirectResponse('/view/'.$slug);
    }

    public function search(ServerRequestInterface $request): View
    {
        $categories = $this->categoryRepository->findAll();

        $data = $request->getQueryParams();

        // Initialize variables
        $search = null != array_key_exists('search', $data) ? $data['search'] : '';
        $order = null != array_key_exists('order', $data) ? $data['order'] : null;
        $category = null != array_key_exists('category', $data) ? $data['category'] : null;

        $errors = $this->validateSearch($data); // Find if there any errors in the Params
        if (!is_null($errors)) {
            // $categories = $this->em->getRepository(Category::class)->findAll();
            // return new View('search', compact('errors', 'categories', 'data'));
            $search = '';
            $order = null;
            $category = null;
        }

        // Search method
        if (null != $data) {
            $q = $this->em->createQueryBuilder();
            $q->select('p')->
              from('App\Entities\Post', 'p')->
              where($q->expr()->orX(
                  $q->expr()->like('p.title', ':title'),
                  $q->expr()->like('p.description', ':description'),
                  $q->expr()->like('p.slug', ':slug'),
                  $q->expr()->like('p.body', ':body'),
              ));

            // Bind parameters for SQL Query
            $q->setParameters(new ArrayCollection([
                new Parameter('title', '%'.trim($search).'%'),
                new Parameter('description', '%'.trim($search).'%'),
                new Parameter('slug', '%'.trim($search).'%'),
                new Parameter('body', '%'.trim($search).'%'),
            ]));

            if (null != $category) {
                $newCategory = $this->em->getRepository(Category::class)->findBy(['name' => $category]);
                $q->andWhere($q->expr()->like('IDENTITY(p.category)', ':category'));
                $q->setParameter('category', $newCategory);
            }

            if (null != $order) {
                $q->orderBy('p.created', $order);
            }

            $result = $q->getQuery()->getResult();

            return new View('search', compact('result', 'categories', 'data'));
        }

        $posts = $this->repository->findby([], ['created' => 'DESC']);

        return new View('search', compact('posts', 'categories'));
    }

    public function validateSearch($data): mixed
    {
        if (array_key_exists('order', $data)) {
            if ('ASC' != $data['order'] && 'DESC' != $data['order'] && '' != $data['order']) {
                return ['order' => 'This is not a valid order'];
            }
        }

        if (array_key_exists('category', $data)) {
            $categories = $this->em->createQuery('SELECT c.name from App\Entities\\Category c')->getSingleColumnResult();
            if (!in_array($data['category'], $categories) && '' != $data['category']) {
                return ['category' => 'This category does not exist.'];
            }
        }

        return null;
    }
}
