<?php
class Database {
    private $host = "sql201.infinityfree.com";
    private $db_name = "if0_40540057_ghosttrackdb";
    private $username = "if0_40540057";
    private $password = "1flXVtfvhEhO";
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
            throw new Exception("Erro de Conexão SQL: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>