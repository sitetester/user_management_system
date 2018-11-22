<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Group;
use App\Entity\Role;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $role = new Role();
        $role->setRole('ROLE_SUPER_ADMIN');

        $group = new Group();
        $group->setName('GROUP_SUPER_ADMIN');
        $group->addRole($role);


        $user = new User();
        $user
            ->setName('admin')
            ->setEmail('admin@example.com')
            // password is encrypted on prePersist doctrine event
            ->setPassword('demo')
            ->addUserGroup($group)
        ;

        $manager->persist($role);
        $manager->persist($group);
        $manager->persist($user);

        $manager->flush();
    }
}
