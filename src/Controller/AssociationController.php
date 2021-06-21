<?php

namespace App\Controller;
use App\Form\AssociationAjoutInfoType;
use App\Form\AssociationEditFormType;
use App\Form\AssociationEditPAFormType;
use App\Repository\ActualiteRepository;
use App\Repository\EvenementRepository;
use App\Repository\OpportuniteRepository;
use App\Services\UploadHelper;

use App\Entity\Association;
use App\Form\AssociationType;
use App\Repository\AssociationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use MongoDB\BSON\Type;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @Route("/association")
 */
class AssociationController extends AbstractController
{

private $associationRepository;
private $entityManager;
    /**
     * @var UploadHelper
     */
    private $uploadHelper;
    public function __construct(AssociationRepository $associationRepository, UploadHelper $uploadHelper ,EntityManagerInterface $entityManager)
    {
       $this->associationRepository=$associationRepository;
        $this->entityManager = $entityManager;
        $this->uploadHelper = $uploadHelper;

    }

    /**
     * @Route("/", name="association_index", methods={"GET"})


     */
    public function index(AssociationRepository $associationRepository): Response
    {  $associations=$this->associationRepository->findAll();
//        return $this->render("proprietaireAssociation/asoociation/associationform.html.twig", ['associationform'=>$form->createView(),

        return $this->render('admin/association/association.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }

//    /**
//     * @Route("/new", name="association_new", methods={"GET","POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $association = new Association();
//        $form = $this->createForm(AssociationType::class, $association);
//
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($association);
//            $entityManager->flush();
//
//            return $this->redirectToRoute('association_index');
//        }
//
//        return $this->render('association/new.html.twig', [
//            'association' => $association,
//            'form' => $form->createView(),
//        ]);
//    }


    /**
     * @Route("/new", name="association_new", methods={"GET","POST"})
     */
    public function new(AssociationRepository $associationRepository,Request $request,EntityManagerInterface $manager): Response
    {
        $association = new Association();
        $form = $this->createFormBuilder($association);

        $form=$this->createFormBuilder($association)
            ->add('UserA',null,['label'=>'Choisissez le proprietaire '])

            ->add('titre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le nom d'association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('siege',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le siege d'association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('adresse',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez l'adresse d'association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('nombreMembre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le nombre des membres d'association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('logo',FileType::class,['label'=>'Charger le logo d\'association' ])
            ->add('but', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "9",
                    'cols' => "45"
                ],
                'label' => "Entrez le but d'association "
            ])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('logo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $association->setLogo($fileName);
            $association=$form->getData();
            $association->setValid(true);
            $association->setDeleted(false);

//            $entityManager = $this->getDoctrine()->getManager();
            $manager->persist($association);
            $manager->flush();
            $this->addFlash('success', 'Association  bien été enregistrée.');

            return $this->redirectToRoute('association_index');
        }

        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
            'associations' => $associationRepository->findAll(),
            'form' => $form->createView(),

        ]);
    }











//    /**
//     * @Route("/{id}", name="association_show", methods={"GET"})
//     */
//    public function show(Association $association): Response
//    {
//        return $this->render('association/show.html.twig', [
//            'association' => $association,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="association_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, AssociationRepository $associationRepository,AssociationEditFormType $associationEditFormType,Association $association): Response
    {
        $form = $this->createForm(AssociationEditFormType::class, $association);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('logo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $association->setLogo($fileName);



$this->entityManager->persist($association);
            $this->entityManager->flush();
            $this->addFlash("success","Association modifiée");

            return $this->redirectToRoute('association_index');
        }

        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
            'associations' => $associationRepository->findAll(),
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}", name="association_delete", methods={"POST"})
     */
    public function delete(Request $request, Association $association): Response
    {
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($association);
            $entityManager->flush();
        }

        return $this->redirectToRoute('association_index');

    }

///**
// * @Route("/ass/{idPA}",name="newAss")
// * @IsGranted("ROLE_SUPERUSER")
// */
//public function creerAss (AssociationRepository $associationRepository,SluggerInterface $slugger,$idPA,Request $requete,UserRepository $DirecteurRepository, EntityManagerInterface $manager){
//    $ass=new Association();
////    $ass->setCreatedAt(new \DateTime('now'))  ;
//    $directeur=$DirecteurRepository->find($idPA);
////        dd($directeur);
//    $form=$this->createFormBuilder($ass)
//->add('UserA'
//)
//
//        ->add('titre',
//            TextType::class, [
//                'required' => true,
//                'label' => "Entrez le nom de votre association",
//                'attr' => ['class' => 'form-control']
//            ])
//        ->add('adresse',
//            TextType::class, [
//                'required' => true,
//                'label' => "Entrez l\'adresse de votre association",
//                'attr' => ['class' => 'form-control']
//            ])
//        ->add('nombreMembre',
//            TextType::class, [
//                'required' => true,
//                'label' => "Entrez le nombre des membres de votre association",
//                'attr' => ['class' => 'form-control']
//            ])
//        ->add('logo',FileType::class,['label'=>'Charger votre logo d\'association' ])
//        ->add('but', TextareaType::class, [
//            'attr' => [
//                'class' => 'form-control',
//                'rows' => "9",
//                'cols' => "45"
//            ],
//            'label' => "Entrez le but de votre assocition "
//        ])
//
//
//
//        ->add('Valider', SubmitType::class, [
//            'attr' => [
//                'class' => 'btn btn-primary'
//            ]
//        ])
//
//        ->getForm();
//    $form->handleRequest($requete);
//    if($form->isSubmitted() && $form->isValid()) {
////        $file=$ass->getLogo();
//        $file=$form->get('logo')->getData();
//        $fileName=md5(uniqid()).'.'.$file->guessExtension();
//        try {
//            $file->move(
//                $this->getParameter('images_directory'),
//                $fileName
//            );
//        } catch (FileException $e) {
//            // ... handle exception if something happens during file upload
//        }
//        $ass->setUserA($directeur);
//        $ass->setLogo($fileName);
//
//        $ass=$form->getData();
//        $ass->setValid(true);
//        $ass->setDeleted(false);
//        $manager->persist($ass);
//        $manager->flush();
//        $this->addFlash('success', 'Association  bien été enregistrée.');
//        return $this->redirectToRoute('association_index');
//
//
//
//    }
//
//    return $this->render("proprietaireAssociation/association/associationform.html.twig", ['associationform'=>$form->createView(),
//        'associations' => $associationRepository->findAll(),
//        'idPA'=>$idPA,
//        'form' => $form->createView(),
//
//    ]);
//
//
//}
    /**
     * @Route("/ass1",name="newAss", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function creerAss (AssociationRepository $associationRepository,Request $requete, EntityManagerInterface $manager){
        $ass=new Association();
//    $ass->setCreatedAt(new \DateTime('now'))  ;
//        dd($directeur);
        $form=$this->createFormBuilder($ass)
            ->add('UserA'
            )

            ->add('titre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le nom de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('adresse',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez l\'adresse de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('nombreMembre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le nombre des membres de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('logo',FileType::class,['label'=>'Charger votre logo d\'association' ])
            ->add('but', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "9",
                    'cols' => "45"
                ],
                'label' => "Entrez le but de votre assocition "
            ])



            ->add('Valider', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary'
                ]
            ])

            ->getForm();
        $form->handleRequest($requete);
        if($form->isSubmitted() && $form->isValid()) {
//        $file=$ass->getLogo();
            $file=$form->get('logo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $ass->setLogo($fileName);

            $ass=$form->getData();
            $ass->setValid(true);
            $ass->setDeleted(false);
            $manager->persist($ass);
            $manager->flush();
            $this->addFlash('success', 'Association  bien été enregistrée.');
            return $this->redirectToRoute('association_index');



        }

        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
            'associations' => $associationRepository->findAll(),
            'form' => $form->createView(),

        ]);


    }

    /**
     * @Route("/association/groupaction",name="groupaction_association")
     * @IsGranted("ROLE_WRITER")
     */
    public function groupAction(Request $request){
        $action = $request->get("action");
        $ids = $request->get("ids");
        $categories = $this->associationRepository->findBy(["id"=>$ids]);
        if ($action=="desactiver" && $this->isGranted("ROLE_EDITORIAL")){
            foreach ($categories as $categorie) {
                $categorie->setValid(false);
                $this->entityManager->persist($categorie);
            }
        }else if ($action=="activer" && $this->isGranted("ROLE_EDITORIAL")){
            foreach ($categories as $categorie) {
                $categorie->setValid(true);
                $this->entityManager->persist($categorie);
            }
        }else if ($action=="supprimer" && $this->isGranted("ROLE_EDITORIAL")){
            foreach ($categories as $categorie) {
                $categorie->setDeleted(true);
                $this->entityManager->persist($categorie);
            }
        }
        else{
            return $this->json(["message"=>"error"]);
        }
        $this->entityManager->flush();
        return $this->json(["message"=>"success","nb"=>count($categories)]);
    }
    /**
     * @Route("/association/edit/{id}",name="edit_association")
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function editCategorie(Association $association ,Request $request){
        $form = $this->createForm(AssociationType::class,$association);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $association->setValid(true)
                ->setDeleted(false);
            $this->entityManager->persist($association);
            $this->entityManager->flush();
            $this->addFlash("success","Association ajouté");
            return $this->redirectToRoute("association_index");
        }
        return $this->render("admin/association/associationform.html.twig",["associationform"=>$form->createView()]);
    }
    /**
     * @Route("/association/changevalidite/{id}",name="changevalidite_association",methods={"post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activate(Association $association ){
        $association = $this->associationRepository->changeValidite($association);
        return $this->json(["message"=>"success","value"=>$association->getValid()]);
    }

    /**
     * @Route("/association/delete/{id}",name="deletee_association")
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function deleteA(Association $association){
        $association = $this->associationRepository->delete($association);
        return $this->json(["message"=>"success","value"=>$association->getDeleted()]);
    }

    /**
     * @Route("/association/edit1/{id}",name="editt_associations")
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function edita(Association $association ,Request $request){
        $association = new Association();
        $association
            ->setTitre($association->getTitre())
            ->setSiege($association->getSiege())
            ->setBut($association->getBut())
            ->setLogo($association->getLogo())
            ->setAdresse($association->getAdresse())
            ->setNombreMembre ($association->getNombreMembre());
        $this->entityManager->persist($association);

        $form = $this->createForm(AssociationType::class,$association);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($form["logo"]->getData()){
                $blogImage = $form["logo"]->getData();
                $image= $this->uploadHelper->uploadBlogImage($blogImage,$association->getTitre());
                $association->setLogo($image->getFilename());
            }

            /* If want to add more specific img validation
             * if (!$this->uploadHelper->validateImg($blogImage)){

            }*/
            $this->entityManager->persist($association);
            $this->entityManager->flush();
            $this->addFlash("success","Association modifiée");
            return $this->redirectToRoute("association_index");
        }

        return $this->render("admin/association/associationform.html.twig",["associationform"=>$form->createView()]);
    }

//////////////////////////////////////// Proprietaire association/////////////////////////////////////////////////////
///
///
///
    /**
     * @Route("/PA", name="association_indexP", methods={"GET"})
     * @IsGranted("ROLE_PASSOCIATION")

     */
    public function indexP(AssociationRepository $associationRepository): Response
    {  $associations=$this->associationRepository->findAll();
//        return $this->render("proprietaireAssociation/asoociation/associationform.html.twig", ['associationform'=>$form->createView(),

        return $this->render('proprietaireAssociation/association/association.html.twig', [
            'associations' => $associationRepository->findAll(),
        ]);
    }




    /**
     * @Route("/newP", name="association_newP", methods={"GET","POST"})
     */
    public function newP(AssociationRepository $associationRepository,Request $request,EntityManagerInterface $manager): Response
    {
        $association = new Association();
        $form = $this->createFormBuilder($association);

        $form=$this->createFormBuilder($association)
            ->add('UserA')

            ->add('titre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le nom de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('siege',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le siege de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('adresse',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez l\'adresse de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('nombreMembre',
                TextType::class, [
                    'required' => true,
                    'label' => "Entrez le nombre des membres de votre association",
                    'attr' => ['class' => 'form-control']
                ])
            ->add('logo',FileType::class,['label'=>'Charger votre logo d\'association' ])
            ->add('but', TextareaType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'rows' => "9",
                    'cols' => "45"
                ],
                'label' => "Entrez le but de votre assocition "
            ])
            ->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('logo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();
            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $association->setLogo($fileName);
            $association=$form->getData();
            $association->setValid(true);
            $association->setDeleted(false);

//            $entityManager = $this->getDoctrine()->getManager();
            $manager->persist($association);
            $manager->flush();
            $this->addFlash('success', 'Association  bien été enregistrée.');

            return $this->redirectToRoute('association_index');
        }

        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
            'associations' => $associationRepository->findAll(),
            'form' => $form->createView(),

        ]);
    }













    /**
     * @Route("/{id}/editP", name="association_editP", methods={"GET","POST"})
     */
    public function editP(Request $request, AssociationRepository $associationRepository,AssociationEditFormType $associationEditFormType,Association $association): Response
    {
        $form = $this->createForm(AssociationEditPAFormType::class, $association);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $file=$form->get('logo')->getData();
            $fileName=md5(uniqid()).'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('images_directory'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            $association->setLogo($fileName);



            $this->entityManager->persist($association);
            $this->entityManager->flush();
            $this->addFlash("success","Association modifié");

            return $this->redirectToRoute('association_indexP');
        }

        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
            'associations' => $associationRepository->findAll(),
            'form' => $form->createView(),

        ]);
    }
    /**
     * @Route("/{id}/ajout", name="association_ajoutInfo", methods={"GET","POST"})
     */
    public function ajoutInformation(Request $request, AssociationRepository $associationRepository,AssociationEditFormType $associationEditFormType,Association $association): Response
    {
        $form = $this->createForm(AssociationAjoutInfoType::class, $association);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {




            $this->entityManager->persist($association);
            $this->entityManager->flush();
            $this->addFlash("success","Information ajoutée avec succé");

            return $this->redirectToRoute('association_indexP');
        }

        return $this->render("admin/association/associationform.html.twig", ['associationform'=>$form->createView(),
            'associations' => $associationRepository->findAll(),
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/{id}/P", name="association_deleteP", methods={"POST"})
     */
    public function deleteP(Request $request, Association $association): Response
    {
        if ($this->isCsrfTokenValid('delete'.$association->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($association);
            $entityManager->flush();
        }

        return $this->redirectToRoute('association_indexP');
    }




    /**
     * @Route("/associationP/edit/{id}",name="edit_associationP")
     * @IsGranted("ROLE_PASSOCIATION")
     */
    public function editCategorieP(Association $association ,Request $request){
        $form = $this->createForm(AssociationType::class,$association);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $association->setValid(true)
                ->setDeleted(false);
            $this->entityManager->persist($association);
            $this->entityManager->flush();
            $this->addFlash("success","Association ajouté");
            return $this->redirectToRoute("association_indexP");
        }
        return $this->render("admin/association/associationform.html.twig",["associationform"=>$form->createView()]);
    }




    /**
     * @Route("/associationP/edit1/{id}",name="editt_associationsP")
     * @IsGranted("ROLE_PASSOCIATION")
     */
    public function editaP(Association $association ,Request $request){
        $association = new Association();
        $association
            ->setTitre($association->getTitre())
            ->setSiege($association->getSiege())
            ->setBut($association->getBut())
            ->setLogo($association->getLogo())
            ->setAdresse($association->getAdresse())
            ->setNombreMembre ($association->getNombreMembre());
        $this->entityManager->persist($association);

        $form = $this->createForm(AssociationType::class,$association);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            if ($form["logo"]->getData()){
                $blogImage = $form["logo"]->getData();
                $image= $this->uploadHelper->uploadBlogImage($blogImage,$association->getTitre());
                $association->setLogo($image->getFilename());
            }

            /* If want to add more specific img validation
             * if (!$this->uploadHelper->validateImg($blogImage)){

            }*/
            $this->entityManager->persist($association);
            $this->entityManager->flush();
            $this->addFlash("success","Association modifiée");
            return $this->redirectToRoute("association_indexP");
        }

        return $this->render("admin/association/associationform.html.twig",["associationform"=>$form->createView()]);
    }





/////////////////////////////////// *********Simple User********///////////////
    /**
     * @Route("/AssociationsUser", name="association_index_user", methods={"GET"})

     */
    public function indexUserAssociations(ActualiteRepository $actualiteRepository,AssociationRepository $associationRepository): Response
    {  $associations=$this->associationRepository->findAll();
        return $this->render('Visiteur/associations.html.twig', [
            'actualites'=>$actualiteRepository->findAll()
            ,
            'associations' => $associationRepository->findAll(),
        ]);
    }
    /**
     * @Route("/Associ/{id}", name="UserAssoci", methods={"GET"})

     */
    public function UserAssociation(EvenementRepository $evenementRepository,ActualiteRepository $actualiteRepository,$id,Association $association=null,AssociationRepository $associationRepository,OpportuniteRepository $opportuniteRepository): Response
    {

        return $this->render('Visiteur/aassociation2parid.html.twig',['id'=>$association->getId()
        ,'opportunites'=>$opportuniteRepository->OpportuniteMaxQuatre(),
            'events'=>$evenementRepository->ActualiteMax3()
            ,
            'actualites'=>$actualiteRepository->findAll()
            ,
            'ass'=>$association
        ]);
    }
}
