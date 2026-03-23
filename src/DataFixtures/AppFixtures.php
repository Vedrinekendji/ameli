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
        $admin->setEmail('admin@ameli.com');
        $admin->setPassword('admin123');
        $admin->setRole('ROLE_ADMIN');

        $manager->persist($admin);
        $manager->flush();
    }
}