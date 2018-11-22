<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AdminUsersManager extends AdminEntityManager
{
    private $eventDispatcher;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        EventDispatcherInterface $eventDispatcher
    ) {
        parent::__construct($entityManager, $userRepository);

        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @param User $entity
     */
    public function save($entity): void
    {
        parent::save($entity);

        $event = new UserRegisteredEvent($entity);
        $this->eventDispatcher->dispatch(UserRegisteredEvent::NAME, $event);
    }

    public function deleteById(int $id): void
    {
        /** @var User $user */
        $user = $this->getById($id);
        if ($user) {
            $this->deleteUserGroups($user);

            $this->entityManager->remove($user);
            $this->entityManager->flush();
        }
    }

    /**
     * https://www.doctrine-project.org/projects/doctrine-orm/en/2.6/reference/working-with-associations.html#removing-associations
     * ```You need to call $em->flush() to make persist these changes in the database permanently.```
     *
     * @param User $user
     */
    private function deleteUserGroups(User $user): void
    {
        foreach ($user->getUserGroups() as $group) {
            $user->removeUserGroup($group);
        }

        $this->entityManager->flush();
    }
}
