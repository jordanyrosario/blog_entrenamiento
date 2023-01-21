<?php

use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use App\Entities\Category;

class CategoryLoader extends AbstractFixture
{
    private $categories = [
        ['name' => 'opinion',
            'slug' => 'opinion',
            'description' => 'Personal opinion'],
        ['name' => 'Global',
            'slug' => 'global',
            'description' => 'Global topic'],
        ['name' => 'Country',
            'slug' => 'country',
            'description' => 'Country topic'],
        ['name' => 'Financial',
            'slug' => 'financial',
            'description' => 'Financial topic'],
        ['name' => 'Sport',
            'slug' => 'sport',
            'description' => 'Sport topic'],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->categories as $category) {
            $newCategory = new Category();

            $newCategory->name = $category['name'];
            $newCategory->slug = $category['slug'];
            $newCategory->description = $category['description'];

            $manager->persist($newCategory);
            $this->addReference('category-'.$category['name'], $newCategory);
        }

        $manager->flush();
        $manager->clear();
    }
}
