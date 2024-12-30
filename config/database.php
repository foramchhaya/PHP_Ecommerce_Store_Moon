<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'moon_jewelry_store';
    private $username = 'root';
    private $password = '';
    private $conn = null;

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $dsn = "mysql:host={$this->host};dbname={$this->db_name};charset=utf8";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_PERSISTENT => true
                ];
                $this->conn = new PDO($dsn, $this->username, $this->password, $options);
            } catch (PDOException $exception) {
                error_log("Connection error: " . $exception->getMessage());
                die("Database connection could not be established.");
            }
        }
        return $this->conn;
    }
}
?>
