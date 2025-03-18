<?php
require_once 'config.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$product_id = $_GET['id'];

// Handle review submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_name = filter_input(INPUT_POST, 'user_name', FILTER_SANITIZE_STRING);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $comment = filter_input(INPUT_POST, 'comment', FILTER_SANITIZE_STRING);

    if ($user_name && $rating && $comment) {
        $stmt = $pdo->prepare('INSERT INTO reviews (product_id, user_name, rating, comment) VALUES (?, ?, ?, ?)');
        $stmt->execute([$product_id, $user_name, $rating, $comment]);
    }
}

// Fetch product details
$stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    header('Location: index.php');
    exit();
}

// Fetch reviews
$stmt = $pdo->prepare('SELECT * FROM reviews WHERE product_id = ? ORDER BY created_at DESC');
$stmt->execute([$product_id]);
$reviews = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Reviews</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1><a href="index.php">Product Reviews</a></h1>
    </header>

    <main class="product-detail">
        <div class="product-info">
            <img src="<?php echo htmlspecialchars($product['image_url']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
            <div class="product-text">
                <h2><?php echo htmlspecialchars($product['name']); ?></h2>
                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                <p class="description"><?php echo htmlspecialchars($product['description']); ?></p>
            </div>
        </div>

        <div class="reviews-section">
            <h3>Reviews</h3>
            <form class="review-form" method="POST">
                <div class="form-group">
                    <label for="user_name">Your Name:</label>
                    <input type="text" id="user_name" name="user_name" required>
                </div>
                <div class="form-group">
                    <label for="rating">Rating:</label>
                    <select id="rating" name="rating" required>
                        <option value="5">5 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="3">3 Stars</option>
                        <option value="2">2 Stars</option>
                        <option value="1">1 Star</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="comment">Your Review:</label>
                    <textarea id="comment" name="comment" required></textarea>
                </div>
                <button type="submit">Submit Review</button>
            </form>

            <div class="reviews-list">
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-header">
                            <span class="user-name"><?php echo htmlspecialchars($review['user_name']); ?></span>
                            <span class="rating">
                                <?php for ($i = 0; $i < $review['rating']; $i++) echo '★'; ?>
                                <?php for ($i = $review['rating']; $i < 5; $i++) echo '☆'; ?>
                            </span>
                        </div>
                        <p class="review-comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                        <span class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                    </div>
                <?php endforeach; ?>
                <?php if (empty($reviews)): ?>
                    <p class="no-reviews">No reviews yet. Be the first to review this product!</p>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2024 Product Reviews. All rights reserved.</p>
    </footer>
</body>
</html> 