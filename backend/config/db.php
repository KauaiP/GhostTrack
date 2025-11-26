<?php
class Database {
    private $host = "127.0.0.1";
    private $db_name = "ghosttrack";
    
    private $username = "admin";
    private $password = "123456"; 
    
    public $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name,
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("set names utf8");
        } catch (PDOException $exception) {
            echo "Erro na conexão: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>