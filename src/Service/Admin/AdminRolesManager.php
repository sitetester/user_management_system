<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminRolesManager extends AdminEntityManager
{
    public function __construct(EntityManagerInterface $entityManager, RoleRepository $roleRepository)
    {
        parent::__construct($entityManager, $roleRepository);
    }
}
