<?php

namespace App\Repository;

use App\Entity\Planet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Planet>
 *
 * @method Planet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Planet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Planet[]    findAll()
 * @method Planet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlanetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Planet::class);
    }

    /**
     * retourne les planètes occupées ou libres entre la position actuelle et +/- 0.01 en longitude et latitude, 0.01 correspondant à peu près à 1km
    * @return Planet[] Returns an array of Planet objects
    */
   public function findPlanetsAroundPlayer(array $position): array
   {
        // avant 0.01 pour 1km env
        // $longitude = 0.0013;
        // $latitude = 0.0009;
        $longitude = 0.0064; // ~500m en longitude
        $latitude = 0.0045;  // ~500m en latitude
        return $this->createQueryBuilder('p')
        ->innerJoin('p.gpsPoint', 'g')
            ->andWhere('p.gpsPoint is not null')
           ->andWhere('g.longitude BETWEEN :minLongitude AND :maxLongitude')
           ->andWhere('g.latitude BETWEEN :minLatitude AND :maxLatitude')
           ->setParameter('minLongitude', $position["longitude"] - $longitude)
           ->setParameter('maxLongitude', $position["longitude"] + $longitude)
           ->setParameter('minLatitude', $position["latitude"] - $latitude)
           ->setParameter('maxLatitude', $position["latitude"] + $latitude)
        //    ->orderBy('r.id', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

    public function setGpsPointAsNull(): int
    {
        $qb = $this->createQueryBuilder('p');

        $query = $qb->update()
            ->set('p.gpsPoint', ':nullValue')
            // ->where('p.id = :id') // Optionnel : filtre si tu ne veux pas mettre à jour toute la table
            // ->setParameter('gpspoint', $newGpsPoint)
            ->setParameter('nullValue', null)
            ->getQuery();

        // Retourne le nombre de lignes modifiées
        return $query->execute();
    }

    /**
     * Retourne un tableau simple d'entiers (ex: [3, 12, 45]) des planètes n'ayant pas un utilisateur ou NPC.
     */
    public function findExcludedNumbers(): array
    {
        $results = $this->createQueryBuilder('p')
            ->select('p.id') // Remplacez 'number' par 'id' si c'est l'ID de la planète
            ->where('p.user IS NULL')
            ->getQuery()
            ->getArrayResult();

        // Convertit le résultat [['id' => 3], ['id' => 12]] en [3, 12]
        return array_column($results, 'id');
    }

    /**
     * @return Planet[] retourne les planètes occupées
     */
    public function findPlanetsByUserAndNPC(): array
   {
       return $this->createQueryBuilder('p')
           ->andWhere('p.user IS NOT NULL')
        //    ->setParameter('val', $value)
        //    ->orderBy('p.id', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }
    /**
     * @return Planet[] retourne les planètes non occupées par les utilisateurs
     */
    public function findPlanetsExceptUser(): array
   {
       return $this->createQueryBuilder('p')
           ->orWhere('p.user != 2')
           ->orWhere('p.user is null')
        //    ->setParameter('val', $value)
        //    ->orderBy('p.id', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

   /**
     * @return Planet[] retourne les planètes occupées par utilisateur
     */
    public function findPlanetsOfUser(): array
   {
       return $this->createQueryBuilder('p')
           ->orWhere('p.user = 2')
        //    ->orWhere('p.user is null')
        //    ->setParameter('val', $value)
        //    ->orderBy('p.id', 'ASC')
        //    ->setMaxResults(10)
           ->getQuery()
           ->getResult()
       ;
   }

//    /**
//     * @return Planet[] Returns an array of Planet objects
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

//    public function findOneBySomeField($value): ?Planet
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
