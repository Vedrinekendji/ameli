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
        $admin->setEmail('admin@instagram.com');
        $admin->setPassword('admin123');
        $admin->setFullName('Administrateur');
        $admin->setUsername('admin');
        $admin->setBirthDate(new \DateTime('1990-01-01'));
        $admin->setRole('ROLE_ADMIN');

        $manager->persist($admin);
        $manager->flush();
    }
}