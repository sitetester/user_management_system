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
        for ($i = 0; $i < 10; $i++) {
            $article = new Article();
            $article
                ->setTitle('Test Article #' . $i)
                ->setContents('Test article #' . $i . ' contents')
            ;

            $manager->persist($article);
        }

        $manager->flush();
    }
}
