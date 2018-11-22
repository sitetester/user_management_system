<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminGroupsManager extends AdminEntityManager
{
    public function __construct(EntityManagerInterface $entityManager, GroupRepository $groupRepository)
    {
        parent::__construct($entityManager, $groupRepository);
    }
}
