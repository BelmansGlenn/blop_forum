<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * @return Article[] Returns an array of Article objects
     */

    public function findWithAuthor()
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.author', 'author')
            ->addSelect('author')
            ->orderBy('a.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findWithAuthorId($id)
    {
        return $this->createQueryBuilder('a')
            ->leftJoin('a.author', 'author')
            ->andWhere('a.author = :val')
            ->setParameter('val', $id)
            ->orderBy('a.createdAt', 'DESC')
            ->addSelect('author')
            ->getQuery()
            ->getResult()
            ;
    }



    public function findOneById($id): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.id = :val')
            ->leftJoin('a.author', 'author')
            ->addSelect('author')
            ->leftJoin('a.answers', 'answers')
            ->addSelect('answers')
            ->leftJoin('answers.author_com', 'a_com')
            ->addSelect('a_com')
            ->setParameter('val', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
