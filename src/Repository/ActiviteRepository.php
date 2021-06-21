<?php

namespace App\Repository;

use App\Entity\Activite;

use App\Entity\Topic;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Activite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Activite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Activite[]    findAll()
 * @method Activite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActiviteRepository extends ServiceEntityRepository
{
    private $entityManager;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Activite::class);
        $this->entityManager = $entityManager;
    }

    // /**
    //  * @return Activite[] Returns an array of Activite objects
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
    public function findOneBySomeField($value): ?Activite
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
//    public function getMessages($id)
//    {
//        $em = $this->getEntityManager();
////        $query = $em->createQuery('SELECT m.id,m.publicationDate,m.content FROM App\Entity\Message m JOIN App\Entity\Topic t WITH m.idTopic = t.id WHERE m.id = ' . $id);
//        $query = $em->createQuery('SELECT  m.id,m.publicationDate,m.content,u.roles  FROM App\Entity\Topic t, App\Entity\Message m
//          JOIN App\Entity\User u
//           WITH m.idUser = u.id WHERE m.idTopic = ' . $id);
//
////       $query = $em->createQuery('SELECT  m.id,m.idUser,m.publicationDate,m.content,u.roles FROM App\Entity\Message m JOIN App\Entity\User u WITH m.idUser = u.id WHERE m.idTopic = ' . $id);
//        return $query->execute();
//
////        return $query->getResult();
//    }

    public function changeValidite(Activite $activite){
        if ($activite->isValid())
            $activite->setIsValid(false);
        else
            $activite->setIsValid(true);
        $this->entityManager->persist($activite);
        $this->entityManager->flush();
        return $activite;
    }

    public function delete(Activite $activite){
        $activite->setIsDeleted(true);
        $this->entityManager->persist($activite);
        $this->entityManager->flush();
        return $activite;
    }

}
