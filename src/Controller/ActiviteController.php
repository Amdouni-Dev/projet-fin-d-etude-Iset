<?php

namespace App\Controller;

use App\Entity\Activite;

use App\Entity\Association;
use App\Entity\Topic;
use App\Entity\User;
use App\Form\ActiviteType;

use App\Repository\ActiviteRepository;
use App\Repository\ActualiteRepository;
use App\Repository\AssociationRepository;
use App\Services\UploadHelper;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PublicationRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\DataMapper\CheckboxListMapper;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @Route("/activite")
 */
class ActiviteController extends AbstractController
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
     * @Route("/", name="activite_index", methods={"GET"})
     */
    public function index(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('activite/index.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/activites", name="activite_gerer", methods={"GET"})
     * @param ActiviteRepository $activiteRepository
     * @return Response
     */
    public function index2(ActiviteRepository $activiteRepository): Response
    {
        return $this->render('proprietaireAssociation/activites/activites.html.twig', [
            'activites' => $activiteRepository->findAll(),
        ]);
    }
    /**
     * @Route("/admin/activites", name="activiteAdmin_gerer", methods={"GET"})
     */
    public function index3(ActualiteRepository $actualiteRepository,ActiviteRepository $activiteRepository): Response
    {
        return $this->render('admin/activites/activites.html.twig', [
            'activites' => $activiteRepository->findAll(),
            'actualites'=>$actualiteRepository->findAll()
        ]);
    }

    /**
     * @Route("/new", name="activite_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $activite = new Activite();
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activite);
            $entityManager->flush();

            return $this->redirectToRoute('activite_index');
        }

        return $this->render('activite/new.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="activite_show", methods={"GET"})
//     */
//    public function show(Activite $activite): Response
//    {
//        return $this->render('activite/show.html.twig', [
//            'activite' => $activite,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="activite_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Activite $activite): Response
    {
        $form = $this->createForm(ActiviteType::class, $activite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('activite_index');
        }

        return $this->render('activite/edit.html.twig', [
            'activite' => $activite,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="activite_delete", methods={"POST"})
     */
    public function delete(Request $request, Activite $activite): Response
    {
        if ($this->isCsrfTokenValid('delete'.$activite->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($activite);
            $entityManager->flush();
        }

        return $this->redirectToRoute('activite_gerer');
    }

    /**
     * @Route("/Resoudre/{id}/{id1}", name="resoudre")
     * @param AssociationRepository $associationRepository
     * @param ActiviteRepository $activiteRepository
     * @param $id
     * @param Request $request
     * @param UserInterface $user
     * @param PublicationRepository $publicationRepository
     * @return Response
     */
    public function resoudre($id1,UserRepository $userRepository,AssociationRepository $associationRepository,ActiviteRepository $activiteRepository,$id,Request $request, UserInterface $user,PublicationRepository $publicationRepository): Response
    {
        $activite=new Activite();
        $pub=$publicationRepository->find($id);

        $ass=$associationRepository->find($user);
        $u=$userRepository->find($user);
        $a=$u->getAssociations()->getValues();
        $n=$u->getAssociations();

        $form = $this->createFormBuilder($activite)
            ->add('association',  null, [


                'choices'=>($a

                ),
                'label' => "Choisissez le titre de votre association  "
            ])
            ->add('description', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "2",
                    'cols' => "45"
                ],
                'label' => "Entrez la description detaillé de cette activité   "
            ])


            ->add('video', FileType::class, ['label' => 'Chargez votre video'])
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            $file=$form->get('video')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $activite->setVideo($fileName);
//            $activite->setAssociation($ass);

            $activite->setUser($user);
            $activite->setIsValid(0);
            $activite->setIsDeleted(0);
            $activite->setPublication($pub);
            $pub->setIsResolved(1);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($activite);
            $entityManager->flush();

            $this->addFlash('success', 'activité ajouté .');


            return $this->redirectToRoute('post');



        }
        return $this->render('proprietaireassociation/activites/activiteform.html.twig', [
            'activiteform'=>$form->createView(),
//'activites'=>$activiteRepository->find($user),
//        'ass'=>$associationRepository->getAssociations($id1),
            'id1'=>$id1,
            'activite' => $activite,

        ]);

    }

    /**
     * @Route("/test7", name="test7", methods={"GET"})
     */
    public function indextest7(Request $request,UserRepository $userRepository, UserInterface $user,AssociationRepository $associationRepository): Response
    {

//$ass=$associationRepository->findAll();
$u=$userRepository->find($user);
//        foreach ($ass as $a){
//dd($a);
//        }
//   $u=$userRepository->find($ass->getUserA()->getId());
dd($u->getAssociations()->getValues());
    }


    /**
     * @Route("/problemeResolue/{id}", name="problemeResolue", methods={"GET"})
     */
    public function index8(ActiviteRepository $activiteRepository,Activite $activite = null,$id,ActualiteRepository $actualiteRepository): Response
    {
//        $activite=$actualiteRepository->find($id);
        return $this->render('Visiteur/activite.html.twig', [
            'actualites' => $actualiteRepository->findAll(),
            'id'=>$activite->getId()  ,
            'activite'=>$activite,
        ]);
    }
    /**
     * @Route("/changevalidite/{id}",name="changevalidite_activite",methods={"GET","post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activate(Activite $activite,ActiviteRepository $activiteRepository){
        $activite = $activiteRepository->changeValidite($activite);

    return    $this->json(["message"=>"success","value"=>$activite->getIsValid()]);
//        $this->addFlash('success', 'validité changé .');
//        return $this->redirectToRoute('activiteAdmin_gerer');


    }
    /**
     * @Route("/deleteAct/{id}",name="deleteActiv")

     */
    public function deleteActiv(Activite $activite,ActiviteRepository $activiteRepository){
        $activite = $activiteRepository->delete($activite);
//        return $this->json(["message"=>"success","value"=>$activite->getIsDeleted()]);
        return $this->redirectToRoute('activite_gerer');
    }

}
