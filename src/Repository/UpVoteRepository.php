<?php

namespace App\Repository;

use App\Entity\UpVote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UpVote|null find($id, $lockMode = null, $lockVersion = null)
 * @method UpVote|null findOneBy(array $criteria, array $orderBy = null)
 * @method UpVote[]    findAll()
 * @method UpVote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UpVoteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UpVote::class);
    }

    /**
     * @return UpVote[] Returns an array of UpVote objects
     */

    public function findUpvoteByArticleIdAndUserId($articleId, $userId)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.article = :val')
            ->setParameter('val', $articleId)
            ->andWhere('u.user = :id')
            ->setParameter('id', $userId)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?UpVote
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
