<?php
declare(strict_types=1);

namespace App\Service\Admin;

use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;

class AdminArticlesManager extends AdminEntityManager
{
    public function __construct(EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        parent::__construct($entityManager, $articleRepository);
    }
}
