<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:load-data',
    description: 'Charge les données initiales (admin) si elles n\'existent pas encore',
)]
class LoadDataCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private UserRepository $userRepository,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $existing = $this->userRepository->findOneBy(['email' => '0000000000000']);

        if ($existing) {
            $io->info('L\'utilisateur admin existe déjà, rien à faire.');
            return Command::SUCCESS;
        }

        $admin = new User();
        $admin->setEmail('0000000000000');
        $admin->setRole('ROLE_ADMIN');
        $admin->setPassword('admin123');

        $this->em->persist($admin);
        $this->em->flush();

        $io->success('Utilisateur admin créé avec succès (email: 0000000000000 / mot de passe: admin123).');

        return Command::SUCCESS;
    }
}
