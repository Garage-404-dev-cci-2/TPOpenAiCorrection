<?php

namespace App\Controller;

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
    public function index(): Response
    {

        $message = "dis moi bonjour";
        $response = $this->openAIService->apiRequest($message);


        //remplacer le content par des données d'un formulaire
        // enregistrer le message dans une BDD
        // créer une entité message
        // enregistrer le content de la réponse en tant qu'entité message
        // Afficher le tout

        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'response' => $response
        ]);
    }
}
