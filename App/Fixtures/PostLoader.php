<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entities\Post;

class postLoader extends AbstractFixture implements DependentFixtureInterface
{
    private $posts = [
        [
            'title' => 'Dolor en la barriga',
            'slug' => 'dolorenbarriga',
            'description' => 'Que pienso del dolor de barriga',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'category' => 'opinion',
        ],
        [
            'title' => 'La pelota dominicana',
            'slug' => 'pelotadominicana',
            'description' => 'Que pienso del baseball',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'category' => 'Sport',
        ],
        [
            'title' => 'Salud en la cocina',
            'slug' => 'saludencocina',
            'description' => 'Que pienso de la comida',
            'body' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'category' => 'Global',
        ],
    ];

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return mixed
     */
    public function getDependencies()
    {
        return [
            UserLoader::class,
            CategoryLoader::class,
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @return mixed
     */
    public function load(Doctrine\Persistence\ObjectManager $manager)
    {
        foreach ($this->posts as $post) {
            $newPost = new Post();
            $newPost->title = $post['title'];
            $newPost->slug = $post['slug'];
            $newPost->description = $post['description'];
            $newPost->body = $post['body'];
            $newPost->publishDate = new DateTime();
            $newPost->category = $this->getReference('category-'.$post['category']);
            $newPost->user = $this->getReference('admin-user');
            $manager->persist($newPost);
        }

        $manager->flush();
        $manager->clear();
    }
}
