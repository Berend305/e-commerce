<?php
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    private $pdo;

    protected function setUp(): void
    {
        // Databaseverbinding instellen
        require_once __DIR__ . '/../web/config.php';
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;
        $this->pdo = new PDO($dsn, DB_USER, DB_PASSWORD);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Maak een schone 'products' tabel aan
        $this->pdo->exec("DROP TABLE IF EXISTS products");
        $this->pdo->exec("
            CREATE TABLE products (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100),
                price DECIMAL(10, 2)
            )
        ");
    }

    public function testWebserverDatabaseInteraction()
    {
        // 1. Voeg een product toe aan de database
        $stmt = $this->pdo->prepare("INSERT INTO products (name, price) VALUES (:name, :price)");
        $stmt->execute(['name' => 'Integration Test Product', 'price' => 99.99]);

        // 2. Simuleer een HTTP-verzoek naar de k3s webserver
        $webServerUrl = getenv('WEB_SERVER_URL');
        $response = file_get_contents($webServerUrl);

        // 3. Controleer of het product in de HTML-output voorkomt
        $this->assertStringContainsString("Integration Test Product", $response);
        $this->assertStringContainsString("99.99", $response);
    }

    public function testInvalidDatabaseConnection()
    {
        // Test een verkeerde databaseverbinding
        $this->expectException(PDOException::class);

        $dsn = "mysql:host=invalid-host;dbname=" . DB_NAME;
        new PDO($dsn, DB_USER, DB_PASSWORD);
    }

    public function testEmptyDatabaseOutput()
    {
        // Simuleer een HTTP-verzoek naar de webserver met een lege database
        $webServerUrl = getenv('WEB_SERVER_URL');
        $response = file_get_contents($webServerUrl);

        // Controleer dat er geen producten in de output staan
        $this->assertStringNotContainsString("Product", $response);
    }
}
