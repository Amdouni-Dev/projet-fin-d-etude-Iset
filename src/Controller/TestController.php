<?php


namespace App\Controller;


use App\Entity\User;
use App\Repository\CategorieRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{

    /**
     * @Route("/test1",name="test1")
     */
    public function index(){
        return $this->render("bazz.html.twig");
    }

    /**
     * @Route("/test22",name="test2")
     */
    public function index22(){
        return $this->render("test2.html.twig");
    }

    /**
     * @Route("/test5",name="test5")
     */
    public function indexTest5(){
        return $this->render("publicationsU/allPub.html.twig");
    }
    /**
     * @Route("/testindex",name="testindex")
     */
    public function indexTest(){
        return $this->render("publicationsU/allPub.html.twig");
    }
    /**
     * @Route("/nb",name="nb")
     */
    public function indexnb(){
        $em = $this->getDoctrine()->getManager();
        $repoUser=$em->getRepository(User::class);
        $totalU = $repoUser->createQueryBuilder('a')
            // Filter by some parameter if you want
             ->where('a.valid = 1 ')
            ->select('count(a.id)')
            ->getQuery()
            ->getSingleScalarResult();
            dd($totalU);

    }
}
