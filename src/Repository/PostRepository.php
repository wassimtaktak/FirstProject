<?php

namespace App\Repository;

use App\Entity\Post;
use App\Entity\Utilisateur;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    
    /**
     * Find the top N most liked posts.
     *
     * @param int $limit The maximum number of posts to retrieve
     * @return array The top N most liked posts
     */
    public function findTopLikedPosts(int $limit,int $forumId): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nbLike', 'DESC') // Assuming 'nbLike' is the field storing the number of likes
            ->where('p.idForum = :forumId')
            ->setParameter('forumId', $forumId)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
    public function findTopLikedallPosts(int $limit): array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.nbLike', 'DESC') // Assuming 'nbLike' is the field storing the number of likes
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
