<?php
class Recipe {
    private $title;
    private $ingredients;
    private $instructions;
    private $preparationTime;
    private $difficulty;
    private $servings;

    public function __construct($data = []) {
        $this->title = $data['title'] ?? '';
        $this->ingredients = $data['ingredients'] ?? [];
        $this->instructions = $data['instructions'] ?? [];
        $this->preparationTime = $data['preparationTime'] ?? '';
        $this->difficulty = $data['difficulty'] ?? '';
        $this->servings = $data['servings'] ?? '';
    }

    public function getTitle() {
        return $this->title;
    }

    public function getIngredients() {
        return $this->ingredients;
    }

    public function getInstructions() {
        return $this->instructions;
    }

    public function getPreparationTime() {
        return $this->preparationTime;
    }

    public function getDifficulty() {
        return $this->difficulty;
    }

    public function getServings() {
        return $this->servings;
    }

    public function isValid() {
        return !empty($this->title) && 
               !empty($this->ingredients) && 
               !empty($this->instructions);
    }
} 