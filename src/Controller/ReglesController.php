<?php

namespace App\Controller;

use App\Entity\Regles;
use App\Form\ReglesType;
use App\Repository\ActualiteRepository;
use App\Repository\ReglesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/regles")
 */
class ReglesController extends AbstractController
{
    /**
     * @Route("/", name="regles_index", methods={"GET"})
     */
    public function index(ReglesRepository $reglesRepository,ActualiteRepository $actualiteRepository): Response
    {
        return $this->render('admin/regles/regles.html.twig', [
            'actualites'=>$actualiteRepository->findAll()
            ,
            'regles' => $reglesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="regles_new", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPERUSER")
     *
     */
    public function new(Request $request,UserInterface $user): Response
    {
        $regle = new Regles();
        $form = $this->createForm(ReglesType::class, $regle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $regle->setUser($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($regle);
            $entityManager->flush();
            $this->addFlash("success","Regle bien ajoutée");

            return $this->redirectToRoute('regles_index');
        }

        return $this->render('admin/regles/regleform.html.twig', [
            'regle' => $regle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="regles_show", methods={"GET"})
     */
    public function show(Regles $regle): Response
    {
        return $this->render('regles/show.html.twig', [
            'regle' => $regle,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="regles_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Regles $regle): Response
    {
        $form = $this->createForm(ReglesType::class, $regle);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('regles_index');
        }

        return $this->render('admin/regles/regleform.html.twig', [
            'regle' => $regle,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="regles_delete", methods={"POST"})
     */
    public function delete(Request $request, Regles $regle): Response
    {
        if ($this->isCsrfTokenValid('delete'.$regle->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($regle);
            $entityManager->flush();
            $this->addFlash("success","Regle bien ete supprimée");


        }

        return $this->redirectToRoute('regles_index');
    }
}
