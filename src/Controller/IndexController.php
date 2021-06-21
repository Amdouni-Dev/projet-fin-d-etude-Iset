<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\Evenement;
use App\Entity\Opportunite;
use App\Entity\Participation;
use App\Entity\Publication;
use App\Entity\Service;
use App\Entity\Topic;
use App\Entity\User;
use App\Repository\ActualiteRepository;
use App\Repository\AssociationRepository;
use App\Repository\EvenementRepository;
use App\Repository\OpportuniteRepository;
use App\Repository\ReglesRepository;
use App\Repository\SpecialiteRepository;
use App\Repository\TopicRepository;
use App\Repository\UserRepository;
use ContainerCTHr7m3\getUserInterface2Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="indexx")
     */
    public function index(SpecialiteRepository $specialiteRepository,TopicRepository $topicRepository,EvenementRepository $evenementRepository,ReglesRepository $reglesRepository,UserRepository $userRepository,ActualiteRepository $actualiteRepository,AssociationRepository $associationRepository,OpportuniteRepository $opportuniteRepository): Response
    {
//        dd(UserInterface::class,$this->getUser());
        /** UserInterface $user
        */

        $user=$this->getUser();
        $em = $this->getDoctrine()->getManager();
        $repoUser=$em->getRepository(User::class);
        $totalU = $repoUser->createQueryBuilder('a')
            // Filter by some parameter if you want
//            ->where('a.valid = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $em = $this->getDoctrine()->getManager();
        $repoUserCV=$em->getRepository(User::class);
        $totalUCV = $repoUserCV->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.validcv = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $em = $this->getDoctrine()->getManager();
        $repoUserCVs=$em->getRepository(User::class);
        $totalUCVs = $repoUserCVs->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.validcv = 0 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoAss=$em->getRepository(Association::class);
        $totalAss=$repoAss->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.deleted = 0 and a.valid =1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoPub=$em->getRepository(Publication::class);
        $totalPubs=$repoPub->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isValid = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoPubR=$em->getRepository(Publication::class);
        $totalPubsR=$repoPubR->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isResolved = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoEv=$em->getRepository(Evenement::class);
        $totalEv=$repoEv->createQueryBuilder('a')
            // Filter by some parameter if you want
//            ->where('a.isResolved = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoEvs=$em->getRepository(Evenement::class);
        $totalEvs=$repoEvs->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isValid = 0 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoP=$em->getRepository(Participation::class);
        $totalP=$repoP->createQueryBuilder('a')
            // Filter by some parameter if you want
//            ->where('a.isResolved = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoOP=$em->getRepository(Opportunite::class);
        $totalOP=$repoOP->createQueryBuilder('a')
            // Filter by some parameter if you want
//            ->where('a.isResolved = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $em = $this->getDoctrine()->getManager();
        $repott=$em->getRepository(Topic::class);
        $totalT = $repott->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isRead = 0 and a.idConsultant = :u' )
            ->setParameter('u', $user)
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $em = $this->getDoctrine()->getManager();
        $repoP=$em->getRepository(Publication::class);
        $totalPub = $repoP->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isValid = 0 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $em = $this->getDoctrine()->getManager();
        $repoUser=$em->getRepository(Service::class);
        $totalSer = $repoUser->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isValid = 0 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $em = $this->getDoctrine()->getManager();
        $repoOps=$em->getRepository(Opportunite::class);
        $totalOPs = $repoOps->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isValid = 0 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        $repoACT=$em->getRepository(Opportunite::class);
        $totalACT = $repoACT->createQueryBuilder('a')
            // Filter by some parameter if you want
            ->where('a.isValid = 0 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
        return $this->render('admin/main.html.twig', [
            'users'=>$totalU,
            'actualites'=>$actualiteRepository->findAll(),
            'associations'=>$totalAss,
            'pubs'=>$totalPubs,
            'pubsR'=>$totalPubsR,
            'evenements'=>$totalEv,
             'participations'=>$totalP,
            'opportunites'=>$totalOP,
            'jeunes'=>$totalUCV,
            'jeune'=>$totalUCVs,
            'topics'=>$totalT,
            'regles'=>$reglesRepository->findAll(),
            'pub'=>$totalPub,
            'services'=>$totalSer,
            'ops'=>$totalOPs,
            'evs'=>$totalEvs,
            'acv'=>$totalACT,
            'top'=>$topicRepository->findAll(),
            'evenementsAll'=>$evenementRepository->ActualiteMax3(),
            'associationsAll'=>$associationRepository->AssociationMaxQuatre(),
            'opportunitesAll'=>$opportuniteRepository->OpportunitesMaxQuatre(),
            'usersAll'=>$userRepository->UserMaxQuatre(),
            'specialites' => $specialiteRepository->findAll(),
            'controller_name' => 'IndexController',
        ]);
    }
}
