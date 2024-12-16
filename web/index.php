<?php
$host = getenv(name: 'DB_HOST') ?: 'db';
$dbname = getenv(name: 'DB_NAME') ?: 'ecommerce_db';
$username = getenv(name: 'DB_USER') ?: 'ecommerce_user';
$password = getenv(name: 'DB_PASS') ?: 'ecommerce_password';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", username: $username, password: $password);
    $pdo->setAttribute(attribute: PDO::ATTR_ERRMODE, value: PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query('SELECT * FROM products');
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Databaseverbinding mislukt: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Onze Producteen</title>
    <style>
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            width: 250px;
            text-align: center;
        }
    </style>
</head>
<body>
    <h1>Onze Producten</h1>
    <div class="product-grid">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h2><?= htmlspecialchars(string: $product['name']) ?></h2>
                <p><?= htmlspecialchars(string: $product['description']) ?></p>
                <p>Prijs: €<?= number_format(num: $product['price'], decimals: 2) ?></p>
                <p>Voorraad: <?= $product['stock_quantity'] ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>