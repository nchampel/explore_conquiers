<?php

namespace App\Repository;

use App\Entity\PlanetDefence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanetDefence>
 *
 * @method PlanetDefence|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanetDefence|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanetDefence[]    findAll()
 * @method PlanetDefence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetDefenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanetDefence::class);
    }

//    /**
//     * @return PlanetDefence[] Returns an array of PlanetDefence objects
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

//    public function findOneBySomeField($value): ?PlanetDefence
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
