<?php
//
//namespace App\Controller;
//
//use App\Entity\Role;
//use App\Entity\User;
//use App\Form\UserFormType;
//use App\Form\UserInscritFormType;
//use App\Repository\ActualiteRepository;
//use App\Repository\RoleRepository;
//use App\Repository\UserRepository;
//use Doctrine\ORM\EntityManagerInterface;
//use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
//use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\File\Exception\FileException;
//use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
//use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
//use Symfony\Contracts\Translation\TranslatorInterface;
//
//class SecurityController extends AbstractController
//{
//    private $passwordEncoder;
//    private $entityManager;
//    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
//    {
//        $this->userRepository = $userRepository;
//        $this->passwordEncoder = $passwordEncoder;
//        $this->entityManager = $entityManager;
//        $this->roleRepository = $roleRepository;
//    }
//    /**
//     * @Route("/login", name="app_login")
//     */
//    public function login(AuthenticationUtils $authenticationUtils,ActualiteRepository $actualiteRepository)
//    {
//
//        // get the login error if there is one
//        $error = $authenticationUtils->getLastAuthenticationError();
//        // last username entered by the user
//        $lastUsername = $authenticationUtils->getLastUsername();
//
//        return $this->render('security/login.html.twig', [
//            'actualites' => $actualiteRepository->ActualiteMaxQuinze(),
//            'last_username' => $lastUsername,
//            'error' => $error
//        ]);
////        return $this->redirectToRoute('index10', [
////            'last_username' => $lastUsername,
////            'error' => $error
////        ]);
//    }
//    /**
//     * @Route("/register",name="register")
//     */
//    public function newUser(ActualiteRepository $actualiteRepository,Request $request, TranslatorInterface $translator)
//    {
//        $form = $this->createForm(UserInscritFormType::class, null, ["translator" => $translator]);
//        $form->handleRequest($request);
//        if ($form->isSubmitted() && $form->isValid()) {
//            /** @var  User $user */
//            $user = $form->getData();
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
////            /** @var  User $user */
////            $user = $form->getData();
//            /** @var Role $role */
//            $password = $form["plainPassword"]->getData();
////            $role = $form["role"]->getData();
//            $user->setValid(true)
////                ->setRoles([$role->setRoleName("User")])
//
////            ->setRoles(["ROLE_USER"])
//                ->setDeleted(false)
//                ->setAdmin(true)
//                ->setPassword($this->passwordEncoder->encodePassword($user, $password));
////                ->setRoles([$role->getRoleName()]);
//            $this->entityManager->persist($user);
//            $this->entityManager->flush();
//            $this->addFlash('success', 'Votre compte est bien eté enregistré. Bienvenue !');
////            $this->addFlash("success", $translator->trans('backend.user.add_user'));
////            return $this->redirectToRoute("app_admin_users");
//        }
//        return $this->render("registration/register.html.twig", ['actualites'=>$actualiteRepository->findAll(),"userForm" => $form->createView()]);
//    }
//    /**
//     * @Route("/logout", name="app_logout")
//     */
//    public function logout(){
//
//    }
//
//}


namespace App\Controller;

use App\Entity\Role;
use App\Entity\User;
use App\Form\UserFormType;
use App\Form\UserInscritFormType;
use App\Repository\ActualiteRepository;
use App\Repository\RoleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Contracts\Translation\TranslatorInterface;

class SecurityController extends AbstractController
{
    private $passwordEncoder;
    private $entityManager;

    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository, UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->roleRepository = $roleRepository;
    }

    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, ActualiteRepository $actualiteRepository)
    {

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'actualites' => $actualiteRepository->ActualiteMaxQuinze(),
            'last_username' => $lastUsername,
            'error' => $error
        ]);
//        return $this->redirectToRoute('index10', [
//            'last_username' => $lastUsername,
//            'error' => $error
//        ]);
    }

    /**
     * @Route("/register",name="register")
     */
    public function newUser(ActualiteRepository $actualiteRepository, Request $request, TranslatorInterface $translator)
    {
        $form = $this->createForm(UserInscritFormType::class, null, ["translator" => $translator]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /** @var  User $user */
            $user = $form->getData();
            $file = $form->get('logo')->getData();
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
//            /** @var  User $user */
//            $user = $form->getData();
            /** @var Role $role */
            $password = $form["plainPassword"]->getData();
//            $role = $form["role"]->getData();
            $user->setValid(true)
                ->setValidcv(false)
//                ->setRoles([$role->setRoleName("User")])

//            ->setRoles(["ROLE_USER"])
                ->setDeleted(false)
                ->setAdmin(true)
                ->setPassword($this->passwordEncoder->encodePassword($user, $password));
//                ->setRoles([$role->getRoleName()]);
            $this->entityManager->persist($user);
            $this->entityManager->flush();
            $this->addFlash('success', 'Votre compte est bien eté enregistré. Bienvenue !');
//            $this->addFlash("success", $translator->trans('backend.user.add_user'));
//            return $this->redirectToRoute("app_admin_users");
        }
        return $this->render("registration/register.html.twig", ['actualites' => $actualiteRepository->findAll(), "userForm" => $form->createView()]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {

    }

}
