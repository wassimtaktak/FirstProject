<?php

namespace App\Repository;

use App\Entity\Partie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Partie>
 *
 * @method Partie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partie[]    findAll()
 * @method Partie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Partie::class);
    }

    public function add(Partie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Partie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function findByIdTournoi($idTournoi)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.idtournoi = :idTournoi')
            ->setParameter('idTournoi', $idTournoi)
            ->getQuery()
            ->getResult();
    }
    public function checkPartiesExistForPhase(string $phase, int $idTournoi): bool
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p.id)')
            ->innerJoin('p.idtournoi', 't')
            ->where('p.phase = :phase')
            ->andWhere('t.id = :idTournoi')
            ->setParameter('phase', $phase)
            ->setParameter('idTournoi', $idTournoi)
            ->getQuery();

        $count = $query->getSingleScalarResult();

        return $count > 0;
    }
    public function findEquipesGagnantesByPhase($idTournoi, $phase)
{
    $qb = $this->createQueryBuilder('p');
    $qb->select('CASE WHEN p.scoreequipe1 > p.scoreequipe2 THEN e1.id ELSE e2.id END AS equipeGagnanteId')
       ->innerJoin('p.equipe1id', 'e1')
       ->innerJoin('p.equipe2id', 'e2')
       ->andWhere('p.idtournoi = :idTournoi')
       ->andWhere('p.phase = :phase')
       ->setParameter('idTournoi', $idTournoi)
       ->setParameter('phase', $phase);

    $result = $qb->getQuery()->getResult();

    $equipesGagnantesIds = array_column($result, 'equipeGagnanteId');

    return $equipesGagnantesIds;
}
    public function hasUnupdatedParties($idTournoi):bool
{
    return $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->andWhere('p.idtournoi = :idTournoi')
        ->andWhere('p.updated = :updated')
        ->setParameter('idTournoi', $idTournoi)
        ->setParameter('updated', false)
        ->getQuery()
        ->getSingleScalarResult() > 0;
}
public function EquipeInParties($teamId): bool
{
    $qb = $this->createQueryBuilder('p')
        ->select('COUNT(p.id)')
        ->where('p.equipe1id = :teamId')
        ->orWhere('p.equipe2id = :teamId')
        ->setParameter('teamId', $teamId)
        ->getQuery();

    $count = $qb->getSingleScalarResult();

    return $count > 0;
}


    


//    /**
//     * @return Pays[] Returns an array of Pays objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Pays
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}