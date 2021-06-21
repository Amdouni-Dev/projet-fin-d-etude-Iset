<?php

namespace App\Repository;

use App\Entity\Regles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Regles|null find($id, $lockMode = null, $lockVersion = null)
 * @method Regles|null findOneBy(array $criteria, array $orderBy = null)
 * @method Regles[]    findAll()
 * @method Regles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReglesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Regles::class);
    }

    // /**
    //  * @return Regles[] Returns an array of Regles objects
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
    public function findOneBySomeField($value): ?Regles
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
