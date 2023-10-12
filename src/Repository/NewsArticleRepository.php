<?php

namespace App\Repository;

use App\Entity\NewsArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<NewsArticle>
 *
 * @method NewsArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method NewsArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method NewsArticle[]    findAll()
 * @method NewsArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NewsArticle::class);
    }

    public function save(NewsArticle $newsArticle): void
    {
        $this->_em->persist($newsArticle);
        $this->_em->flush();
    }

    public function delete(NewsArticle $newsArticle): void
    {
        $this->_em->remove($newsArticle);
        $this->_em->flush();
    }

    public function deleteBeId(int $id): void
    {
        $newsArticle = $this->find($id);
        $this->_em->remove($newsArticle);
        $this->_em->flush();
    }

//    /**
//     * @return NewsArticle[] Returns an array of NewsArticle objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('n.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?NewsArticle
//    {
//        return $this->createQueryBuilder('n')
//            ->andWhere('n.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
