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
 public function getStatistics()
{
    $totalReclamations = $this->createQueryBuilder('r')
        ->select('COUNT(r)')
        ->getQuery()
        ->getSingleScalarResult();

    $sujetsCount = $this->createQueryBuilder('r')
        ->select('r.sujet, COUNT(r) as count')
        ->groupBy('r.sujet')
        ->getQuery()
        ->getResult();

    $statisticsByDay = $this->createQueryBuilder('r')
        ->select('COUNT(r) as count, DAY(r.dateCreation) as day')
        ->groupBy('day')
        ->getQuery()
        ->getResult();

    $statisticsByWeek = $this->createQueryBuilder('r')
        ->select('COUNT(r) as count, WEEK(r.dateCreation) as week')
        ->groupBy('week')
        ->getQuery()
        ->getResult();

    $statisticsByMonth = $this->createQueryBuilder('r')
        ->select('COUNT(r) as count, MONTH(r.dateCreation) as month')
        ->groupBy('month')
        ->getQuery()
        ->getResult();

    $statisticsByYear = $this->createQueryBuilder('r')
        ->select('COUNT(r) as count, YEAR(r.dateCreation) as year')
        ->groupBy('year')
        ->getQuery()
        ->getResult();

    return [
        'totalReclamations' => $totalReclamations,
        'sujetsCount' => $sujetsCount,
        'statisticsByDay' => $statisticsByDay,
        'statisticsByWeek' => $statisticsByWeek,
        'statisticsByMonth' => $statisticsByMonth,
        'statisticsByYear' => $statisticsByYear,
    ];
}


}
