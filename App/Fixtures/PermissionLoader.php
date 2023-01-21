<?php

use Doctrine\Persistence\ObjectManager;
use App\Entities\Permission;
use Doctrine\Common\DataFixtures\AbstractFixture;

class PermissionLoader extends AbstractFixture
{
    private array $permissions = [
        'post.list',
        'post.show',
        'post.edit',
        'post.delete',
        'post.update',
        'post.create',
        'post.publish',
        'category.list',
        'category.show',
        'category.edit',
        'category.update',
        'category.create',
        'category.delete',
        'user.list',
        'user.show',
        'user.edit',
        'user.update',
        'user.create',
        'user.delete',
        'role.list',
        'role.show',
        'role.edit',
        'role.update',
        'role.create',
        'role.delete',
        'post.deleteComment',
    ];

    public function load(ObjectManager $manager): void
    {
        foreach ($this->permissions as $name) {
            $permission = new Permission();
            $permission->name = $name;
            $manager->persist($permission);
        }

        $manager->flush();
        $manager->clear();
    }
}
