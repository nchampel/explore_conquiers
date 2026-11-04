<?php

namespace App\Repository;

use App\Entity\GpsPoint;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GpsPoint>
 *
 * @method GpsPoint|null find($id, $lockMode = null, $lockVersion = null)
 * @method GpsPoint|null findOneBy(array $criteria, array $orderBy = null)
 * @method GpsPoint[]    findAll()
 * @method GpsPoint[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GpsPointRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GpsPoint::class);
    }

//    /**
//     * @return GpsPoint[] Returns an array of GpsPoint objects
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

//    public function findOneBySomeField($value): ?GpsPoint
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
