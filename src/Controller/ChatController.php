<?php

namespace App\Controller;

use DateTime;
use App\Entity\Message;
use Symfony\Component\HttpFoundation\Request;
use App\Form\MessageType;
use App\Services\OpenAIService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    private $entityManager;
    private $openAIService;


    public function __construct(EntityManagerInterface $entityManager, OpenAIService $openAIService)
    {
        $this->entityManager = $entityManager;
        $this->openAIService = $openAIService;
    }

    #[Route('/', name: 'app_chat')]
    public function index(Request $request): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $message = $this->NewMessage($message->getContent(), 'user');
            $history = $this->GetHistory();
            $responsecontent = $this->openAIService->apiRequest($history);
            $this->NewMessage($responsecontent, 'assistant');

            return $this->redirectToRoute('app_chat');
        }
        $messages = $this->entityManager
            ->getRepository(Message::class)
            ->findAll();

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'messages' => $messages,
            'form' =>  $form->createView(),
        ]);
    }




    public function GetHistory()
    {
        $packdemessageszebi = $this->entityManager->getrepository(Message::class)
            ->findBy([], ["createdAt" => "DESC"], 9);

        return array_reverse($packdemessageszebi);
    }

    public function NewMessage(string $content, string $role)
    {
        $message = new Message;
        $message->setRole($role)
            ->setContent($content)
            ->setCreatedAt(new \DateTime());

        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $message;
    }
    #[Route('/clear', name: 'app_clear')]
    public function clear(Request $request): Response
    {

        $messages = $this->entityManager->getrepository(Message::class)
            ->findAll();
        foreach ($messages as $message) {
            $this->entityManager->remove($message);
        }
        $this->entityManager->flush();

        return $this->redirectToRoute("app_chat");
    }
}
