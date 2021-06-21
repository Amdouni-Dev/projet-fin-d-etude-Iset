<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Publication;
use Symfony\Component\HttpFoundation\Request;

use App\Form\CommentaireFormType;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    /**
     * @Route("/comment/{id}", name="comment")
     * @param $id
     * @param Request $request
     * @param PublicationRepository $comment
     * @param UserRepository $repository
     * @return Response
     * @throws \Exception
     */
    public function index($id,Request $request,PublicationRepository $comment,UserRepository $repository): Response
    { $pub = $comment->find($id);
    $comments=$pub->getCommentaires();
    $commentaire=new Commentaire();
    $form=$this->createForm(CommentaireFormType::class,$commentaire);
    $form->handleRequest($request);
        if ($form->isSubmitted()) {
             $commentaire->setDateComnt(new \DateTime('now'));
             $id=$this->getUser()->getId();
             $commentaire->setUser($repository->find($id));
             $commentaire->setPublication($pub);
//             dd($commentaire);
             $em= $this->getDoctrine()->getManager();
             $em->persist($commentaire);
             $em->flush();
             return $this->redirectToRoute('post');
            }

        return $this->render('comment/index.html.twig', [
            'publication'=>$pub,
            'comments'=>$comments,
            'form'=>$form->createView(),
        ]);
    }

    /**
     * @Route("/post/MesPublications", name="supprimerpost")
     * @param PublicationRepository $repository
     * @param Request $request
     * @return Response
     */
    public function delpost(PublicationRepository $repository,Request $request): Response
    {
        $data= $request->request->get('d');
//        dd($data);
        $pub = $repository->find($data);
        $em = $this->getDoctrine()->getManager();
        $em->remove($pub);
        $em->flush();
        return $this->json(['code' => 200], 200);
    }
}
