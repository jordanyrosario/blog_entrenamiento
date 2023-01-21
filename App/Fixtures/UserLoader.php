<?php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entities\User;

class UserLoader extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return mixed
     */
    public function getDependencies()
    {
        return [
            RoleLoader::class,
        ];
    }

    /**
     * Load data fixtures with the passed EntityManager.
     *
     * @return mixed
     */
    public function load(Doctrine\Persistence\ObjectManager $manager)
    {
        $user = new User();
        $user->name = 'Admin';
        $user->lastName = 'Admin';
        $user->email = 'admin@mailinator.com';
        $user->password = password_hash('Pa$$w0rd!', PASSWORD_DEFAULT);
        $user->getAllRoles()->add(
            $this->getReference('admin-role')
        );

        $manager->persist($user);
        $manager->flush();
        $this->addReference('admin-user', $user);
    }
}
