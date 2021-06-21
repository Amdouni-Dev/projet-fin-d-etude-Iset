<?php
//
//
//namespace App\Controller;
//
//
//use App\Entity\Association;
//use App\Entity\Role;
//use App\Entity\User;
//use App\Form\AssociationEditFormType;
//use App\Form\ChangePwsdFormType;
//use App\Form\EditProfile;
//use App\Form\UserFormType;
//use App\Repository\AssociationRepository;
//use App\Repository\RoleRepository;
//use App\Repository\UserRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\File\Exception\FileException;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Contracts\Translation\TranslatorInterface;
//
//class ParamsUserController extends BaseController
//{
//    private $userRepository;
//    private $passwordEncoder;
//
//    private $entityManager;
//    private $roleRepository;
//
//    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
//    {
//        $this->userRepository = $userRepository;
//        $this->passwordEncoder = $passwordEncoder;
//        $this->entityManager = $entityManager;
//        $this->roleRepository = $roleRepository;
//    }
//
//
//
//
//
//
//
//    /**
//     * @Route("/{id}/edit", name="userr_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, UserRepository $userRepository,EditProfile $editProfile,User $user): Response
//    {
//        $form = $this->createForm(EditProfile::class, $user);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//
//
//            $file=$form->get('logo')->getData();
//            $fileName=md5(uniqid()).'.'.$file->guessExtension();
//
//            try {
//                $file->move(
//                    $this->getParameter('images_directory'),
//                    $fileName
//                );
//            } catch (FileException $e) {
//                // ... handle exception if something happens during file upload
//            }
//            $user->setLogo($fileName);
//
//
//
//            $this->entityManager->persist($user);
//            $this->entityManager->flush();
//            $this->addFlash("success","compte modifié");
//
//            return $this->redirectToRoute('indexx');
//        }
//
//        return $this->render("admin/user/editprofile.html.twig", ['editProfileform'=>$form->createView(),
//
//            'form' => $form->createView(),
//
//        ]);
//    }
//
//
//
//
//
//
//}


namespace App\Controller;


use App\Entity\Association;
use App\Entity\Role;
use App\Entity\User;
use App\Form\AssociationEditFormType;
use App\Form\ChangePwsdFormType;
use App\Form\EditProfile;
use App\Form\ProfilJeuneType;
use App\Form\UserFormType;
use App\Repository\ActualiteRepository;
use App\Repository\AssociationRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class ParamsUserController extends BaseController
{
    private $userRepository;
    private $passwordEncoder;

    private $entityManager;
    private $roleRepository;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->roleRepository = $roleRepository;
    }


    /**
     * @Route("/{id}/edit", name="userr_edit", methods={"GET","POST"})
     */
    public function edit(ActualiteRepository $actualiteRepository,Request $request, UserRepository $userRepository, EditProfile $editProfile, User $user): Response
    {
        $form = $this->createForm(EditProfile::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $file = $form->get('logo')->getData();
            if ($file) {
                $fileName = md5(uniqid()) . '.' . $file->guessExtension();

                try {
                    $file->move(
                        $this->getParameter('images_directory'),
                        $fileName
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $user->setLogo($fileName);

            }


            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash("success", "compte modifié");

            return $this->redirectToRoute('indexx');
        }

        return $this->render("admin/user/editprofile.html.twig", ['editProfileform' => $form->createView(),

            'form' => $form->createView(),
            'actualites'=>$actualiteRepository->findAll()

        ]);
    }





    /**
     * @Route("/{id}/editJeune", name="jeune_edit", methods={"GET","POST"})
     */
    public function editProfilJeune(ActualiteRepository $actualiteRepository,Request $request, UserRepository $userRepository, ProfilJeuneType $Profilejeune, User $user): Response
    {
        $form = $this->createForm(ProfilJeuneType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $cvFile = $form->get('cv')->getData();
            if ($cvFile) {
                $cvfilename = md5(uniqid()) . '.' . $cvFile->guessExtension();
                try {
                    $cvFile->move(
                        $this->getParameter('cvs_directory'),
                        $cvfilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $user->setCv($cvfilename);
            }



            $videoFile = $form->get('video')->getData();
            if ($videoFile) {
                $videofilename = md5(uniqid()) . '.' . $videoFile->guessExtension();
                try {
                    $videoFile->move(
                        $this->getParameter('videos_directory'),
                        $videofilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }
                $user->setVideo($videofilename);
            }


            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash("success", "compte Jeune modifié");

            return $this->redirectToRoute('indexx');
        }

        return $this->render("jeune/editjeuneprofile.html.twig", ['editJeuneform' => $form->createView(),

            'form' => $form->createView(),
            'actualites'=>$actualiteRepository->findAll()

        ]);
    }


}