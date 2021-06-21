<?php

namespace App\Controller;

use App\Entity\Association;
use App\Entity\Opportunite;
use App\Entity\Service;
use App\Form\ServiceType;
use App\Repository\AssociationRepository;
use App\Repository\OpportuniteRepository;
use App\Repository\ServiceRepository;
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

/**
 * @Route("/service")
 */
class ServiceController extends AbstractController
{
    /**
     * @Route("/", name="service_index", methods={"GET"})
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->render('consultant/services.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }
    /**
     * @Route("/ServicesAdmin", name="service_index_admin", methods={"GET"})
     */
    public function indexSAdmin(ServiceRepository $serviceRepository): Response
    {
        return $this->render('admin/servicesConsultants/services.html.twig', [
            'services' => $serviceRepository->findAll(),
        ]);
    }
    /**
     * @Route("/new", name="service_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($service);
            $entityManager->flush();

            return $this->redirectToRoute('service_index');
        }

        return $this->render('service/new.html.twig', [
            'service' => $service,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="service_show", methods={"GET"})
//     */
//    public function show(Service $service): Response
//    {
//        return $this->render('service/show.html.twig', [
//            'service' => $service,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="service_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Service $service): Response
    {
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('service_index');
        }

        return $this->render('consultant/formService.html.twig', [
            'service' => $service,
            'serviceform' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="service_delete", methods={"POST"})
//     */
//    public function delete(Request $request, Service $service): Response
//    {
//        if ($this->isCsrfTokenValid('delete'.$service->getId(), $request->request->get('_token'))) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->remove($service);
//            $entityManager->flush();
//        }
//
//        return $this->redirectToRoute('service_index');
//    }


//    /********       Consultant       */

    /**
     * @Route("/newService", name="newService", methods={"GET","POST"})
     * @param UserInterface $user
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function newService(UserInterface $user, Request $request, EntityManagerInterface $manager): Response
    {
        $service = new Service();

        $form = $this->createFormBuilder($service)

            ->add('type',ChoiceType::class,['required'=>true,'label'=>'Choisir le type de votre service','choices' => array(
                '1. Conseil en stratégie' => 'Conseil en stratégie',
                '2. Conseil en marketing' => 'Conseil en marketing',
                '3. Conseil en exploitation' => 'Conseil en exploitation',
                '4. Conseil financier' => 'Conseil financier',
                '5. Conseil en conformité' => 'Conseil en conformité',
                '6. Conseil en technologie / informatique' => 'Conseil en technologie / informatique',
                '7. Social media consultant' => 'Social media consultant',
                '8. Consultant en durabilité' => 'Consultant en durabilité',)]





               )
//         ->add('mutimedia',FileType::class,['required'=>true,'multiple'=>true])

//
//            ->add('type',
//                TextType::class, [
//                    'required' => true,
//                    'label' => "Entrez le type de votre service",
//                    'attr' => ['class' => 'form-control']
//                ])
            ->add('description',
                TextareaType::class, [
                    'required' => true,
                    'label' => "Decrire votre service ",
                    'attr' => ['class' => 'form-control']
                ])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
           

            $service->setUser($user);
            $service->setIsValid(0);
//$opportunite->setDateLimite(date_create('Y-m-d H:i:s'));

            $service = $form->getData();


//            $entityManager = $this->getDoctrine()->getManager();
            $manager->persist($service);
            $manager->flush();
            $this->addFlash('success', 'Service bien été enregistrée.');

            return $this->redirectToRoute('service_index');
        }
        return $this->render('consultant/formService.html.twig', [
            'serviceform'=>$form->createView(),
            'service' => $service,

        ]);

    }


    /**
     * @Route("/deleteeService/{id}",name="Delete__service")
     */
    public function deleteS($id)
    {
//  $op=$opportuniteRepository->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $service= $entityManager->getRepository(Service::class)->find($id);
        $entityManager->remove($service);
        $this->addFlash('success', 'Service bien été supprimé.');


        $entityManager->flush();
        return $this->redirectToRoute('service_index');


    }
    /**
     * @Route("/changevalidite/{id}",name="changevalidite_service",methods={"post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activate(Service $service,ServiceRepository  $serviceRepository){
        $service = $serviceRepository->changeValidite($service);
        return $this->json(["message"=>"success","value"=>$service->getIsValid()]);
    }

}
