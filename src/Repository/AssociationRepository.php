<?php

namespace App\Repository;

use App\Entity\Actualite;
use App\Entity\Association;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Association|null find($id, $lockMode = null, $lockVersion = null)
 * @method Association|null findOneBy(array $criteria, array $orderBy = null)
 * @method Association[]    findAll()
 * @method Association[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssociationRepository extends ServiceEntityRepository
{

    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Association::class);
        $this->entityManager = $entityManager;
    }


    // /**
    //  * @return Association[] Returns an array of Association objects
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
    public function findOneBySomeField($value): ?Association
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function changeValidite(Association $association){
        if ($association->isValid())
            $association->setValid(false);
        else
            $association->setValid(true);
        $this->entityManager->persist($association);
        $this->entityManager->flush();
        return $association;
    }

    public function delete(Association $association){
        $association->setDeleted(true);
        $this->entityManager->persist($association);
        $this->entityManager->flush();
        return $association;
    }
//    public function getAssociations($id)
//    {
//        $queryBuilder = $this->entityManager->createQueryBuilder();
////        $query = $em->createQuery('SELECT m.id,m.publicationDate,m.content FROM App\Entity\Message m JOIN App\Entity\Topic t WITH m.idTopic = t.id WHERE m.id = ' . $id);
//        $queryBuilder->select('SELECT  * FROM App\Entity\Association a, App\Entity\User u
//
//           a.userA.id == u.id and a.userA.id == ' . $id);
//
////       $query = $em->createQuery('SELECT  m.id,m.idUser,m.publicationDate,m.content,u.roles FROM App\Entity\Message m JOIN App\Entity\User u WITH m.idUser = u.id WHERE m.idTopic = ' . $id);
//
//        $query =  $queryBuilder->getQuery();
//        return $query->getResult();
////        return $query->getResult();
//    }

//    public function ActualiteMaxQuinze()
//    {
//        $queryBuilder = $this->entityManager->createQueryBuilder();
//        $queryBuilder->select('o')
//            ->from(Actualite::class, 'o')
//            ->orderBy('o.id','DESC')
//            ->setMaxResults(15);
//
//        $query = $queryBuilder->getQuery();
//        return $query->getResult();
////        echo $query->getDQL(), "\n";
////        foreach ($query->getResult() as $op) {
////            echo $op;
////        }
////        return $query->getResult();
//    }

    public function AssociationMaxQuatre()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(Association::class, 'o')
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
