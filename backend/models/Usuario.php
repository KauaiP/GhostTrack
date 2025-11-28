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

    public function criar() {
        $sql = "INSERT INTO {$this->table} (nome, email, senha) 
                VALUES (:nome, :email, :senha)";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":senha", $this->senha);

        return $stmt->execute();
    }

    public function listar() {
        $sql = "SELECT id, nome, email FROM {$this->table}";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function lerUm() {
        $sql = "SELECT id, nome, email FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        return $stmt;
    }

    public function deletar() {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
