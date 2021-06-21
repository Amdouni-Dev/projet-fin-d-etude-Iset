<?php


namespace App\Controller;


use App\Repository\ActualiteRepository;
use App\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{

    /**
     * @Route("/admin",name="app_admin_index")
     */
    public function index(){
        return $this->render("admin/main.html.twig");
    }
    /**
     * @Route("/Bienvenue",name="index10")
     */
    public function index20(ActualiteRepository $actualiteRepository){

        return $this->redirectToRoute("indexx",[
            'actualites' => $actualiteRepository->ActualiteMaxQuinze()
        ]);
    }

}
