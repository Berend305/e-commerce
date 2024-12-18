<?php
// Test CRUD-functionaliteit en veiligheid van de database
use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    private $pdo;

    // SetUp wordt voor elke test uitgevoerd om een schone products tabel aan te maken.
    protected function setUp(): void
    {
        require_once __DIR__ . '/../web/config.php';

        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Maak een schone tabel voor elke test
        $this->pdo->exec("DROP TABLE IF EXISTS products");
        $this->pdo->exec("
            CREATE TABLE products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                price DECIMAL(10, 2)
            )
        ");
    }

    // Test of een product succesvol wordt toegevoegd.
    public function testCreateProduct()
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
        $stmt->execute(['name' => 'Test Product', 'price' => 19.99]);

        $this->assertEquals(1, $stmt->rowCount(), "Failed to insert product.");
    }

    // Test of een product correct wordt opgehaald.
    public function testReadProduct()
    {
        $this->pdo->exec("INSERT INTO products (name, price) VALUES ('Test Product', 19.99)");
        
        $stmt = $this->pdo->query("SELECT * FROM products WHERE name = 'Test Product'");
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals('Test Product', $product['name']);
        $this->assertEquals(19.99, $product['price']);
    }

    // Test of een product correct wordt bijgewerkt.
    public function testUpdateProduct()
    {
        $this->pdo->exec("INSERT INTO products (name, price) VALUES ('Test Product', 19.99)");

        $stmt = $this->pdo->prepare("UPDATE products SET price = :price WHERE name = :name");
        $stmt->execute(['price' => 29.99, 'name' => 'Test Product']);

        $stmt = $this->pdo->query("SELECT price FROM products WHERE name = 'Test Product'");
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(29.99, $product['price']);
    }

    // Test of een product succesvol wordt verwijderd.
    public function testDeleteProduct()
    {
        $this->pdo->exec("INSERT INTO products (name, price) VALUES ('Test Product', 19.99)");

        $stmt = $this->pdo->prepare("DELETE FROM products WHERE name = :name");
        $stmt->execute(['name' => 'Test Product']);

        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM products");
        $count = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertEquals(0, $count['count'], "Product was not deleted.");
    }

    // Test of de tabel leeg is.
    public function testEmptyTable()
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        $products = $stmt->fetchAll();

        $this->assertEmpty($products, "The table is not empty.");
    }

    // Test of een ongeldige invoer een PDOException veroorzaakt.
    public function testInvalidInput()
    {
        $this->expectException(PDOException::class);

        $stmt = $this->pdo->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
        $stmt->execute(['name' => null, 'price' => 'invalid']);
    }
}
?>