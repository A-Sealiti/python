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
    // Check if category has products
    $stmt = $pdo->prepare('SELECT COUNT(*) FROM products WHERE category_id = ?');
    $stmt->execute([$_GET['id']]);
    $product_count = $stmt->fetchColumn();

    if ($product_count > 0) {
        $error = 'Cannot delete category with existing products';
    } else {
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        if ($stmt->execute([$_GET['id']])) {
            $message = 'Category deleted successfully';
        } else {
            $error = 'Error deleting category';
        }
    }
}

// Handle Create/Update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = trim($_POST['name'] ?? '');

    // Validate input
    if (empty($name)) {
        $error = 'Category name is required';
    } else {
        if ($id) {
            // Update existing category
            $stmt = $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
            if ($stmt->execute([$name, $id])) {
                $message = 'Category updated successfully';
            } else {
                $error = 'Error updating category';
            }
        } else {
            // Create new category
            $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
            if ($stmt->execute([$name])) {
                $message = 'Category created successfully';
            } else {
                $error = 'Error creating category';
            }
        }
    }
}

// Fetch categories
$categories = $pdo->query('SELECT * FROM categories ORDER BY name')->fetchAll();

// Get category for editing if ID is provided
$editing_category = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([$_GET['id']]);
    $editing_category = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
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
        .category-form {
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
        .form-group input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
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
        .category-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .category-table th, .category-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .category-table th {
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
            <h1>Manage Categories</h1>
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

        <div class="category-form">
            <h2><?php echo $editing_category ? 'Edit Category' : 'Add New Category'; ?></h2>
            <form method="POST" action="">
                <?php if ($editing_category): ?>
                    <input type="hidden" name="id" value="<?php echo $editing_category['id']; ?>">
                <?php endif; ?>
                
                <div class="form-group">
                    <label for="name">Category Name:</label>
                    <input type="text" id="name" name="name" required 
                           value="<?php echo htmlspecialchars($editing_category['name'] ?? ''); ?>">
                </div>

                <button type="submit" class="submit-btn">
                    <?php echo $editing_category ? 'Update Category' : 'Add Category'; ?>
                </button>
                <?php if ($editing_category): ?>
                    <a href="admin_categories.php" class="action-btn cancel-btn">Cancel</a>
                <?php endif; ?>
            </form>
        </div>

        <h2>Category List</h2>
        <table class="category-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                    <td>
                        <a href="admin_categories.php?action=edit&id=<?php echo $category['id']; ?>" 
                           class="action-btn edit-btn">Edit</a>
                        <a href="admin_categories.php?action=delete&id=<?php echo $category['id']; ?>" 
                           class="action-btn delete-btn" 
                           onclick="return confirm('Are you sure you want to delete this category?')">Delete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 