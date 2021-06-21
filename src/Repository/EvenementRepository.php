<?php

namespace App\Repository;

use App\Entity\Activite;
use App\Entity\Actualite;
use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Evenement::class);
        $this->entityManager = $entityManager;

    }

    // /**
    //  * @return Evenement[] Returns an array of Evenement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function delete(Evenement $evenement){
        $evenement->setIsDeleted(true);
        $this->entityManager->persist($evenement);
        $this->entityManager->flush();
        return $evenement;
    }
    public function changeValidite(Evenement $evenement){
        if ($evenement->isValid())
            $evenement->setIsValid(false);
        else
            $evenement->setIsValid(true);
        $this->entityManager->persist($evenement);
        $this->entityManager->flush();
        return $evenement;
    }



    public function ActualiteMax3()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Evenement::class, 'o')
            ->orderBy('o.id','DESC')
            ->setMaxResults(2);

        $query = $queryBuilder->getQuery();
        return $query->getResult();
//        echo $query->getDQL(), "\n";
//        foreach ($query->getResult() as $op) {
//            echo $op;
//        }
//        return $query->getResult();
    }

}
