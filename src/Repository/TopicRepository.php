<?php

namespace App\Repository;

use App\Entity\Association;
use App\Entity\Topic;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
/**
 * @method Topic|null find($id, $lockMode = null, $lockVersion = null)
 * @method Topic|null findOneBy(array $criteria, array $orderBy = null)
 * @method Topic[]    findAll()
 * @method Topic[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TopicRepository extends ServiceEntityRepository
{
    private $entityManager;


    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Topic::class);
        $this->entityManager = $entityManager;
    }

    public function getTopicsData() {
       

        
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT t.id,t.name,t.author,u.username,u.roles FROM App\Entity\Topic t JOIN App\Entity\User u WITH t.author = u.id ");

        return $query->getResult();
    }

    public function changeValidite(Topic $topic){
        if ($topic->isValid())
            $topic->setValid(false);
        else
            $topic->setValid(true);
        $this->entityManager->persist($topic);
        $this->entityManager->flush();
        return $topic;
    }
    public function delete(Topic $topic){
        $topic->setDeleted(true);
        $this->entityManager->persist($topic);
        $this->entityManager->flush();
        return $topic;
    }
}
