<?php
// Inclusief de AIWrapper klasse
require_once 'classes/AIWrapper.php';

// Controleer of het formulier is verzonden
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ingredients'])) {
    try {
        $ingredientsInput = trim($_POST['ingredients']);
        if (empty($ingredientsInput)) {
            throw new Exception("Geen ingrediënten opgegeven");
        }
        $ingredients = array_map('trim', explode(',', $ingredientsInput));

        $wrapper = new AIWrapper();
        $wrapper->processInput($ingredients);
        
        // Gebruik de geformatteerde output
        $formattedRecipe = $wrapper->getFormattedRecipe();
        header('Location: index.php?recipe=' . urlencode($formattedRecipe));
        exit;
    } catch (Exception $e) {
        header('Location: index.php?error=' . urlencode($e->getMessage()));
        exit;
    }
}
?>