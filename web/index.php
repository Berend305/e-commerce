<?php
require 'config.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, price FROM products";
$result = $conn->query(query: $sql);

if ($result->num_rows > 0) {
    echo "<h1>Product List</h1><ul>";
    while($row = $result->fetch_assoc()) {
        echo "<li>" . $row["name"] . " - $" . $row["price"] . "</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No products found</p>";
}

$conn->close();
?>