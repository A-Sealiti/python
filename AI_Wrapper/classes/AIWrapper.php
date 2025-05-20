<?php
require_once 'RecipeFormatter.php';

class AIWrapper {
    private $ingredients = [];
    private $response = '';
    private $apiKey;

    public function __construct() {
        // Eerst kijken naar lokale configuratie
        if (file_exists(__DIR__ . '/../config/.api_key.php')) {
            require_once __DIR__ . '/../config/.api_key.php';
        } elseif (!defined('API_KEY')) {
            require_once __DIR__ . '/../config/config.php';
        }
        $this->apiKey = API_KEY;
    }

    public function processInput($ingredients) {
        if (empty($ingredients)) {
            throw new Exception('Er zijn geen ingrediënten opgegeven.');
        }

        $this->ingredients = $ingredients;
        
        // Maak de API call
        $ch = curl_init('https://api.openai.com/v1/chat/completions');
        
        $data = [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Je bent een professionele chef-kok die recepten maakt op basis van beschikbare ingrediënten. Geef ALTIJD je antwoord in JSON formaat met de volgende structuur:
{
    "title": "Naam van het recept",
    "ingredients": ["ingredient 1", "ingredient 2", ...],
    "instructions": ["stap 1", "stap 2", ...],
    "preparationTime": "bereidingstijd",
    "difficulty": "moeilijkheidsgraad",
    "servings": "aantal personen"
}'
                ],
                [
                    'role' => 'user',
                    'content' => 'Maak een recept met de volgende ingrediënten: ' . implode(', ', $this->ingredients)
                ]
            ],
            'temperature' => 0.7
        ];

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ]);

        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            throw new Exception('API Error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        $result = json_decode($response, true);
        
        if (isset($result['choices'][0]['message']['content'])) {
            $this->response = $result['choices'][0]['message']['content'];
        } else {
            throw new Exception('Geen geldige response van de API');
        }
        
        return true;
    }

    public function getResponse() {
        return $this->response;
    }

    public function getFormattedRecipe() {
        $formatter = new RecipeFormatter($this->response);
        return $formatter->toHtml();
    }
}
