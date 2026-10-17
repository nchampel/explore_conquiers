<?php

namespace App\Repository;

use App\Entity\Defence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Defence>
 *
 * @method Defence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Defence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Defence[]    findAll()
 * @method Defence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DefenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Defence::class);
    }

//    /**
//     * @return Defence[] Returns an array of Defence objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Defence
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
