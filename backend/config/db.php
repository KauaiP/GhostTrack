<?php
class Database {
    // PREENCHA COM DADOS DO PAINEL
    private $host = "sql201.infinityfree.com"; // MySQL Host Name
    private $db_name = "if0_40540057_ghosttrackdb"; // MySQL Database Name (tem prefixo!)
    private $username = "if0_40540057"; // MySQL User Name
    private $password = "1flXVtfvhEhO"; // MySQL Password
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
            // Importante: Lança uma exceção para o Controller pegar
            throw new Exception("Erro de Conexão SQL: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>