<?php
require_once 'config.php';

// Get selected category from URL
$selected_category = isset($_GET['category']) ? (int)$_GET['category'] : null;

// Fetch all categories
$stmt = $pdo->query('SELECT * FROM categories ORDER BY name');
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare the products query
if ($selected_category) {
    $stmt = $pdo->prepare('
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        WHERE p.category_id = ? 
        ORDER BY p.name
    ');
    $stmt->execute([$selected_category]);
} else {
    $stmt = $pdo->query('
        SELECT p.*, c.name as category_name 
        FROM products p 
        JOIN categories c ON p.category_id = c.id 
        ORDER BY c.name, p.name
    ');
}
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group products by category
$products_by_category = [];
foreach ($products as $product) {
    $products_by_category[$product['category_name']][] = $product;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Product Reviews</h1>
        <nav class="category-nav">
            <a href="index.php" class="<?php echo !$selected_category ? 'active' : ''; ?>">All Categories</a>
            <?php foreach ($categories as $category): ?>
                <a href="index.php?category=<?php echo $category['id']; ?>" 
                   class="<?php echo $selected_category == $category['id'] ? 'active' : ''; ?>">
                    <?php echo htmlspecialchars($category['name']); ?>
                </a>
            <?php endforeach; ?>
            <a href="admin_login.php" class="admin-link">Admin Login</a>
        </nav>
    </header>

    <main>
        <?php if (empty($products)): ?>
            <p class="no-products">No products found in this category.</p>
        <?php else: ?>
            <?php foreach ($products_by_category as $category_name => $category_products): ?>
                <section class="category-section">
                    <h2><?php echo htmlspecialchars($category_name); ?></h2>
                    <div class="product-grid">
                        <?php foreach ($category_products as $product): ?>
                            <div class="product-card">
                                <img src="<?php echo htmlspecialchars($product['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>">
                                <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="price">$<?php echo number_format($product['price'], 2); ?></p>
                                <p class="description"><?php echo htmlspecialchars(substr($product['description'], 0, 100)) . '...'; ?></p>
                                <a href="product.php?id=<?php echo $product['id']; ?>" class="view-details">View Details</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endforeach; ?>
        <?php endif; ?>
    </main>

    <footer>
        <p>&copy; 2024 Product Reviews. All rights reserved.</p>
    </footer>
</body>
</html> 