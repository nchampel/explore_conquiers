<?php

namespace App\Repository;

use App\Entity\ResearchPlayer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ResearchPlayer>
 *
 * @method ResearchPlayer|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchPlayer|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchPlayer[]    findAll()
 * @method ResearchPlayer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchPlayerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchPlayer::class);
    }

//    /**
//     * @return ResearchPlayer[] Returns an array of ResearchPlayer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ResearchPlayer
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
