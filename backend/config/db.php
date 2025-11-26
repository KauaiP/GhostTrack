<?php
class Database {
    private $host = "127.0.0.1";
    private $db_name = "ghosttrack";
    
    // MUDANÇA AQUI: Trocamos root pelo usuário que criamos
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
            // O erro JSON parse acontece porque esse echo imprime texto simples
            // antes do JSON. Agora com a senha certa, isso não vai mais acontecer.
            echo "Erro na conexão: " . $exception->getMessage();
        }

        return $this->conn;
    }
}
?>