<?php

namespace App\Controller;

use App\Entity\Activite;
use App\Entity\Association;
use App\Entity\Categorie;
use App\Entity\Regles;
use App\Entity\Service;
use App\Entity\Specialite;
use App\Entity\User;
use App\Repository\ActualiteRepository;
use App\Repository\AssociationRepository;
use App\Repository\UserRepository;
use App\Services\UploadHelper;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\VarDumper\VarDumper;

use Doctrine\ORM\EntityManagerInterface;

use App\Entity\Topic;
use App\Repository\TopicRepository;

use App\Entity\Message;
use App\Repository\MessageRepository;

use App\Form\NewTopicType;
use App\Form\NewMessageType;

use App\Service\UserFunctions;

class ForumController extends AbstractController
{
    private $topicRepository;
    private $entityManager;
    /**
     * @var UploadHelper
     */
    private $uploadHelper;
    public function __construct(TopicRepository $topicRepository, UploadHelper $uploadHelper ,EntityManagerInterface $entityManager)
    {
      $this->topicRepository=$topicRepository;
        $this->entityManager = $entityManager;
        $this->uploadHelper = $uploadHelper;

    }
    /**
     * @Route("/forum", name="forum")
     */
    public function index(TopicRepository $topicRepository,Topic $topic, MessageRepository $messageRepository)
    {
//        $topics = $topicRepository->getTopicsData();
$topics=$topicRepository->findAll();
         $lastMessage = $messageRepository->getLastMessage($topic->getId());

//        foreach ($topics as $key => $value) {
//            $countMessage = $messageRepository->getCountMessage($topics[$key]['id']);
//            $topics[$key]['countMessage'] = $countMessage;
//            $lastMessage = $messageRepository->getLastMessage($topics[$key]['id']);
//            $topics[$key]['lastMessage'] = $lastMessage;
//d
//        }

        return $this->render('forum/index.html.twig', [
            'topics' => $topics,

        ]);
    }

 

    /**
     * @Route("/forum/newTopic/{id}", name="newTopic", methods={"GET","POST"})
     */
    public function newTopic($id,UserInterface $user,Request $request, EntityManagerInterface $manager,UserRepository $userRepository)
{
//        $form = $this->createForm(AssociationType::class,$association);

//        $form = $this->createForm(NewTopicType::class, ['role' => $this->getUser()->getRoles()  ]);
        $form = $this->createForm(NewTopicType::class );
//$User=$userRepository->findOneBy($this->getUser()->getId());
//dd($User);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $userC= new User();
            $u=$userRepository->find($id);
//            dd($u);
            $topic = new Topic();
            $topic->setName($form->get('name')->getData());
//$id=$user->getId();
//dd($id);
//dd($user);
//            $topic->setAuthor($this->getUser()->getId());
            $topic->setAuthor($user);
$topic->setIdConsultant($u);
$topic->setIsRead(false);
            $topic->setCreationDate(date_create(date('Y-m-d')));
            $topic->setValid(true);
            $topic->setDeleted(false);

            $manager->persist($topic);
            $manager->flush();

            $message = new Message();
//           $message->setIdTopic($topic->getId());
            $message->setIdTopic($topic);

            $message->setIdUser($user);
            $message->setPublicationDate(date_create(date('Y-m-d H:i:s')));
            $message->setContent($form->get('content')->getData());
         

            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('topic', ['id' => $topic->getId()]);
        }

        return $this->render('forum/newTopic.html.twig', [
            'form'  =>  $form->createView()
        ]);
    }

    /**
     * @Route("/forum/topic/{id}", name="topiccc")
     */
    public function topic($id,Topic $topic = null,UserInterface $user,TopicRepository $topicRepository, MessageRepository $messageRepository, Request $request, EntityManagerInterface $manager)
    {          $messages = $messageRepository->getMessages($topic->getId());

//        if (empty($topic)) {
//            return $this->render('exceptions/404.html.twig', [
//                'reason' => 'topic'
//            ]);
//        }
//        else {
//            $messages = $messageRepository->getMessages($topic->getId());
//            foreach ($messages as $key => $value) {
//                $messages[$key]['roles'] = $functions->roleStr(end($messages[$key]['roles']));
//            }

            $form = $this->createForm(NewMessageType::class);
            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
                if ($form->get('Envoyer')->isClicked()){
                $message = new Message(Publier);
                $message->setIdTopic($topic);

                $message->setIdUser($user);
                $message->setPublicationDate(date_create(date('Y-m-d H:i:s')));
                $message->setContent($form->get('content')->getData());
              

                $manager->persist($message);
                $manager->flush();
            //actualiser
                return $this->redirectToRoute('topic', ['id' => $topic->getId()]);

                }
               
            }

            
            return $this->render('forum/index.html.twig', [
                'topics' => $topic,
                'messages' => $messages,
                'form'  =>  $form->createView()
            ]);
        }


    /**
     * @Route("/forum/editMessage/{id}", name="editMessage")
     */
    public function editMessage(Message $message, MessageRepository $messageRepository , Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(NewMessageType::class,$message);
        $form->handleRequest($request);
        $topic = $messageRepository->getTopicData($message->getIdTopic());

        if($form->isSubmitted() && $form->isValid()){
            $message->setContent($form->get('content')->getData());
          
            $manager->persist($message);
            $manager->flush();

            return $this->redirectToRoute('topic', ['id' => $message->getIdTopic()]);
        }

        return $this->render('forum/editMessage.html.twig', [
            'topic' => $topic,
            'message' => $message,
            'form'  =>  $form->createView()
        ]);
    }


/**
     * @Route("/suppforum/{id}", name="suppforum")
     */
    public function supprofil($id,Topic $topic, Request $request,TopicRepository $topicRepository , EntityManagerInterface $manager, UserFunctions $userFunctions)
    {
        if( $this->isGranted('ROLE_ADMIN')){
            
            $x = $topicRepository->find($id);
            $manager->remove($x);
           $manager->flush();

            return $this->render('home/index.html.twig', [
        
                ]);
            }


            else{
                return $this->render('home/index.html.twig', [
            
                    ]);
                
            }
        }
      


/////////////////////////////////////////////////////Mouna USer////////////


    /**
     * @Route("/forums", name="forums")
     */
    public function indexM(UserInterface $user,Topic $topic = null,TopicRepository $topicRepository,MessageRepository $messageRepository)
    {

$topics=$topicRepository->findAll();


        return $this->render('forum/all.html.twig',['topics'=>$topics]);
    }


    /**
     * @Route("/forums/topics/{id}", name="topic")
     */
    public function topics($id,Topic $topic = null,UserInterface $user,TopicRepository $topicRepository, MessageRepository $messageRepository, Request $request, EntityManagerInterface $manager)
    {
        $messages=$messageRepository->findAll();
//        $messages = $messageRepository->getMessages($topic->getId());
//$messages=$messageRepository->findAll();
//$messages=$messageRepository->find($topic->getId())

        $form = $this->createForm(NewMessageType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            if ($form->get('Envoyer')->isClicked()){
                $message = new Message();
                $topic->setIsRead(1);
                $message->setIdTopic($topic);

                $message->setIdUser($user);
                $message->setPublicationDate(date_create(date('Y-m-d H:i:s')));
                $message->setContent($form->get('content')->getData());


                $manager->persist($message);
                $manager->flush();
                //actualiser
                return $this->redirectToRoute('topic', ['id' => $topic->getId()]);

            }

        }


        return $this->render('messages.html.twig', [
            'topic' => $topic,
            'messages' => $messages,
            'form'  =>  $form->createView()
        ]);
    }
//////////////////////////////Consultant//////////////////////
    /**
     * @Route("/forumsAll", name="forumsall")
     */
    public function indexFALL(TopicRepository $topicRepository)
    {

//$topics=$topicRepository->find($user);
        $topics=$topicRepository->findAll();


        return $this->render('consultant/chats.html.twig',['topics'=>$topics]);
    }


//    /**
//     * @Route("/f", name="f")
//     */
//    public function indexF(TopicRepository $topicRepository)
//    {
//
////$topics=$topicRepository->find($user);
//        $topics=$topicRepository->findAll();
//
//
//        return $this->render('consultant/chats.html.twig',['topics'=>$topics]);
//    }
    /**
     * @Route("/forum/groupaction",name="groupaction_forum")
     * @IsGranted("ROLE_WRITER")
     */
    public function groupAction(Request $request){
        $action = $request->get("action");
        $ids = $request->get("ids");
        $categories = $this->topicRepository->findBy(["id"=>$ids]);
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
     * @Route("/AdminChat", name="adminchats", methods={"GET"})


     */
    public function indexChatAdmin(ActualiteRepository $actualiteRepository,TopicRepository $topicRepository ): Response
    {  $associations=$this->topicRepository->findAll();
//        return $this->render("proprietaireAssociation/asoociation/associationform.html.twig", ['associationform'=>$form->createView(),

        return $this->render('admin/chats/chats.html.twig', [
            'chats' => $topicRepository->findAll(),
            'actualites'=>$actualiteRepository->findAll()
        ]);
    }

    /**
     * @Route("/chats/changevaliditee/{id}",name="chat_changeV",methods={"post"})
     * @IsGranted("ROLE_SUPERUSER")
     */
    public function activate(Topic $topic){
        $topic = $this->topicRepository->changeValidite($topic);
        return $this->json(["message"=>"success","value"=>$topic->getValid()]);
    }
    /**
     * @Route("/deleteT/{id}",name="deleteT")

     */
    public function deletet(Topic $topic ){
        $topic = $this->topicRepository->delete($topic);
        return $this->redirectToRoute('forumsall',[$topic->getDeleted()])  ;

//        return $this->json(["message"=>"success","value"=>$topic->getDeleted()]);
    }
//    /**
//     * @Route("/nbSujet",name="deleteT")
//
//     */
//    public function nbSujet(){
//        $em = $this->getDoctrine()->getManager();
//        $repoUser=$em->getRepository(Topic::class);
//        $totalT = $repoUser->createQueryBuilder('a')
//            // Filter by some parameter if you want
//            ->where('a.isRead = 0 ')
//            ->select('count(a.id)')
//            ->getQuery()
//            ->getSingleScalarResult();
////        dd($totalT);
//    }

    /**
     * @Route("/ttttt/{id}", name="mmmm")
     */
    public function delete($id,Request $request, Topic $topic,TopicRepository $topicRepository,Message $message): Response
    {
//$t=$topicRepository->find($topic);
//$m=$t->getMessages();
        $entityManager = $this->getDoctrine()->getManager();
        $message= $entityManager->getRepository(Message::class)->find($topic);
//        dd($message);

        $topic= $entityManager->getRepository(Topic::class)->find($id);
        $entityManager->remove($message);
        $entityManager->remove($topic);

        $this->addFlash('success', 'Conversation bien été supprimée.');


        $entityManager->flush();
        return $this->redirectToRoute('adminchats');
//dd($t->getMessages());
    }

    /**
     * @Route("/deleteeService/{id}",name="Delete__service")
     */
    public function deleteS($id)
    {
//  $op=$opportuniteRepository->find($id);

        $entityManager = $this->getDoctrine()->getManager();

        $service= $entityManager->getRepository(Service::class)->find($id);
        $entityManager->remove($service);
        $this->addFlash('success', 'Service bien été supprimé.');


        $entityManager->flush();
        return $this->redirectToRoute('service_index');


    }
}
