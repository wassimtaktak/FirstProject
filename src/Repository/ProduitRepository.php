<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Produit>
 *
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    public function save(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Produit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }



    //    /**
    //     * @return Produit[] Returns an array of Produit objects
    //     */
    public function findByCategorie($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategorieASC($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :val')
            ->setParameter('val', $value)
            ->orderBy('p.prix', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function findByCategorieDESC($value): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.categorie = :val')
            ->setParameter('val', $value)
            ->orderBy('p.prix', 'DESC')
            ->getQuery()
            ->getResult();
    }

    //    public function findOneBySomeField($value): ?Produit
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
