<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $password = password_hash("admin", PASSWORD_BCRYPT);
        $user = new User();
        $user->setLogin('admin')
            ->setPassword($password);
        $manager->persist($user);
        $manager->flush();
    }
}
