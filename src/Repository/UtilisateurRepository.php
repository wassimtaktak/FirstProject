<?php
namespace App\Repository;

use App\Entity\Role;
use App\Entity\Utilisateur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UtilisateurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }
    public function countUsersByRoleId(int $roleId): int
    {
        return $this->createQueryBuilder('u')
            ->select('COUNT(u.id)')
            ->where('u.idrole = :roleId')
            ->setParameter('roleId', $roleId)
            ->getQuery()
            ->getSingleScalarResult();
    }
}


