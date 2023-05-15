<?php

namespace App\Controller;

use OpenAI;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatController extends AbstractController
{
    #[Route('/', name: 'app_chat')]
    public function index(): Response
    {

        $yourApiKey = "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";
        $client = OpenAI::client($yourApiKey);

        $response = $client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user', 'content' => 'dis moi bonjour'
                ],
            ],
        ]);
        //remplacer le content par des données d'un formulaire
        // enregistrer le message dans une BDD
        // créer une entité message
        // enregistrer le content de la réponse en tant qu'entité message
        // Afficher le tout
        return $this->render('chat/index.html.twig', [
            'controller_name' => 'ChatController',
            'response' => $response['choices'][0]['message']['content']
        ]);
    }
}
