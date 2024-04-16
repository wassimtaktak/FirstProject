<?php
namespace App\Repository;

use App\Entity\Reclamations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ReclamationsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reclamations::class);
    }

    /**
     * Récupère tous les sujets des réclamations.
     *
     * @return array
     */
    public function findAllSujets(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.sujet')
            ->distinct()
            ->orderBy('r.sujet', 'ASC')
            ->getQuery()
            ->getResult();
    }
    public function findAllStatuses(): array
    {
        return $this->createQueryBuilder('r')
            ->select('DISTINCT r.status')
            ->getQuery()
            ->getResult();
    }
    
}
