<?php

namespace App\Repository;

use App\Entity\TastingRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TastingRoom|null find($id, $lockMode = null, $lockVersion = null)
 * @method TastingRoom|null findOneBy(array $criteria, array $orderBy = null)
 * @method TastingRoom[]    findAll()
 * @method TastingRoom[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TastingRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TastingRoom::class);
    }

    // /**
    //  * @return TastingRoom[] Returns an array of TastingRoom objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TastingRoom
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
