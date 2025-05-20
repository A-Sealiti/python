<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <title>AI Recept Generator</title>
    <style>
        body {
            background: linear-gradient(135deg, #1a2a6c, #b21f1f, #fdbb2d);
            background-size: 400% 400%;
            animation: gradient 15s ease infinite;
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            max-width: 800px;
        }

        .recipe-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            margin-top: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        h1 {
            color: #2c3e50;
            font-weight: 700;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
        }

        .form-label {
            color: #34495e;
            font-weight: 600;
        }

        .form-control {
            border-radius: 10px;
            border: 2px solid #e0e0e0;
            padding: 0.8rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }

        .btn-primary {
            background: linear-gradient(45deg, #3498db, #2980b9);
            border: none;
            border-radius: 10px;
            padding: 0.8rem;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(52, 152, 219, 0.4);
        }

        .recipe-meta {
            display: flex;
            gap: 1rem;
            margin: 1.5rem 0;
            flex-wrap: wrap;
        }

        .recipe-meta span {
            background: #f8f9fa;
            padding: 0.7rem 1.2rem;
            border-radius: 25px;
            font-size: 0.9rem;
            color: #2c3e50;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }

        .recipe-meta span:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .ingredients, .instructions {
            margin-top: 2rem;
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 15px;
        }

        .ingredients h3, .instructions h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .ingredients ul {
            list-style-type: none;
            padding-left: 0;
        }

        .ingredients li {
            padding: 0.8rem 0;
            border-bottom: 1px solid #e0e0e0;
            color: #34495e;
        }

        .ingredients li:last-child {
            border-bottom: none;
        }

        .instructions ol {
            padding-left: 1.2rem;
        }

        .instructions li {
            margin-bottom: 1rem;
            color: #34495e;
            line-height: 1.6;
        }

        .alert {
            border-radius: 15px;
            margin-top: 1.5rem;
            padding: 1rem;
            border: none;
        }

        .alert-danger {
            background-color: #fee2e2;
            color: #dc2626;
        }

        .recipe {
            animation: fadeIn 0.5s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive aanpassingen */
        @media (max-width: 768px) {
            .recipe-card {
                padding: 1.5rem;
                margin: 1rem;
            }

            .recipe-meta {
                justify-content: center;
            }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center py-5">
    <div class="container">
        <div class="recipe-card">
            <h1 class="text-center mb-4">AI Recept Generator</h1>
            <p class="text-center mb-4">Voer hieronder je ingrediënten in en ontvang een recept!</p>

            <form action="process.php" method="POST">
                <div class="form-group">
                    <label for="ingredients" class="form-label">Ingrediënten (gescheiden door komma's):</label>
                    <textarea id="ingredients" class="form-control mb-3" name="ingredients" rows="4" required 
                        placeholder="bijv. ui, knoflook, tomaat, pasta"></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">
                    <i class="fas fa-utensils me-2"></i>Genereer Recept
                </button>
            </form>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger mt-4">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?php echo htmlspecialchars($_GET['error']) ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_GET['recipe'])): ?>
                <div class="mt-4">
                    <?php echo $_GET['recipe'] ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>