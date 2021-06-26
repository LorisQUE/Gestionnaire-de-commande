<?php

namespace App\Repository;

use App\Entity\CommandeAchat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandeAchat|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeAchat|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeAchat[]    findAll()
 * @method CommandeAchat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeAchatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeAchat::class);
    }

    public function  getPrixTotal(CommandeAchat $commandeAchat)
    {
        try {
            $var = $this->createQueryBuilder('c')
                ->select('SUM(l.Prix * l.Quantite)')
                ->join('c.Lignes', 'l')
                ->andWhere('c = :commande')
                ->setParameter('commande', $commandeAchat)
                ->getQuery()
                ->getOneOrNullResult()
            ;
            $price = (float) array_values($var)[0];
            return round($price, 2);
        }
        catch (\Exception $exception){
            return 0;
        }
    }

    // /**
    //  * @return CommandeAchat[] Returns an array of CommandeAchat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CommandeAchat
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
