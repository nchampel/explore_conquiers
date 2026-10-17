<?php

namespace App\Repository;

use App\Entity\Planet;
use App\Entity\PlanetBuilding;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PlanetBuilding>
 *
 * @method PlanetBuilding|null find($id, $lockMode = null, $lockVersion = null)
 * @method PlanetBuilding|null findOneBy(array $criteria, array $orderBy = null)
 * @method PlanetBuilding[]    findAll()
 * @method PlanetBuilding[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetBuildingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PlanetBuilding::class);
    }

       /**
        * @return PlanetBuilding[] retourne les bâtiments
        */
        public function findBuildingProductionByPlanet(Planet $planet): array
        {
            return $this->createQueryBuilder('p')
                ->andWhere('p.building IN (1, 2, 3)')
                ->andWhere('p.planet = :planet')
                ->setParameter('planet', $planet)
                // ->orderBy('p.id', 'ASC')
                // ->setMaxResults(10)
                ->getQuery()
                ->getResult()
            ;
        }

//    /**
//     * @return PlanetBuilding[] Returns an array of PlanetBuilding objects
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

//    public function findOneBySomeField($value): ?PlanetBuilding
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
