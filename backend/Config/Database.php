<?php
class Database {
    public $host = "localhost";
    public $db_name = "navigo_db";
    public $username = "root";
    public $password = "";
    public $conn;

    // Get database connection
    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",
                $this->username,
                $this->password,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                )
            );
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }

    // Test database connection
    public function testConnection() {
        try {
            $this->getConnection();
            return $this->conn !== null;
        } catch(Exception $e) {
            return false;
        }
    }

    // Close database connection
    public function closeConnection() {
        $this->conn = null;
    }
}
?>