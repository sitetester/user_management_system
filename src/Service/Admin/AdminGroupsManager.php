<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Entity\Group;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminGroupsManager extends AdminEntityManager
{
    public function __construct(EntityManagerInterface $entityManager, GroupRepository $groupRepository)
    {
        parent::__construct($entityManager, $groupRepository);
    }

    public function deleteGroup(Group $group): void
    {
        $this->entityManager->remove($group);
        $this->entityManager->flush();
    }
}
