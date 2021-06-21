<?php

namespace App\Controller;

use App\Entity\Actualite;
use App\Form\ActualiteType;
use App\Repository\ActualiteRepository;
use App\Repository\PublicationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/actualite")
 */
class ActualiteController extends AbstractController
{
    /**
     * @Route("/", name="actualite_index", methods={"GET"})
     */
    public function index(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('admin/actualites/actualites.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="actualite_new", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function new(Request $request,UserInterface $user): Response
    {
        $actualite = new Actualite();
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $actualite->setCreatedAt(new \DateTime('now'));
            $actualite->setUser($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($actualite);
            $entityManager->flush();
            $this->addFlash("success","Actualité bien ajoutée");
            return $this->redirectToRoute('actualite_index');
        }

        return $this->render('admin/actualites/actualiteform.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="actualite_show", methods={"GET"})
     */
    public function show(Actualite $actualite): Response
    {
        return $this->render('actualite/show.html.twig', [
            'actualite' => $actualite,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="actualite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Actualite $actualite): Response
    {
        $form = $this->createForm(ActualiteType::class, $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success","Actualité modifiée");
            return $this->redirectToRoute('actualite_index');
        }

        return $this->render('admin/actualites/actualiteform.html.twig', [
            'actualite' => $actualite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="actualite_delete", methods={"POST"})
     */
    public function delete(Request $request, Actualite $actualite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$actualite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($actualite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('actualite_index');
    }


    /**
     * @Route("/supprimer", name="supprimeractualite")
     * @param ActualiteRepository $repository
     * @param Request $request
     * @return Response
     */
    public function delpost(ActualiteRepository $repository,Request $request): Response
    {
        $data= $request->request->get('d');
//        dd($data);
        $act = $repository->find($data);
        $em = $this->getDoctrine()->getManager();
        $em->remove($act);
        $em->flush();
        return $this->json(['code' => 200], 200);
    }


    /**
     * @Route("/ActsUser", name="actualite_indexU", methods={"GET"})
     */
    public function indexActsUser(ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('admin/bars.html.twig', [
            'actualites' => $actualiteRepository->ActualiteMaxQuinze()
        ]);
    }
}
