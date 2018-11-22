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
    /**
     * Static `role_hierarchy` is already configured in /config/packages/security.yaml
     *
     * Symfony supports only static role_hierarchy
     * https://symfony.com/doc/current/security.html#hierarchical-roles
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadSuperAdmin($manager);
        $this->loadEditorAdmin($manager);
    }

    /**
     * Admin can change password after login
     *
     * @param ObjectManager $manager
     */
    private function loadSuperAdmin(ObjectManager $manager): void
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
            // password is encrypted on prePersist doctrine event in config/services.yaml
            ->setPassword('demo')
            ->addUserGroup($group)
        ;

        $manager->persist($role);
        $manager->persist($group);
        $manager->persist($user);

        $manager->flush();
    }

    /**
     * Login with this user to confirm only 'Articles' link is visible for this user
     *
     * Confirm access denied exception for links like
     * http://127.0.0.1:8000/admin/groups/
     * http://127.0.0.1:8000/admin/users/
     *
     * Or `impersonate a user` using http://127.0.0.1:8000/admin/users/?_switch_user=editorUser@example.com
     *
     * @param ObjectManager $manager
     */
    private function loadEditorAdmin(ObjectManager $manager): void
    {
        $roleAdmin = new Role();
        $roleAdmin->setRole('ROLE_ADMIN');

        $roleEditor = new Role();
        $roleEditor->setRole('ROLE_EDITOR');

        $groupEditorAdmin = new Group();
        $groupEditorAdmin->setName('GROUP_EDITOR_ADMIN');
        $groupEditorAdmin
            ->addRole($roleAdmin)
            ->addRole($roleEditor)
        ;

        $editorUser = new User();
        $editorUser
            ->setName('editorUser')
            ->setEmail('editorUser@example.com')
            // password is encrypted on prePersist doctrine event
            ->setPassword('editorUser123')
            ->addUserGroup($groupEditorAdmin)
        ;

        $manager->persist($roleAdmin);
        $manager->persist($roleEditor);
        $manager->persist($groupEditorAdmin);
        $manager->persist($editorUser);

        $manager->flush();
    }
}
