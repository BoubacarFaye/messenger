<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public function fetchPostsWithUsername()
    {
        $qb = $this->createQueryBuilder('p');

        $qb->select('p.id', 'p.content', 'p.media', 'p.posted_at', 'u.username', 'COUNT(DISTINCT c.id) AS commentNumber', 'COUNT(DISTINCT l.id) AS likesNumber')
            ->leftJoin('p.user_id', 'u')
            ->leftJoin('p.comments', 'c')
            ->leftJoin('p.likes', 'l')
            ->groupBy('p.id');
        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;
    }
    //    /**
    //     * @return Post[] Returns an array of Post objects
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

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
