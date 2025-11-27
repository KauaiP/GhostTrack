<?php
class Usuario {
    private $conn;
    private $table = "usuarios";

    public $id;
    public $nome;
    public $email;
    public $senha;

    public function __construct($db) {
        $this->conn = $db;
    }

    // 1. Criar usuário (Cadastro)
    public function criar() {
        // Verifica se email já existe
        $checkQuery = "SELECT id FROM " . $this->table . " WHERE email = :email LIMIT 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(':email', $this->email);
        $checkStmt->execute();
        
        if($checkStmt->rowCount() > 0) {
            return false; // Email já existe
        }

        $sql = "INSERT INTO {$this->table} (nome, email, senha) VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);

        return $stmt->execute();
    }

    // 2. Login (Verificar credenciais)
    public function login() {
        $sql = "SELECT id, nome, email, senha FROM {$this->table} WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':email', $this->email);
        $stmt->execute();
        return $stmt;
    }

    // 3. Listar todos (EVITAR O ERRO 500)
    public function listar() {
        $sql = "SELECT id, nome, email FROM {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    // 4. Ler um único usuário
    public function lerUm() {
        $sql = "SELECT id, nome, email FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        return $stmt;
    }
}
?>