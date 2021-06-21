<?php

namespace App\Repository;

use App\Entity\Actualite;
use App\Entity\Association;
use App\Entity\Opportunite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Opportunite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Opportunite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Opportunite[]    findAll()
 * @method Opportunite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OpportuniteRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry ,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Opportunite::class);
        $this->entityManager = $entityManager;
    }

    // /**
    //  * @return Opportunite[] Returns an array of Opportunite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Opportunite
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function OpportuniteMaxQuatre()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Opportunite::class, 'o')
     ->orderBy('o.id','DESC')
            ->setMaxResults(4);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
//        echo $query->getDQL(), "\n";
//        foreach ($query->getResult() as $op) {
//            echo $op;
//        }
//        return $query->getResult();
    }


    public function changeValidite(Opportunite $opportunite){
        if ($opportunite->isValid())
            $opportunite->setIsValid(false);
        else
            $opportunite->setIsValid(true);
        $this->entityManager->persist($opportunite);
        $this->entityManager->flush();
        return $opportunite;
    }


    public function OpportunitesMaxQuatre()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Opportunite::class, 'o')
            ->orderBy('o.id','DESC')
            ->setMaxResults(3);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
//        echo $query->getDQL(), "\n";
//        foreach ($query->getResult() as $op) {
//            echo $op;
//        }
//        return $query->getResult();
    }

}
