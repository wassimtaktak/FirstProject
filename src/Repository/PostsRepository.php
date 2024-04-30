<?php

namespace App\Repository;

use App\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Utilisateur::class);
    }

    /**
 * Find users who have written posts for a given forum.
 *
 * @param int $forumId The ID of the forum
 * @return array The list of users with the count of their posts
 */
public function findUsersByForum($forumId)
{
    return $this->createQueryBuilder('u')
        ->select('u', 'COUNT(p.id) as postCount')
        ->leftJoin('App\Entity\Post', 'p', 'WITH', 'p.idUser = u.id')
        ->where('p.idForum = :forumId')
        ->setParameter('forumId', $forumId)
        ->groupBy('u.id')
        ->getQuery()
        ->getResult();
}

    /**
     * Count the number of posts for each user
     *
     * @return array
     */
    public function countPostsPerUser(): array
    {
        return $this->createQueryBuilder('u')
            ->select('u.username', 'COUNT(p.id) as postCount')
            ->leftJoin('App\Entity\Post', 'p', 'WITH', 'p.idUser = u.id')
            ->groupBy('u.id')
            ->getQuery()
            ->getResult();
    }
    /**
     * Find the top N most liked posts.
     *
     * @param int $limit The maximum number of posts to retrieve
     * @return array The top N most liked posts
     */
    public function findTopLikedPosts(int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nbLike', 'DESC') // Assuming 'nbLike' is the field storing the number of likes
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
