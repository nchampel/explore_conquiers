<?php

namespace App\Repository;

use App\Entity\PlanetResource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanetResource>
 *
 * @method PlanetResource|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanetResource|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanetResource[]    findAll()
 * @method PlanetResource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetResourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanetResource::class);
    }

//    /**
//     * @return PlanetResource[] Returns an array of PlanetResource objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PlanetResource
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
