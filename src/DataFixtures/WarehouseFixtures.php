<?php

namespace App\DataFixtures;

use App\Entity\Warehouse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class WarehouseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        for($i = 1; $i <= 3; $i++){
            $warehouse = new Warehouse();
            $warehouse->setName($faker->city);
            $manager->persist($warehouse);
        }
        $manager->flush();
    }
}
