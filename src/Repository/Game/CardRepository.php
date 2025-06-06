<?php

namespace App\Repository\Game;

use App\Entity\Game\Card;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Card>
 */
class CardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Card::class);
    }

    public function getCardsByIds(array $ids)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getResult()
        ;
    }

    public function getNbCards(int $limit): array
    {
        return array_merge(
            $this->createQueryBuilder('i')
                 ->andWhere('i.isMain = :isMain')
                 ->setParameter('isMain', true)
                 ->setMaxResults(1)
                 ->getQuery()
                 ->getResult()
            ,
            $this->createQueryBuilder('i')
                 ->andWhere('i.isMain = :isMain')
                 ->orWhere('i.isMain IS NULL')
                 ->setParameter('isMain', false)
                 ->setMaxResults($limit - 1)
                 ->getQuery()
                 ->getResult()
            ,
        );
    }

    //    /**
    //     * @return Card[] Returns an array of Card objects
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

    //    public function findOneBySomeField($value): ?Card
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
