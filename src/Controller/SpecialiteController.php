<?php

namespace App\Controller;





use App\Entity\Specialite;

use App\Form\SpecialiteType;


use App\Repository\ActualiteRepository;
use App\Repository\SpecialiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;




class SpecialiteController extends AbstractController
{
    /**
     * @Route("/SpecialiteJeune", name="specialite_index", methods={"GET"})
     */
    public function index(ActualiteRepository $actualiteRepository,SpecialiteRepository $specialiteRepository): Response
    {
        return $this->render('jeune/specialites.html.twig', [
            'specialites' => $specialiteRepository->findAll(),
            'actualites'=>$actualiteRepository->findAll()
        ]);
    }


    /**
     * @Route("/SpecialiteAdmin", name="specialite_index_admin", methods={"GET"})
     */
    public function indexSpAdmin(SpecialiteRepository $specialiteRepository): Response
    {
        return $this->render('admin/specialitesJeunes/specialites.html.twig', [
            'specialites' => $specialiteRepository->findAll(),
        ]);
    }


    /**
     * @Route("/newSpecialite", name="newSpecialite", methods={"GET","POST"})
     * @param UserInterface $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newSpecialite(ActualiteRepository $actualiteRepository,UserInterface $user, Request $request, EntityManagerInterface $manager): Response
    {
        $specialite = new Specialite();

        $form = $this->createFormBuilder($specialite)

            ->add('type',ChoiceType::class,['required'=>true,'label'=>'Choisir le type de votre spécialite','choices' => array(
                    '1. Art et Culture' => 'Art et Culture',
                    '2. Agroalimentaire' => 'Agroalimentaire',
                    '3. Communication' => 'Communication',
                    '4. Comptabilité et Finance' => 'Comptabilité et Finance',
                    '5. Design et Innovation' => 'Design et Innovation',
                    '6. Economie' => 'Economie',
                    '7. Gouvernance' => 'Gouvernance',
                    '8. Médias et Journalisme' => 'Médias et Journalisme',)]





            )
//         ->add('mutimedia',FileType::class,['required'=>true,'multiple'=>true])

//
//            ->add('type',
//                TextType::class, [
//                    'required' => true,
//                    'label' => "Entrez le type de votre specialite",
//                    'attr' => ['class' => 'form-control']
//                ])
            ->add('description',
                TextareaType::class, [
                    'required' => true,
                    'label' => "Decrire votre spécialite ",
                    'attr' => ['class' => 'form-control']
                ])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {


            $specialite->setUser($user);
            $specialite->setIsValid(0);
//$opportunite->setDateLimite(date_create('Y-m-d H:i:s'));

            $specialite = $form->getData();


//            $entityManager = $this->getDoctrine()->getManager();
            $manager->persist($specialite);
            $manager->flush();
            $this->addFlash('success', 'Spécialite bien été enregistrée.');

            return $this->redirectToRoute('specialite_index');
        }
        return $this->render('jeune/formSpecialite.html.twig', [
            'specialiteform'=>$form->createView(),
            'specialite' => $specialite,
            'actualites'=>$actualiteRepository->findAll()

        ]);

    }


    /**
     * @Route("/{id}/editSP", name="specialite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Specialite $specialite): Response
    {
        $form = $this->createForm(SpecialiteType::class, $specialite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('specialite_index');
        }

        return $this->render('jeune/formSpecialite.html.twig', [
            'specialite' => $specialite,
            'specialiteform' => $form->createView(),
        ]);
    }






    /**
     * @Route("/deleteeSpecialite/{id}",name="Delete__specialite")
     */
    public function deleteS($id)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $specialite= $entityManager->getRepository(Specialite::class)->find($id);
        $entityManager->remove($specialite);
        $this->addFlash('success', 'Spécialite bien été supprimée.');


        $entityManager->flush();
        return $this->redirectToRoute('specialite_index_admin');


    }

    /**
     * @Route("/changevaliditesp/{id}",name="changevalidite_specialite",methods={"post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activate(Specialite $specialite,SpecialiteRepository  $specialiteRepository){
        $entityManager = $this->getDoctrine()->getManager();
        $specialite = $specialiteRepository->changeValiditesp($specialite);
        $entityManager->persist($specialite);
        $entityManager->flush();
        return $this->json(["message"=>"success","value"=>$specialite->getIsValid()]);
    }

}