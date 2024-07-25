<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Comment>
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }
    public function fetchComments(int $postId)
    {
        $qb = $this->createQueryBuilder('c');

        $qb->select('c.id', 'c.content', 'c.posted_at', 'u.username', 'COUNT(DISTINCT l.id) AS likesNumber')
            ->leftJoin('c.user_id', 'u')
            ->leftJoin('c.likes', 'l')
            ->groupBy('c.id')
            ->where('c.post_id = :post')
            ->setParameter('post', $postId);
        $query = $qb->getQuery();
        $results = $query->getResult();

        return $results;
    }
    //    /**
    //     * @return Comment[] Returns an array of Comment objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Comment
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
