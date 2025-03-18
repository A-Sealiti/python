<?php
require_once 'config.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

$message = '';
$error = '';

// Handle Delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    if ($stmt->execute([$_GET['id']])) {
        $message = 'Product deleted successfully';
    } else {
        $error = 'Error deleting product';
    }
}

// Handle Create/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $category_id = intval($_POST['category_id'] ?? 0);
    $image_url = trim($_POST['image_url'] ?? '');

    // Validate input
    if (empty($name) || empty($description) || $price <= 0 || $category_id <= 0) {
        $error = 'All fields are required and price must be greater than 0';
    } else {
        if ($id) {
            // Update existing product
            $stmt = $pdo->prepare('
                UPDATE products 
                SET name = ?, description = ?, price = ?, category_id = ?, image_url = ? 
                WHERE id = ?
            ');
            if ($stmt->execute([$name, $description, $price, $category_id, $image_url, $id])) {
                $message = 'Product updated successfully';
            } else {
                $error = 'Error updating product';
            }
        } else {
            // Create new product
            $stmt = $pdo->prepare('
                INSERT INTO products (name, description, price, category_id, image_url) 
                VALUES (?, ?, ?, ?, ?)
            ');
            if ($stmt->execute([$name, $description, $price, $category_id, $image_url])) {
                $message = 'Product created successfully';
            } else {
                $error = 'Error creating product';
            }
        }
    }
}

// Fetch categories for dropdown
$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();

// Fetch products
$products = $pdo->query('
    SELECT p.*, c.name as category_name 
    FROM products p 
    JOIN categories c ON p.category_id = c.id 
    ORDER BY p.name
')->fetchAll();

// Get product for editing if ID is provided
$editing_product = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $editing_product = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .admin-nav {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .admin-nav a {
            margin-right: 20px;
            text-decoration: none;
            color: #007bff;
        }
        .product-form {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .form-group textarea {
            height: 100px;
        }
        .submit-btn {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .submit-btn:hover {
            background: #0056b3;
        }
        .message {
            color: green;
            margin-bottom: 15px;
        }
        .error {
            color: red;
            margin-bottom: 15px;
        }
        .product-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .product-table th, .product-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .product-table th {
            background: #f8f9fa;
        }
        .action-btn {
            padding: 5px 10px;
            border-radius: 4px;
            text-decoration: none;
            color: white;
            margin-right: 5px;
        }
        .edit-btn { background: #28a745; }
        .delete-btn { background: #dc3545; }
        .cancel-btn {
            background: #6c757d;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <h1>Manage Products</h1>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>

        <nav class="admin-nav">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_products.php">Manage Products</a>
            <a href="admin_categories.php">Manage Categories</a>
            <a href="index.php" target="_blank">View Site</a>
            
        </nav>

        <?php if ($message): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <?php if ($error): ?>
            <p class="error"><?php echo htmlspecialchars($error); ?></p>
        <?php endif; ?>

        <div class="product-form">
            <h2><?php echo $editing_product ? 'Edit Product' : 'Add New Product'; ?></h2>
            <form method="POST" action="">
                <?php if ($editing_product): ?>
                    <input type="hidden" name="id" value="<?php echo $editing_product['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Product Name:</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($editing_product['name'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="description">Description:</label>
                    <textarea id="description" name="description" required><?php 
                        echo htmlspecialchars($editing_product['description'] ?? ''); 
                    ?></textarea>
                </div>

                <div class="form-group">
                    <label for="price">Price:</label>
                    <input type="number" id="price" name="price" step="0.01" required 
                           value="<?php echo htmlspecialchars($editing_product['price'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="category_id">Category:</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Select a category</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['id']; ?>" 
                                    <?php echo ($editing_product['category_id'] ?? '') == $category['id'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="image_url">Image URL:</label>
                    <input type="url" id="image_url" name="image_url" 
                           value="<?php echo htmlspecialchars($editing_product['image_url'] ?? ''); ?>">
                </div>

                <button type="submit" class="submit-btn">
                    <?php echo $editing_product ? 'Update Product' : 'Add Product'; ?>
                </button>
                <?php if ($editing_product): ?>
                    <a href="admin_products.php" class="action-btn cancel-btn">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Product List</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                <tr>
                    <td><?php echo htmlspecialchars($product['name']); ?></td>
                    <td><?php echo htmlspecialchars($product['category_name']); ?></td>
                    <td>$<?php echo number_format($product['price'], 2); ?></td>
                    <td>
                        <a href="admin_products.php?action=edit&id=<?php echo $product['id']; ?>" 
                           class="action-btn edit-btn">Edit</a>
                        <a href="admin_products.php?action=delete&id=<?php echo $product['id']; ?>" 
                           class="action-btn delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 