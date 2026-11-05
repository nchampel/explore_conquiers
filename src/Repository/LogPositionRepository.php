<?php

namespace App\Repository;

use App\Entity\LogPosition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<LogPosition>
 *
 * @method LogPosition|null find($id, $lockMode = null, $lockVersion = null)
 * @method LogPosition|null findOneBy(array $criteria, array $orderBy = null)
 * @method LogPosition[]    findAll()
 * @method LogPosition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LogPositionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LogPosition::class);
    }

//    /**
//     * @return LogPosition[] Returns an array of LogPosition objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?LogPosition
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
