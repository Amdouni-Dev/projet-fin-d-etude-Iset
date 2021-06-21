<?php
//
//namespace App\Repository;
//
//use App\Entity\Message;
//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\Common\Persistence\ManagerRegistry;
//
///**
// * @method Message|null find($id, $lockMode = null, $lockVersion = null)
// * @method Message|null findOneBy(array $criteria, array $orderBy = null)
// * @method Message[]    findAll()
// * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
// */
//class MessageRepository extends ServiceEntityRepository
//{
//    public function __construct(ManagerRegistry $registry)
//    {
//        parent::__construct($registry, Message::class);
//    }
//
//
//
//    public function getTopicData($topic) {
//        $em = $this->getEntityManager();
//        $query = $em->createQuery('SELECT t.id, t.name FROM App\Entity\Message m JOIN App\Entity\Topic t WITH m.idTopic = t.id WHERE m.id = '.$topic);
//        return $query->execute();
//    }
//
//    public function getCountMessage($topic) {
//        return $this->createQueryBuilder('m')
//            ->select('count(m.id)')
//            ->andWhere('m.idTopic = :id')
//            ->setParameter('id', $topic)
//            ->getQuery()
////            ->getSingleScalarResult()
//        ;
//    }
//
//    public function getLastMessage($topic) {
//        return $this->createQueryBuilder('m')
//            ->select('m.publicationDate')
//            ->andWhere('m.idTopic = :id')
//            ->setParameter('id', $topic)
//            ->orderBy('m.publicationDate', 'DESC')
//            ->setMaxResults(1)
//            ->getQuery()
////            ->getSingleScalarResult()
//        ;
//    }
//
//    public function getMessages($id) {
//        $em = $this->getEntityManager();
//        $query = $em->createQuery('SELECT m.id,m.idUser,m.publicationDate,m.content,u.username,u.roles FROM App\Entity\Message m JOIN App\Entity\User u WITH m.idUser = u.id WHERE m.idTopic = '.$id);
//        return $query->getResult();
//    }
//}


namespace App\Repository;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }


    public function getTopicData($topic)
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT t.id, t.name FROM App\Entity\Message m JOIN App\Entity\Topic t WITH m.idTopic = t.id WHERE m.id = ' . $topic);
        return $query->execute();
    }

    public function getCountMessage($topic)
    {
        return $this->createQueryBuilder('m')
            ->select('count(m.id)')
            ->andWhere('m.idTopic = :id')
            ->setParameter('id', $topic)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function getLastMessage($topic)
    {
        return $this->createQueryBuilder('m')
            ->select('m.publicationDate')
            ->andWhere('m.idTopic = :id')
            ->setParameter('id', $topic)
            ->orderBy('m.publicationDate', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleScalarResult();
    }





    public function getMessages($id)
    {
        $em = $this->getEntityManager();
//        $query = $em->createQuery('SELECT m.id,m.publicationDate,m.content FROM App\Entity\Message m JOIN App\Entity\Topic t WITH m.idTopic = t.id WHERE m.id = ' . $id);
        $query = $em->createQuery('SELECT  m.id,m.publicationDate,m.content,u.roles  FROM App\Entity\Topic t, App\Entity\Message m
          JOIN App\Entity\User u
           WITH m.idUser = u.id WHERE m.idTopic = ' . $id);

//       $query = $em->createQuery('SELECT  m.id,m.idUser,m.publicationDate,m.content,u.roles FROM App\Entity\Message m JOIN App\Entity\User u WITH m.idUser = u.id WHERE m.idTopic = ' . $id);
        return $query->execute();

//        return $query->getResult();
    }




}
