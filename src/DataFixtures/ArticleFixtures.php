<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 20; $i++){
            $article = new Article();
            $article->setReference($i)
                ->setName($faker->sentence(1))
                ->setPrice($faker->randomFloat(2, 50,5000));
            $manager->persist($article);
        }
        $manager->flush();
    }
}
