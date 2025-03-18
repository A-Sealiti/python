<?php
require_once 'config.php';
session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: admin_login.php');
    exit;
}

// Fetch some sample data (modify these queries according to your database structure)
try {
    // Example: Monthly sales data
    $monthlyData = $pdo->query("SELECT 
        DATE_FORMAT(created_at, '%Y-%m') as month,
        COUNT(*) as total_orders,
        SUM(amount) as revenue
        FROM orders 
        GROUP BY DATE_FORMAT(created_at, '%Y-%m')
        ORDER BY month DESC LIMIT 6")->fetchAll(PDO::FETCH_ASSOC);

    // Example: Top products data
    $topProducts = $pdo->query("SELECT 
        product_name,
        COUNT(*) as sales_count
        FROM order_items 
        GROUP BY product_name 
        ORDER BY sales_count DESC 
        LIMIT 5")->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // For demo purposes, create sample data if tables don't exist
    $monthlyData = [
        ['month' => '2024-03', 'total_orders' => 150, 'revenue' => 15000],
        ['month' => '2024-02', 'total_orders' => 120, 'revenue' => 12000],
        ['month' => '2024-01', 'total_orders' => 100, 'revenue' => 10000],
    ];
    
    $topProducts = [
        ['product_name' => 'Product A', 'sales_count' => 50],
        ['product_name' => 'Product B', 'sales_count' => 40],
        ['product_name' => 'Product C', 'sales_count' => 30],
        ['product_name' => 'Product D', 'sales_count' => 20],
        ['product_name' => 'Product E', 'sales_count' => 10],
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
        }
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        .charts-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .chart-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .logout-btn {
            background: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
        }
        .logout-btn:hover {
            background: #c82333;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <h1>Admin Dashboard</h1>
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
        <nav class="admin-nav">
            <a href="admin_dashboard.php">Dashboard</a>
            <a href="admin_products.php">Manage Products</a>
            <a href="admin_categories.php">Manage Categories</a>
            <a href="index.php" target="_blank">View Site</a>
            
        </nav>  
        <div class="charts-grid">
            <div class="chart-container">
                <h2>Monthly Orders</h2>
                <canvas id="ordersChart"></canvas>
            </div>
            <div class="chart-container">
                <h2>Top Products</h2>
                <canvas id="productsChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Prepare data for monthly orders chart
        const monthlyData = <?php echo json_encode($monthlyData); ?>;
        const months = monthlyData.map(data => data.month);
        const orders = monthlyData.map(data => data.total_orders);
        const revenue = monthlyData.map(data => data.revenue);

        // Monthly Orders Chart
        new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Orders',
                    data: orders,
                    borderColor: '#007bff',
                    tension: 0.1
                }, {
                    label: 'Revenue ($)',
                    data: revenue,
                    borderColor: '#28a745',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Top Products Chart
        const productsData = <?php echo json_encode($topProducts); ?>;
        new Chart(document.getElementById('productsChart'), {
            type: 'bar',
            data: {
                labels: productsData.map(data => data.product_name),
                datasets: [{
                    label: 'Sales Count',
                    data: productsData.map(data => data.sales_count),
                    backgroundColor: '#007bff'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html> 