<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\Evenement;
use App\Entity\Opportunite;
use App\Entity\User;
use App\Form\OpportuniteType;
use App\Repository\ActiviteRepository;
use App\Repository\ActualiteRepository;
use App\Repository\AssociationRepository;
use App\Repository\OpportuniteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/opportunite")
 */
class OpportuniteController extends AbstractController
{
    /**
     * @Route("/", name="opportunite_index", methods={"GET"})
     */
    public function index(ActualiteRepository $actualiteRepository,OpportuniteRepository $opportuniteRepository): Response
    {
        return $this->render('proprietaireAssociation/opportunites/opportunite.html.twig', [
            'opportunites' => $opportuniteRepository->findAll(),
            'actualites'=>$actualiteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/opportunitesAdmint", name="opportunitesAdmint", methods={"GET"})
     */
    public function indexOppAdmint(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('admin/opportunite/activites.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);

    }

    /**
     * @Route("/new", name="opportunite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $opportunite = new Opportunite();
        $form = $this->createForm(OpportuniteType::class, $opportunite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($opportunite);
            $entityManager->flush();

            return $this->redirectToRoute('opportunite_index');
        }

        return $this->render('opportunite/new.html.twig', [
            'opportunite' => $opportunite,
            'form' => $form->createView(),
        ]);
    }


    /**
     * @Route("/{id}/edit", name="opportunite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Opportunite $opportunite): Response
    {
        $form = $this->createForm(OpportuniteType::class, $opportunite);
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
            $opportunite->setImage($fileName);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('opportunite_index');
        }

        return $this->render('proprietaireassociation/opportunites/formOpportunite.html.twig', [
            'opportuniteform'=>$form->createView(),
            'opportunite' => $opportunite,

        ]);
    }
//
//    /**
//     * @Route("/dd/{id}", name="opportunite_deleteee", methods={"POST"})
//     */
//    public function delete(Request $request, Opportunite $opportunite): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$opportunite->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($opportunite);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('opportunite_index');
//    }

//    /**
//     * @Route("/deleter/{id}",name="rdelete")
//
//     */
//    public function deleteact($id){
////        $entityManager=$this->getDoctrine()->getManager();
////        $act=$entityManager->getRepository(Opportunite::class)->find($id);
////        $entityManager->remove($act);
////        $entityManager->flush();
////        return $this->redirectToRoute('association_index');
//        $em = $this->getDoctrine()->getManager();
//        $user = $em->getRepository(Opportunite::class)->findBy($id);
//$em->remove($user);
//$em->flush();
//    }
    ///////////////////////////*************** Proprietaire*****************////////////////
    /**
     * @Route("/newOP/{id}", name="OP_new", methods={"GET","POST"})
     */
    public function newPR($id,Association $association,AssociationRepository $associationRepository,OpportuniteRepository $opportuniteRepository,UserInterface $user,Request $request,EntityManagerInterface $manager): Response
    {
        $opportunite = new Opportunite();
$association=new Association();
$ass=$associationRepository->find($id);
        $form=$this->createFormBuilder($opportunite)


            ->add('titre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le titre de votre oppoptunite",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('region',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le region ",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('domaineConcerne',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le domaine concerné",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('lienFormPostul',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le lien de formulaire pour postuler",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('typeOffre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le type d\'offre ",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('dateLimite',
                DateType::class, [
                    'required' => true,
                    'label' => "Entrez la date limite",

                ])
            ->add('image',FileType::class,['label'=>'Chargez votre image' ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "9",
                    'cols' => "45"
                ],
                'label' => "Entrez la description detaillé de cette oportunité  "
            ])
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
            $opportunite->setImage($fileName);
            $opportunite->setLanceur($user);
            $opportunite->setIsValid(0);
//$opportunite->setDateLimite(date_create('Y-m-d H:i:s'));
            $opportunite->setAssociation($ass);
            $opportunite=$form->getData();


//            $entityManager = $this->getDoctrine()->getManager();
            $manager->persist($opportunite);
            $manager->flush();
            $this->addFlash('success', 'Opportunité  bien été enregistrée.');

            return $this->redirectToRoute('opportunite_index');
        }

//        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
//            'associations' => $associationRepository->findAll(),
//            'form' => $form->createView(),
//
//        ]);
        return $this->render('proprietaireassociation/opportunites/formOpportunite.html.twig', [
            'opportuniteform'=>$form->createView(),
            'opportunite' => $opportunite,

        ]);
    }


    /**
     * @Route("/opportunites", name="opportunitesUser", methods={"GET"})
     */
    public function index2(OpportuniteRepository $opportuniteRepository,ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('Visiteur/opportunites.html.twig', [
            'actualites'=>$actualiteRepository->findAll(),
            'opportunites' => $opportuniteRepository->findAll(),
        ]);

    }

    /**
     * @Route("/opportunitesAdmin", name="opportunitesAdmin", methods={"GET"})
     */
    public function indexOppAdmin(OpportuniteRepository $opportuniteRepository): Response
    {
        return $this->render('admin/opportunite/opportunite.html.twig', [
            'opportunites' => $opportuniteRepository->findAll(),
        ]);

    }

    /**
     * @Route("/Opport/{id}", name="UserOPP", methods={"GET"})

     */
    public function UserOPP($id,Opportunite $opportunite=null ,OpportuniteRepository $opportuniteRepository): Response
    {
        return $this->render('Visiteur/opportunite2parid.html.twig',['id'=>$opportunite->getId()
            ,
            'opp'=>$opportunite
        ]);
    }
    /**
     * @Route("/dddelete/{id}",name="dd")
     */
    public function deletedd($id)
    {
//  $op=$opportuniteRepository->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $op = $entityManager->getRepository(Opportunite::class)->find($id);
        $entityManager->remove($op);
        $this->addFlash('success', 'Opportunité  bien été supprimée.');


        $entityManager->flush();
        return $this->redirectToRoute('opportunitesUser');


    }
    /**
     * @Route("/deleteOPAdmin/{id}",name="opAdminDelete")
     */
    public function deleteAdminOP($id)
    {
//  $op=$opportuniteRepository->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $op = $entityManager->getRepository(Opportunite::class)->find($id);
        $entityManager->remove($op);
        $this->addFlash('success', 'Opportunité  bien été supprimée.');


        $entityManager->flush();
        return $this->redirectToRoute('opportunitesAdmin');


    }

    /**
     * @Route("/changevalidite/{id}",name="changevalidite_opportunite",methods={"post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activate(Opportunite $opportunite,OpportuniteRepository $opportuniteRepository){
        $opportunite = $opportuniteRepository->changeValidite($opportunite);
        return $this->json(["message"=>"success","value"=>$opportunite->getIsValid()]);
    }
    /**
     * @Route("/{id}", name="opp_delete", methods={"POST"})
     */
    public function delete(Request $request, Opportunite $opportunite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$opportunite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($opportunite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('opportunitesAdmin');
    }
    }
