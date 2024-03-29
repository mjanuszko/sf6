<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function save(Game $game, bool $flush = false): void
    {
        $this->getEntityManager()->persist($game);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $game, bool $flush = false): void
    {
        $this->getEntityManager()->remove($game);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllWithScore(int $score): array
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT * FROM game g
            WHERE g.score = :score
            ORDER BY g.id ASC
            ';

        $stmt = $conn->prepare($sql);

        $resultSet = $stmt->executeQuery(['score' => $score]);

        return $resultSet->fetchAllAssociative();
    }

    public function findAllWithScoreDQL(int $score): array
    {
        $qb = $this->createQueryBuilder('g')
            ->andWhere('g.score = :score')
            ->setParameter('score', $score)
            ->orderBy('g.id', 'ASC');

        $query = $qb->getQuery();

        return $query->execute();
    }

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
