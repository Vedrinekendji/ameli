<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('0000000000000');
        $admin->setRole('ROLE_ADMIN');
        $admin->setPassword('admin123');

        $manager->persist($admin);
        $manager->flush();
    }
}
