<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    // Trouver un user par email
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }

    // Trouver un user par username
    public function findByUsername(string $username): ?User
    {
        return $this->findOneBy(['username' => $username]);
    }

    // Trouver tous les users avec le role ROLE_USER
    public function findAllClients(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', 'ROLE_USER')
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Trouver tous les admins
    public function findAllAdmins(): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.role = :role')
            ->setParameter('role', 'ROLE_ADMIN')
            ->orderBy('u.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    // Rechercher un user par nom ou username
    public function search(string $query): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.username LIKE :query OR u.fullName LIKE :query')
            ->setParameter('query', '%' . $query . '%')
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }

    // Compter le nombre total d'utilisateurs
    public function countAll(): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }
}