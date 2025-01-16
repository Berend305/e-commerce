<?php
require 'config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
    $pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "SELECT id, name, price FROM products";
    $stmt = $pdo->query($query);

    echo "<h1>Product List</h1><ul>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<li>{$row['name']} - \${$row['price']}</li>";
    }
    echo "</ul>";
} catch (PDOException $e) {
    echo "Database connection failed: " . $e->getMessage();
}
?>

