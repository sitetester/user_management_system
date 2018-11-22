<?php
declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class ArticlesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $manager->persist(
            (new Article())
                ->setTitle('Test Article 1')
                ->setContents('test articles contents')
        );

        $manager->flush();
    }
}
