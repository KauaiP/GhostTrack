<?php
class Meta {
    private $conn;
    private $table = "metas";

    public $id;
    public $usuario_id;
    public $titulo;
    public $descricao;
    public $categoria;
    public $valor;
    public $unidade;
    public $data_inicio;
    public $data_conclusao;
    public $progresso;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function criar() {
        $sql = "INSERT INTO {$this->table} (usuario_id, titulo, descricao, categoria, valor, unidade, data_inicio, data_conclusao, progresso)
                VALUES (:usuario_id, :titulo, :descricao, :categoria, :valor, :unidade, :data_inicio, :data_conclusao, :progresso)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":categoria", $this->progresso);
        $stmt->bindParam(":valor", $this->progresso);
        $stmt->bindParam(":unidade", $this->progresso);
        $stmt->bindParam(":data_inicio", $this->progresso);
        $stmt->bindParam(":data_conclusao", $this->progresso);
        $stmt->bindParam(":progresso", $this->progresso);        

        return $stmt->execute();
    }

    public function listarPorUsuario() {
        $sql = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->execute();
        return $stmt;
    }

    public function atualizarProgresso() {
        $sql = "UPDATE {$this->table} 
                SET progresso = :progresso 
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":progresso", $this->progresso);
        $stmt->bindParam(":id", $this->id);

        return $stmt->execute();
    }

    public function deletar() {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":id", $this->id);
        return $stmt->execute();
    }
}
