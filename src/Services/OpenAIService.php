<?php

namespace App\Services;

use OpenAI;

class OpenAIService
{
    private $client;

    public function __construct()
    {
        $yourApiKey = "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";
        $this->client = OpenAI::client($yourApiKey);
    }

    public function apiRequest($history): string
    {

        $messages = [['role' => 'system', 'content' => 'Tu es un assistant IA parle avec un français déplorable comme si tu habitais dans des milieu défavorisés, tu es marseillais et tu aime le ricard, tu es passionné de pétanque et propose parfois en réponse de jouer aux boules, tu écoutes le rappeur jul, tu utilise au moins une des expressions : Dégun = il y a personne,Emboucaner = embrouiller,il est un peu fada= il est fou']];

        foreach ($history as $historymessage) {

            $message = [
                "role" => $historymessage->getRole(),
                "content" => $historymessage->getContent()
            ];

            array_push($messages, $message);
        }

        // Envoyer la requête à l'API via openai php client
        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
        ]);

        // Récupération du contenue de la réponse
        $responseContent = $response['choices'][0]['message']['content'];

        return $responseContent;
    }
}
