<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Association;
use App\Entity\Evenement;
use App\Entity\Opportunite;
use App\Entity\Participation;
use App\Form\EvenementType;
use App\Form\OpportuniteType;
use App\Repository\ActiviteRepository;
use App\Repository\ActualiteRepository;
use App\Repository\AssociationRepository;
use App\Repository\EvenementRepository;
use App\Repository\OpportuniteRepository;
use App\Repository\ParticipationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * @Route("/evenement")
 */
class EvenementController extends AbstractController
{
//    /**
//     * @Route("/", name="evenement_index", methods={"GET"})
//     */
//    public function index(EvenementRepository $evenementRepository): Response
//    {
//        return $this->render('evenement/index.html.twig', [
//            'evenements' => $evenementRepository->findAll(),
//        ]);
//    }
    /**
     * @Route("/", name="evenement_index", methods={"GET"})
     */
    public function index(ActualiteRepository $actualiteRepository,EvenementRepository $evenementRepository): Response
    {
        return $this->render('proprietaireAssociation/evenements/evenement.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'actualites'=>$actualiteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="evenement_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($evenement);
            $entityManager->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('evenement/new.html.twig', [
            'evenement' => $evenement,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="evenement_show", methods={"GET"})
//     */
//    public function show(Evenement $evenement): Response
//    {
//        return $this->render('evenement/show.html.twig', [
//            'evenement' => $evenement,
//        ]);
//    }

//    /**
//     * @Route("/{id}/edit", name="evenement_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, Evenement $evenement): Response
//    {
//        $form = $this->createForm(EvenementType::class, $evenement);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $this->getDoctrine()->getManager()->flush();
//
//            return $this->redirectToRoute('evenement_index');
//        }
//
//        return $this->render('evenement/edit.html.twig', [
//            'evenement' => $evenement,
//            'form' => $form->createView(),
//        ]);
//    }

//    /**
//     * @Route("/{id}", name="evenement_delete", methods={"POST"})
//     */
//    public function delete(Request $request, Evenement $evenement): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($evenement);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('evenement_index');
//    }


    /////////////////////// Proprietaire Association/////////////////////
    /**
     * @Route("/newEV/{id}", name="EV_new", methods={"GET","POST"})
     */
    public function newPR($id,Association $association,AssociationRepository $associationRepository,EvenementRepository $evenementRepository,UserInterface $user,Request $request,EntityManagerInterface $manager): Response
    {
        $evenement=new Evenement();
        $association=new Association();
        $ass=$associationRepository->find($id);
        $form=$this->createFormBuilder($evenement)


            ->add('sujet',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le sujet de votre evenement",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "9",
                    'cols' => "45"
                ],
                'label' => "Entrez la description detaillé de cette oportunité  "
            ])
            ->add('place',
                TextType::class, [
                    'required' => true,
                    'label' => "Tapez la localisation",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('date',
                DateTimeType::class, [
                    'required' => true,
                    'label' => "Tapez la date debut d\'evenement",

                ])
            ->add('dateFinEvenement',
                DateTimeType::class, [
                    'required' => true,
                    'label' => "Tapez la date fin d\'evenement",

                ])
            ->add('finDateAnnonce',
                DateType::class, [
                    'required' => true,
                    'label' => "Tapez la date de fin d\'annonce",

                ])

            ->add('image',FileType::class,['label'=>'Chargez votre image' ])

            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('image')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $evenement->setImage($fileName);
            $evenement->setUser($user);
            $evenement->setIsValid(0);
//            $opportunite->setIsValid(0);
//$opportunite->setDateLimite(date_create('Y-m-d H:i:s'));
            $evenement->setAssociation($ass);
            $evenement=$form->getData();


//            $entityManager = $this->getDoctrine()->getManager();
            $manager->persist($evenement);
            $manager->flush();
            $this->addFlash('success', 'evenement  bien été enregistrée.');

            return $this->redirectToRoute('evenement_index');
        }

//        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
//            'associations' => $associationRepository->findAll(),
//            'form' => $form->createView(),
//
//        ]);
        return $this->render('proprietaireassociation/evenements/formevenement.html.twig', [
            'evenementform'=>$form->createView(),
            'evenement' => $evenement,

        ]);
    }
    /**
     * @Route("/{id}/edit", name="evenement_edit", methods={"GET","POST"})
     */
    public function edit(ActualiteRepository $actualiteRepository,Request $request, Evenement $evenement): Response
    {
        $form = $this->createForm(EvenementType::class, $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('image')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $evenement->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_index');
        }

        return $this->render('proprietaireassociation/evenements/formEvenement.html.twig', [
            'evenementform'=>$form->createView(),
            'evenement' => $evenement,
            'actualites'=>$actualiteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/deleteEv/{id}",name="deleteEv")

     */
    public function deleteActiv(Evenement $evenement,EvenementRepository $evenementRepository){
        $evenement = $evenementRepository->delete($evenement);
//        return $this->json(["message"=>"success","value"=>$activite->getIsDeleted()]);
        return $this->redirectToRoute('evenement_index');
    }
    /**
     * @Route("/changevalidite/{id}",name="changevalidite_evenement",methods={"GET","post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activate(Evenement $evenement,EvenementRepository $evenementRepository){
        $evenement = $evenementRepository->changeValidite($evenement);

        return    $this->json(["message"=>"success","value"=>$evenement->getIsValid()]);
//        $this->addFlash('success', 'validité changé .');
//        return $this->redirectToRoute('activiteAdmin_gerer');


    }
    /**
     * @Route("/admin/evenements", name="evenementAdmin_gerer", methods={"GET"})
     */
    public function index3(ActualiteRepository $actualiteRepository,EvenementRepository $evenementRepository): Response
    {
        return $this->render('admin/evenements/evenements.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'actualites'=>$actualiteRepository->findAll()
        ]);
    }


    /**
     * @Route("/{id}", name="evenement_delete", methods={"POST"})
     */
    public function delete(Request $request, Evenement $evenement): Response
    {
        if ($this->isCsrfTokenValid('delete'.$evenement->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($evenement);
            $entityManager->flush();
        }

        return $this->redirectToRoute('evenementAdmin_gerer');
    }
    /**
     * @Route("/Events", name="user_events_index", methods={"GET"})
     */
    public function indexU(ParticipationRepository $participationRepository,ActualiteRepository $actualiteRepository,EvenementRepository $evenementRepository): Response
    {
        return $this->render('Visiteur/evenements.html.twig', [
            'evenements' => $evenementRepository->findAll(),
            'actualites'=>$actualiteRepository->findAll(),
            'participations'=>$participationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/participer/{id}", name="user_participer_events", methods={"GET"})
     */
    public function participer($id,Evenement $evenement,EntityManagerInterface $manager,ActualiteRepository $actualiteRepository,EvenementRepository $evenementRepository,UserInterface $user): Response
    {
$participation=new Participation();
$evenement=$evenementRepository->find($id);
//dd($evenement);
$participation->setUser($user);
//dd($user);
        $p=$evenement->getParticipants()->getValues();
$participation->setEvenement($evenement);
$evenement->addParticipant($user);
$participation->setIsIntersse(1);

//            $entityManager = $this->getDoctrine()->getManager();
        $manager->persist($participation);
        $manager->persist($evenement);
        $manager->flush();
        $this->addFlash('success', 'Merci pour votre participation ! ');
//        return $this->json(["message"=>"success","value"]);
return $this->redirectToRoute('user_events_index',['events' => $p,'id'=>$evenement->getId(),'ev'=>$evenement]);
    }
    /**
     * @Route("/test12/{id}", name="test12", methods={"GET"})
     */
    public function test12($id,EntityManagerInterface $manager,ActualiteRepository $actualiteRepository,EvenementRepository $evenementRepository,UserInterface $user): Response
    {
$e=$evenementRepository->find($id);
$p=$e->getParticipants()->getValues();
dd($p);
        return $this->render('test.html.twig', [
            'events' => $p,

            'actualites'=>$actualiteRepository->findAll()
//            'participations'=>$participationRepository->findAll(),
        ]);
    }
}
