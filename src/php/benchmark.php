<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

class CitusBenchmark
{
    private $connection;
    private $host;
    private $port;
    private $database;
    private $username;
    private $password;

    public function __construct()
    {
        $this->host = $_ENV['PGHOST'] ?? 'localhost';
        $this->port = $_ENV['PGPORT'] ?? '5432';
        $this->database = $_ENV['PGDATABASE'] ?? 'postgres';
        $this->username = $_ENV['PGUSER'] ?? 'postgres';
        $this->password = $_ENV['PGPASSWORD'] ?? 'postgres';
    }

    /**
     * Establish database connection
     */
    private function connect(): void
    {
        $dsn = "pgsql:host={$this->host};port={$this->port};dbname={$this->database}";
        
        try {
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_EMULATE_PREPARES => false
            ]);
            echo "Database connection successful...\n";
        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage() . "\n");
        }
    }

    /**
     * Close database connection
     */
    private function disconnect(): void
    {
        $this->connection = null;
        echo "Database connection closed...\n";
    }

    /**
     * Create users table if not exists
     */
    private function createTable(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE
            )
        ";
        
        try {
            $this->connection->exec($sql);
            echo "Users table ready...\n";
        } catch (PDOException $e) {
            echo "Error creating table: " . $e->getMessage() . "\n";
        }
    }

    /**
     * Insert users with transaction for better performance
     */
    public function insertUsers(int $count = 100000): void
    {
        $this->connect();
        $this->createTable();

        // Start timing
        $startTime = microtime(true);
        echo "Starting insert benchmark...\n";

        try {
            // Begin transaction
            $this->connection->beginTransaction();

            // Prepare the insert statement
            $stmt = $this->connection->prepare('INSERT INTO users (id, name, email) VALUES (?, ?, ?)');

            for ($i = 1; $i <= $count; $i++) {
                $name = "User{$i}";
                $email = "user{$i}@test.com";
                
                $stmt->execute([$i, $name, $email]);

                // Progress logging every 1000 records
                if ($i % 1000 === 0) {
                    echo "Inserted: {$i}\n";
                }
            }

            // Commit transaction
            $this->connection->commit();
            
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            echo "Insert completed successfully!\n";
            echo "Total time: {$duration} seconds\n";
            echo "Records per second: " . round($count / $duration, 2) . "\n";

        } catch (PDOException $e) {
            // Rollback on error
            $this->connection->rollback();
            echo "Error during insert: " . $e->getMessage() . "\n";
        } finally {
            $this->disconnect();
        }
    }

    /**
     * Insert users using batch insert for better performance
     */
    public function insertUsersBatch(int $count = 100000, int $batchSize = 1000): void
    {
        $this->connect();
        $this->createTable();

        // Start timing
        $startTime = microtime(true);
        echo "Starting batch insert benchmark...\n";

        try {
            // Begin transaction
            $this->connection->beginTransaction();

            $inserted = 0;
            $batch = [];

            for ($i = 1; $i <= $count; $i++) {
                $batch[] = [
                    'id' => $i,
                    'name' => "User{$i}",
                    'email' => "user{$i}@test.com"
                ];

                // Execute batch when it reaches batch size or at the end
                if (count($batch) === $batchSize || $i === $count) {
                    $this->executeBatch($batch);
                    $inserted += count($batch);
                    echo "Inserted: {$inserted}\n";
                    $batch = [];
                }
            }

            // Commit transaction
            $this->connection->commit();
            
            $endTime = microtime(true);
            $duration = round($endTime - $startTime, 2);
            echo "Batch insert completed successfully!\n";
            echo "Total time: {$duration} seconds\n";
            echo "Records per second: " . round($count / $duration, 2) . "\n";

        } catch (PDOException $e) {
            // Rollback on error
            $this->connection->rollback();
            echo "Error during batch insert: " . $e->getMessage() . "\n";
        } finally {
            $this->disconnect();
        }
    }

    /**
     * Execute batch insert
     */
    private function executeBatch(array $batch): void
    {
        if (empty($batch)) {
            return;
        }

        $placeholders = [];
        $values = [];

        foreach ($batch as $row) {
            $placeholders[] = '(?, ?, ?)';
            $values[] = $row['id'];
            $values[] = $row['name'];
            $values[] = $row['email'];
        }

        $sql = 'INSERT INTO users (id, name, email) VALUES ' . implode(', ', $placeholders);
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($values);
    }

    /**
     * Test database connection
     */
    public function testConnection(): void
    {
        $this->connect();
        
        try {
            $stmt = $this->connection->query('SELECT version()');
            $version = $stmt->fetchColumn();
            echo "PostgreSQL Version: {$version}\n";
            
            // Test if Citus extension is available
            $stmt = $this->connection->query("SELECT * FROM pg_extension WHERE extname = 'citus'");
            $citus = $stmt->fetch();
            
            if ($citus) {
                echo "Citus extension is installed and active\n";
            } else {
                echo "Citus extension not found\n";
            }
            
        } catch (PDOException $e) {
            echo "Error testing connection: " . $e->getMessage() . "\n";
        } finally {
            $this->disconnect();
        }
    }

    /**
     * Clean up - truncate users table
     */
    public function cleanup(): void
    {
        $this->connect();
        
        try {
            $this->connection->exec('TRUNCATE TABLE users RESTART IDENTITY');
            echo "Users table truncated successfully\n";
        } catch (PDOException $e) {
            echo "Error during cleanup: " . $e->getMessage() . "\n";
        } finally {
            $this->disconnect();
        }
    }
}

// Main execution
if (php_sapi_name() === 'cli') {
    $benchmark = new CitusBenchmark();
    
    // Parse command line arguments
    $options = getopt('', ['test', 'cleanup', 'batch', 'count:', 'batch-size:']);
    
    if (isset($options['test'])) {
        $benchmark->testConnection();
    } elseif (isset($options['cleanup'])) {
        $benchmark->cleanup();
    } elseif (isset($options['batch'])) {
        $count = isset($options['count']) ? (int)$options['count'] : 100000;
        $batchSize = isset($options['batch-size']) ? (int)$options['batch-size'] : 1000;
        $benchmark->insertUsersBatch($count, $batchSize);
    } else {
        $count = isset($options['count']) ? (int)$options['count'] : 100000;
        $benchmark->insertUsers($count);
    }
}
