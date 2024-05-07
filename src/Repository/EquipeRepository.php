<?php

namespace App\Repository;

use App\Entity\Equipe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Membre;


/**
 * @extends ServiceEntityRepository<Equipe>
 *
 * @method Equipe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Equipe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Equipe[]    findAll()
 * @method Equipe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EquipeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Equipe::class);
    }

    public function add(Equipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Equipe $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    
    public function findByIdTournoi($idTournoi)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.idtournoi = :idTournoi')
            ->setParameter('idTournoi', $idTournoi)
            ->orderBy('e.points', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function getMyTeamForTournament(int $user, int $tournamentId) : ?Equipe
    {    
        return $this->createQueryBuilder('e')
            ->join(Membre::class, 'm', 'WITH', 'm.idequipe = e.id')
            ->andWhere('m.iduser = :userId')
            ->andWhere('e.idtournoi = :tournamentId')
            ->setParameter('userId', $user)
            ->setParameter('tournamentId', $tournamentId)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function isTeamNameExistsForTournament(string $teamName, int $idTournoi): bool
{
    $query = $this->createQueryBuilder('e')
        ->select('COUNT(e.id)')
        ->where('e.nom = :teamName')
        ->andWhere('e.idtournoi = :idTournoi')
        ->setParameter('teamName', $teamName)
        ->setParameter('idTournoi', $idTournoi)
        ->getQuery();

    $count = $query->getSingleScalarResult();

    return $count > 0;
}
    public function countTeamsInTournament(Tournoi $tournoi): int
    {
        return $this->createQueryBuilder('e')
            ->select('COUNT(e.id)')
            ->andWhere('e.idtournoi = :tournoiId')
            ->setParameter('tournoiId', $tournoi->getId())
            ->getQuery()
            ->getSingleScalarResult();
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