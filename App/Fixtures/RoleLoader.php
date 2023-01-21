<?php

use App\Entities\Permission;
use App\Entities\Role;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RoleLoader extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $default = new Role();
        $default->name = 'user';
        $permission = [];
        $permission = $manager->getRepository(Permission::class)->findOneBy(['name' => 'post.list']);
        $default->getPermissions()->add($permission);
        $permission = $manager->getRepository(Permission::class)->findOneBy(['name' => 'post.show']);
        $default->getPermissions()->add($permission);
        $permission = $manager->getRepository(Permission::class)->findOneBy(['name' => 'post.create']);
        $default->getPermissions()->add($permission);

        $default->getPermissions()->add($permission);

        $superAdmin = new Role();
        $superAdmin->name = 'superAdmin';
        $permissions = $manager->getRepository(Permission::class)->findAll();
        foreach ($permissions  as $permission) {
            $superAdmin->getPermissions()->add($permission);
        }

        $manager->persist($superAdmin);
        $manager->persist($default);
        $manager->flush();
        $this->addReference('admin-role', $superAdmin);
    }

    public function getDependencies()
    {
        return [
            PermissionLoader::class,
        ];
    }
}
