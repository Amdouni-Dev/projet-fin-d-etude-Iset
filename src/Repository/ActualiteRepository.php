<?php

namespace App\Repository;

use App\Entity\Actualite;
use App\Entity\Opportunite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Actualite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actualite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actualite[]    findAll()
 * @method Actualite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActualiteRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry ,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Actualite::class);
        $this->entityManager = $entityManager;
    }
    // /**
    //  * @return Actualite[] Returns an array of Actualite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Actualite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


    public function ActualiteMaxQuinze()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Actualite::class, 'o')
            ->orderBy('o.id','DESC')
            ->setMaxResults(15);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
//        echo $query->getDQL(), "\n";
//        foreach ($query->getResult() as $op) {
//            echo $op;
//        }
//        return $query->getResult();
    }
}
