<?php 
namespace App\Repository;

use App\Entity\Tournoi;
use App\Entity\Jeu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TournoiRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tournoi::class);
    }
    public function findFilteredAndSortedTournois(?string $type): array
    {
        $currentDate = new \DateTime();

        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.jour > :currentDate')
            ->setParameter('currentDate', $currentDate);

        switch ($type) {
            case 'date_desc':
                $queryBuilder->orderBy('t.jour', 'DESC');
                break;
            case 'date_asc':
                $queryBuilder->orderBy('t.jour', 'ASC');
                break;
            default:
                $queryBuilder->orderBy('t.jour', 'ASC');
        }

        return $queryBuilder->getQuery()->getResult();
    }
    public function SortedTournois(): array
    {
        $currentDate = new \DateTime();

        $queryBuilder = $this->createQueryBuilder('t')
            ->where('t.jour > :currentDate')
            ->setParameter('currentDate', $currentDate)
            ->orderBy('t.jour', 'ASC');

        return $queryBuilder->getQuery()->getResult();
    }
    public function filteredByGames(?int $idjeu): array
    {
        $currentDate = new \DateTime();
        return $this->createQueryBuilder('t')
            ->andWhere('t.idjeu = :idjeu')
            ->andWhere('t.jour > :currentDate')
            ->setParameters([
                'idjeu' => $idjeu,
                'currentDate' => $currentDate,
            ])
            ->orderBy('t.jour', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
    public function jeuTournoi($jeuId): bool
    {
        $qb = $this->createQueryBuilder('p')
            ->select('COUNT(p.idjeu)')
            ->where('p.idjeu = :jeuId')
            ->setParameter('jeuId', $jeuId)
            ->getQuery();

        $count = $qb->getSingleScalarResult();

        return $count > 0;
    }
    public function countTournoisByJeu(): array
    {
        return $this->createQueryBuilder('t')
            ->select('COUNT(t.id) AS nombreTournois', 'j.nom AS jeuNom')
            ->join('t.idjeu', 'j')
            ->groupBy('j.nom')
            ->orderBy('nombreTournois', 'DESC')
            ->getQuery()
            ->getResult();
    }
    
}
