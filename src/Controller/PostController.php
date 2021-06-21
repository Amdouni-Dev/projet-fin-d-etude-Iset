<?php

namespace App\Controller;
use App\Entity\Opportunite;
use App\Repository\ActiviteRepository;
use App\Repository\ActualiteRepository;
use App\Repository\OpportuniteRepository;
use App\Repository\ReglesRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\File;

use App\Entity\Commentaire;
use App\Entity\Mutimedia;
use App\Entity\Publication;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\MultimediaType;
use App\Form\PublicationType;
use App\Repository\CommentaireRepository;
use App\Repository\MutimediaRepository;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\UploadHelper;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class PostController extends AbstractController
{

    /**
     * @var UploadHelper
     */
    private $uploadHelper;
    public function __construct( UploadHelper $uploadHelper ,EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
        $this->uploadHelper = $uploadHelper;

    }


    /**
     * @Route("/post", name="post")
     * @param PublicationRepository $repository
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function index(ActiviteRepository $activiteRepository ,ReglesRepository $reglesRepository,PublicationRepository $repository, Request $request,ActualiteRepository $actualiteRepository): Response
    {
        $now = new \DateTime('now');
        $pubs = $repository->findAll();
        $commentaire = new Commentaire();
        $forms = [];
        $form = $this->createForm(CommentType::class, $commentaire);
        return $this->render('publicationsU/allpubs.html.twig', ['activites'=>$activiteRepository->findAll(),'regles'=>$reglesRepository->findAll(),'actualites'=>$actualiteRepository->findAll(),'now' => $now, 'pubs' => $pubs, 'form' => $form->createView()]);
    }

    /**
     * @Route("/MesPublication", name="mespublication")
     * @param UserRepository $repository
     * @param Request $request
     * @return Response
     * @throws Exception
     */

    public function index2(ReglesRepository $reglesRepository,UserRepository $repository, Request $request,ActualiteRepository $actualiteRepository): Response
    {
        $now = new \DateTime('now');
        $userid= $this->getUser()->getId();
        $user = $repository->find($userid);
        $pubs = $user->getPublications();
        $commentaire = new Commentaire();
        $forms = [];
        $form = $this->createForm(CommentType::class, $commentaire);
        return $this->render('publicationsU/MesPublication.html.twig', ['regles'=>$reglesRepository->findAll(),'actualites'=>$actualiteRepository->findAll(),'now' => $now, 'pubs' => $pubs, 'form' => $form->createView()]);
    }


    /**
     * @Route("/post/{id}/addcomment", name="newcomment")
     * @param $id
     * @param Request $request
     * @param PublicationRepository $repository
     * @param UserRepository $rep
     * @return Response
     */
    public function addcomment($id, Request $request, PublicationRepository $repository, UserRepository $rep): Response
    {
        $commentaire = new Commentaire();
//        $comment = $_POST['aa'];
        $data = $request->request->get('aa');
//        dd($data);
        $pub = $repository->find($id);
        $em = $this->getDoctrine()->getManager();
        $commentaire->setDateComnt(new \DateTime('now'));
        $commentaire->setUser($rep->find($this->getUser()->getId()));
        $commentaire->setPublication($pub);
        $commentaire->setContenuComnt($data);
        $em->persist($commentaire);
        $em->flush();
        return $this->json(['code' => 200, 'message' => $data, 'nbrcomments' => $pub->getCommentaires()->count(),
            'dateajout' => $commentaire->getDateComnt()->format('H:i')], 200);

    }

    /**
     * @Route("/post/deletecomment", name="deletecomment")
     * @param Request $request
     * @param CommentaireRepository $rep
     * @return Response
     */

    public function deletecomment(Request $request,CommentaireRepository $rep):Response{

        $idc= $_POST['d'];
        //        $idc= $request->request->get('d');
        $comment =$rep->find($idc);
        $em= $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();
        return $this->json(['code'=>200,'message'=>'commentaire supprimé !'],200);
    }
//
    /**
     * @Route("/posts/{id}", name="singleposts")
     * @param PublicationRepository $repository
     * @param Request $request
     * @return Response
     */
    public function singlepost($id, PublicationRepository $repository, Request $request): Response
    {
        $pub = $repository->find($id);
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentType::class, $commentaire);
        return $this->render('publicationsU/singlepost.html.twig', ['pub' => $pub, 'form' => $form->createView()]);
    }



    /**
     * @Route("/post/edit/{id}", name="editpost")
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     * @throws Exception
     */
    public function editpublication(ActualiteRepository $actualiteRepository,$id, Request $request, UserRepository $repository, PublicationRepository $rep, MutimediaRepository $multi): Response
    {

        $pub1 = $rep->find($id);
        $mul1 = $multi->find($id);
        $form1 = $this->createForm(PublicationType::class, $pub1);
        $form1->handleRequest($request);
        $form = $this->createForm(MultimediaType::class, $mul1);
        $form->handleRequest($request);
        dump($pub1);
        $em = $this->getDoctrine()->getManager();
        if (($form1->isSubmitted())) {
            $files [] = $request->files->all();

//            $files[] = $_FILES['files'];
//            $files [] = $request->files->all();
//            dd($files);
            $pub1->setDatePub(new \DateTime('now'));
            $pub1->setUser($repository->find($this->getUser()->getId()));
            $em->persist($pub1);
            foreach ($files as $key => $value) {
                foreach ($value as $cle => $v) {
//                    dd($v);
                    foreach ($v as $c => $file) {
//                        dd($file);
                        $p = new Mutimedia();
                        $filename = $file->getClientOriginalName();
//                        dd($filename);
                        $file->move($this->getParameter('images_directory'), $filename);
                        $p->setSource($filename);
                        $p->setPublication($pub1);
                        $em->persist($p);
                    }
                }
            }
            $em->flush();
            $this->addFlash('notice', 'Publication modifier avec succée !');
            return $this->redirectToRoute("post");
        }
//        return $this->render('publicationsU/editpublication.html.twig', ['pub1' => $pub1, 'form' => $form->createView(), 'form1' => $form1->createView()]);
        return $this->render('publicationsU/newpub.html.twig', ['actualites'=>$actualiteRepository->findAll(),'form' => $form->createView(), 'form1' => $form1->createView()]);

    }
    /**
     * @Route("/post/repost/{id}", name="Repost")
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     * @throws Exception
     */
    public function Repostpublication(ActualiteRepository $actualiteRepository,$id, Request $request, UserRepository $repository, PublicationRepository $rep, MutimediaRepository $multi): Response
    {
//        $pub2= $repository->find($id);
//        $bol=false;
//        $now=new \DateTime('now');
//        $tdiff=$now->diff($pub2->getDatePub());
//        if($tdiff->days >1)
//            $bol=true;

        $pub1 = $rep->find($id);
        $mul1 = $multi->find($id);
        $form1 = $this->createForm(PublicationType::class, $pub1);
        $form1->handleRequest($request);
        $form = $this->createForm(MultimediaType::class, $mul1);
        $form->handleRequest($request);


        dump($pub1);
        $em = $this->getDoctrine()->getManager();
        if (($form1->isSubmitted())) {
//            $files[] = $_FILES['files'];

//            dd($a,$b);
            $files [] = $request->files->all();
//            dd($files);
            $pub1->setDatePub(new \DateTime('now'));
            $pub1->setUser($repository->find($this->getUser()->getId()));
            $em->persist($pub1);
            foreach ($files as $key => $value) {
                foreach ($value as $cle => $v) {
//                    dd($v);
                    foreach ($v as $c => $file) {
//                        dd($file);
                        $p = new Mutimedia();
                        $filename = $file->getClientOriginalName();
//                        dd($filename);
                        $file->move($this->getParameter('images_directory'), $filename);
                        $p->setSource($filename);
                        $p->setPublication($pub1);
                        $em->persist($p);
                    }
                }
            }
            $em->flush();
            $this->addFlash('notice', 'Publication Resposted !');
            return $this->redirectToRoute("post");
        }
        return $this->render('publicationsU/repost.html.twig', ['actualites'=>$actualiteRepository->findAll(),'pub1' => $pub1, 'form' => $form->createView(), 'form1' => $form1->createView()]);
    }

    /**
     * @Route("/post/solved/{id}", name="Solve")
     * @param $id
     * @param Request $request
     * @param UserRepository $repository
     * @param PublicationRepository $rep
     * @param MutimediaRepository $multi
     * @return Response
     */
    public function Solvedpublication($id,Request $request,UserRepository $repository,PublicationRepository $rep,MutimediaRepository $multi): Response
    {
//        $pub2= $repository->find($id);
//        $bol=false;
//        $now=new \DateTime('now');
//        $tdiff=$now->diff($pub2->getDatePub());
//        if($tdiff->days >1)
//            $bol=true;
        $pub1 = $rep->find($id);
        $pub1->setStatut("R");
        $em= $this->getDoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute("post");
    }

    /**
     * @Route("/post/new", name="newpost")
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     * @throws Exception
     */
    public function newpublication(Request $request, UserRepository $repository): Response
    {
        try{
        $multimedia = new Mutimedia();
        $pub = new Publication();
        $form1 = $this->createForm(PublicationType::class, $pub);
        $form1->handleRequest($request);
        $form = $this->createForm(MultimediaType::class, $multimedia);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if (($form1->isSubmitted())) {
//            $files[] = $_FILES['files'];
            $a = $request->request->get('markers1');
            $b = $request->request->get('markers2');
//            dd($a,$b);
            $files [] = $request->files->all();
            $pub->setIsValid(0);
            $pub->setIsResolved(0);
            $pub->setLongitude($a);
            $pub->setLatitude($b);
            $pub->setDatePub(new \DateTime('now'));
            $pub->setUser($repository->find($this->getUser()->getId()));
            $em->persist($pub);
            $em->flush();
            foreach ($files as $key => $value) {
                foreach ($value as $cle => $v) {
                    foreach ($v as $c => $file) {
                        $p = new Mutimedia();
                        $filename = $file->getClientOriginalName();
//                        dd($filename);
                        $file->move($this->getParameter('images_directory'), $filename);

                        $p->setPublication($pub);
                        $p->setSource($filename);
                        $em->persist($p);
                    }
                }
            }
            $em->flush();
            $this->addFlash("success","Publication ajoutée ");
//            return $this->redirectToRoute("post");
            return $this->redirectToRoute("newpost");

        }
        return $this->render('publicationsU/newPub.html.twig', ['form' => $form->createView(), 'form1' => $form1->createView()]);
        }catch (\Exception $e){
            echo "Exception Found - " . $e->getMessage() . "<br/>";
        }
    }

    /**
     * @Route("/post/test", name="test")
     * @return Response

     */
    public function single(): Response
    {
        return $this->render('publication/localisatio.html.twig');
    }

    /**
     * @Route("/post/editcomment", name="editcomment")
     * @param CommentaireRepository $rep
     * @return Response
     */

    public function editcomment(CommentaireRepository $rep,Request $request):Response
    {
        $idc= $request->request->get('d');
        $contenu= $request->request->get('c');
        $comment =$rep->find($idc);
        $comment->setContenuComnt($contenu);
        $em= $this->getDoctrine()->getManager();
//        $em->persist($comment);
        $em->flush();
        return $this->json(['msg'=>'commentaire modifié !']);
    }


    /**
     * @Route("/post/editP/{id}", name="editPub")
     * @param Request $request
     * @param UserRepository $repository
     * @return Response
     * @throws Exception
     */
    public function editPub(ActualiteRepository $actualiteRepository,$id, Request $request, UserRepository $repository, PublicationRepository $rep, MutimediaRepository $multi): Response
    {
        $pub = $rep->find($id);
        $mul = $multi->find($id);

        $form1 = $this->createForm(PublicationType::class, $pub);
        $form1->handleRequest($request);
        $form = $this->createForm(MultimediaType::class, $mul);
        $form->handleRequest($request);
        $em = $this->getDoctrine()->getManager();
        if (($form1->isSubmitted())) {
            if ($form["source"]->getData()){
                $blogImage = $form["source"]->getData();
                $image= $this->uploadHelper->uploadBlogImage($blogImage,$mul->getSource());
                $mul->setSource($image->getFilename());
            }
//            $files[] = $_FILES['files'];
            $a = $request->request->get('markers1');
            $b = $request->request->get('markers2');
//            dd($a,$b);
            $files [] = $request->files->all();
            $pub->setLongitude($a);
            $pub->setLatitude($b);

            $pub->setDatePub(new \DateTime('now'));
            $pub->setUser($repository->find($this->getUser()->getId()));
            $em->persist($pub);
            $em->flush();
//            foreach ($files as $key => $value) {
//                foreach ($value as $cle => $v) {
//                    foreach ($v as $c => $file) {
//                        $p = new Mutimedia();
//                        $filename = $file->getClientOriginalName();
////                        dd($filename);
//                        $file->move($this->getParameter('images_directory'), $filename);
//                        $p->setSource($filename);
//                        $p->setPublication($pub);
//                        $em->persist($p);
//                    }
//                }
//            }
            $em->flush();
            $this->addFlash("success","Publication modifiée ");
//            return $this->redirectToRoute("post");
            return $this->redirectToRoute("post");

        }
        return $this->render('publicationsU/editPub.html.twig', ['actualites'=>$actualiteRepository->findAll(),'form' => $form->createView(), 'form1' => $form1->createView()]);
    }
    /**
     * @Route("/post/admin", name="postAdmin")
     * @param PublicationRepository $repository
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function indexpostadmin(ActualiteRepository $actualiteRepository,PublicationRepository $repository, Request $request): Response
    {
        $now = new \DateTime('now');
        $pubs = $repository->findAll();
        $commentaire = new Commentaire();
        $forms = [];
        $form = $this->createForm(CommentType::class, $commentaire);
        return $this->render('admin/publications/publications.html.twig', ['actualites'=>$actualiteRepository->findAll(),'now' => $now, 'pubs' => $pubs, 'form' => $form->createView()]);
    }
    /**
     * @Route("/deletePub/{id}",name="adminPostdelete")
     */
    public function deletePubAdmin($id)
    {


        $entityManager = $this->getDoctrine()->getManager();

        $op = $entityManager->getRepository(Publication::class)->find($id);
        $entityManager->remove($op);
        $this->addFlash('success', 'Publication bien été supprimée.');


        $entityManager->flush();
        return $this->redirectToRoute('postAdmin');


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

    /**
     * @Route("/changevaliditePubv/{id}",name="changevalidite_publication",methods={"post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activatePub(Publication $publication,PublicationRepository $publicationRepository){
        $publication = $publicationRepository->changeValidite($publication);
        return $this->json(["message"=>"success","value"=>$publication->getIsValid()]);
    }
    /**
     * @Route("/act/admin", name="actAdmin")
     * @param PublicationRepository $repository
     * @param Request $request
     * @return Response
     * @throws Exception
     */
    public function indexactadmin(ActiviteRepository $repository, Request $request): Response
    {
        $now = new \DateTime('now');
        $pubs = $repository->findAll();
        $commentaire = new Commentaire();
        $forms = [];
        $form = $this->createForm(CommentType::class, $commentaire);
        return $this->render('admin/activites/activites.html.twig', ['now' => $now, 'activites' => $pubs, 'form' => $form->createView()]);
    }
}