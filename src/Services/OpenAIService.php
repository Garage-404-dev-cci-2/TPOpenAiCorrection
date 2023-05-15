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

    public function apiRequest($message): string
    {
        // Envoyer la requête à l'API via openai php client
        $response = $this->client->chat()->create([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'user', 'content' => $message
                ],
            ],
        ]);

        // Récupération du contenue de la réponse
        $responseContent = $response['choices'][0]['message']['content'];

        return $responseContent;
    }
}
