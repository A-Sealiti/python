<?php
require_once 'Recipe.php';

class RecipeFormatter {
    private $recipe;

    public function __construct($jsonString) {
        $this->parseJson($jsonString);
    }

    private function parseJson($jsonString) {
        try {
            $data = json_decode($jsonString, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Ongeldige JSON: ' . json_last_error_msg());
            }
            $this->recipe = new Recipe($data);
        } catch (Exception $e) {
            // Fallback naar niet-JSON parsing
            $this->parsePlainText($jsonString);
        }
    }

    private function parsePlainText($text) {
        // Probeer de tekst te parsen als het geen JSON is
        $lines = explode("\n", $text);
        $data = [
            'title' => '',
            'ingredients' => [],
            'instructions' => [],
            'preparationTime' => '',
            'difficulty' => '',
            'servings' => ''
        ];

        $currentSection = '';
        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;

            // Probeer secties te herkennen
            if (stripos($line, 'titel') !== false || stripos($line, 'recept') !== false) {
                $data['title'] = trim(str_replace(['Titel:', 'Recept:'], '', $line));
            } elseif (stripos($line, 'ingrediënten') !== false) {
                $currentSection = 'ingredients';
            } elseif (stripos($line, 'bereiding') !== false || stripos($line, 'instructies') !== false) {
                $currentSection = 'instructions';
            } elseif (stripos($line, 'bereidingstijd') !== false) {
                $data['preparationTime'] = trim(str_replace('Bereidingstijd:', '', $line));
            } elseif (stripos($line, 'moeilijkheidsgraad') !== false) {
                $data['difficulty'] = trim(str_replace('Moeilijkheidsgraad:', '', $line));
            } elseif (stripos($line, 'personen') !== false) {
                $data['servings'] = trim(str_replace('Personen:', '', $line));
            } else {
                // Voeg toe aan de huidige sectie
                if ($currentSection === 'ingredients') {
                    $data['ingredients'][] = $line;
                } elseif ($currentSection === 'instructions') {
                    $data['instructions'][] = $line;
                }
            }
        }

        $this->recipe = new Recipe($data);
    }

    public function getRecipe() {
        return $this->recipe;
    }

    public function toHtml() {
        if (!$this->recipe->isValid()) {
            return '<div class="alert alert-danger">Ongeldig recept ontvangen</div>';
        }

        $html = '<div class="recipe">';
        $html .= '<h2>' . htmlspecialchars($this->recipe->getTitle()) . '</h2>';
        
        // Metadata
        $html .= '<div class="recipe-meta">';
        if ($this->recipe->getPreparationTime()) {
            $html .= '<span class="prep-time"><i class="fas fa-clock"></i> ' . htmlspecialchars($this->recipe->getPreparationTime()) . '</span>';
        }
        if ($this->recipe->getDifficulty()) {
            $html .= '<span class="difficulty"><i class="fas fa-signal"></i> ' . htmlspecialchars($this->recipe->getDifficulty()) . '</span>';
        }
        if ($this->recipe->getServings()) {
            $html .= '<span class="servings"><i class="fas fa-users"></i> ' . htmlspecialchars($this->recipe->getServings()) . '</span>';
        }
        $html .= '</div>';

        // Ingrediënten
        $html .= '<div class="ingredients">';
        $html .= '<h3>Ingrediënten</h3>';
        $html .= '<ul>';
        foreach ($this->recipe->getIngredients() as $ingredient) {
            $html .= '<li>' . htmlspecialchars($ingredient) . '</li>';
        }
        $html .= '</ul>';
        $html .= '</div>';

        // Instructies
        $html .= '<div class="instructions">';
        $html .= '<h3>Bereiding</h3>';
        $html .= '<ol>';
        foreach ($this->recipe->getInstructions() as $instruction) {
            $html .= '<li>' . htmlspecialchars($instruction) . '</li>';
        }
        $html .= '</ol>';
        $html .= '</div>';

        $html .= '</div>';
        return $html;
    }
} 