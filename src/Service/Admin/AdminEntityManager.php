<?php
declare(strict_types=1);

namespace App\Service\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

class AdminEntityManager
{
    protected $entityManager;
    protected $entityRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        EntityRepository $entityRepository
    ) {
        $this->entityManager = $entityManager;
        $this->entityRepository = $entityRepository;
    }

    public function getAll(): ?array
    {
        return $this->entityRepository->findAll();
    }

    public function addOrUpdate(object $entity): void
    {
        if ($entity->getId() === null) {
            $this->entityManager->persist($entity);
        }

        $this->entityManager->flush();
    }

    public function delete(object $entity): void
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

    public function getById(int $id)
    {
        return $this->entityRepository->find($id);
    }
}
