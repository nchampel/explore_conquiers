<?php

namespace App\Repository;

use App\Entity\PlanetVessel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanetVessel>
 *
 * @method PlanetVessel|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanetVessel|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanetVessel[]    findAll()
 * @method PlanetVessel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetVesselRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanetVessel::class);
    }

//    /**
//     * @return PlanetVessel[] Returns an array of PlanetVessel objects
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

//    public function findOneBySomeField($value): ?PlanetVessel
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
