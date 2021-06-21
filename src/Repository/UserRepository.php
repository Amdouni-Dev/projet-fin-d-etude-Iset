<?php
//
//namespace App\Repository;
//
//use App\Entity\User;
//use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Doctrine\Persistence\ManagerRegistry;
//
///**
// * @method User|null find($id, $lockMode = null, $lockVersion = null)
// * @method User|null findOneBy(array $criteria, array $orderBy = null)
// * @method User[]    findAll()
// * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
// */
//class UserRepository extends ServiceEntityRepository
//{
//
//    private $entityManager;
//
//    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
//    {
//        parent::__construct($registry, User::class);
//        $this->entityManager = $entityManager;
//    }
//
//    public function saveUser($user):User
//    {
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
//        return $user;
//    }
//
//    public function findOneByUsernameOrEmail($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.email = :val or u.username = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult();
//    }
//
//    // /**
//    //  * @return User[] Returns an array of User objects
//    //  */
//    /*
//    public function findByExampleField($value)
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }
//    */
//
//    /*
//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
//    */
//    public function changeValidite(User $user){
//        if ($user->isValid())
//            $user->setValid(false);
//        else
//            $user->setValid(true);
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
//        return $user;
//    }
//
//    public function delete(User $user){
//        $user->setDeleted(true);
//        $this->entityManager->persist($user);
//        $this->entityManager->flush();
//        return $user;
//    }
//}


namespace App\Repository;

use App\Entity\Association;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{

    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, User::class);
        $this->entityManager = $entityManager;
    }

    public function saveUser($user): User
    {
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function findOneByUsernameOrEmail($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.email = :val or u.username = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function changeValidite(User $user)
    {
        if ($user->isValid())
            $user->setValid(false);
        else
            $user->setValid(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }

    public function delete(User $user)
    {
        $user->setDeleted(true);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $user;
    }


    public function changeValiditecv(User $user)
    {
        if ($user->isValidcv())
            $user->setValidcv(false);
        else
            $user->setValidcv(true);

        return $user;
    }
    public function UserMaxQuatre()
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->select('o')
            ->from(User::class, 'o')
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
}
