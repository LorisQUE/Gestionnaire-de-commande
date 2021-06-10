<?php

namespace App\Repository;

use App\Entity\GammeRealisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GammeRealisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method GammeRealisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method GammeRealisation[]    findAll()
 * @method GammeRealisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GammeRealisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GammeRealisation::class);
    }

    // /**
    //  * @return GammeRealisation[] Returns an array of GammeRealisation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GammeRealisation
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
