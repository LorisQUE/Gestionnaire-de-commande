<?php

namespace App\Repository;

use App\Entity\OperationRealisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OperationRealisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method OperationRealisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method OperationRealisation[]    findAll()
 * @method OperationRealisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OperationRealisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OperationRealisation::class);
    }

    // /**
    //  * @return OperationRealisation[] Returns an array of OperationRealisation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OperationRealisation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
