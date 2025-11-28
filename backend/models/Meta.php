<?php
class Meta {
    private $conn;
    private $table = "metas";

    public $id;
    public $usuario_id;
    public $titulo;
    public $descricao;
    public $status;
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
        $sql = "INSERT INTO {$this->table} 
                (usuario_id, titulo, descricao, status, categoria, valor, unidade, data_inicio, data_conclusao, progresso)
                VALUES (:usuario_id, :titulo, :descricao, :status, :categoria, :valor, :unidade, :data_inicio, :data_conclusao, :progresso)";

        $stmt = $this->conn->prepare($sql);

        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->descricao = htmlspecialchars(strip_tags($this->descricao));

        $stmt->bindParam(":usuario_id", $this->usuario_id);
        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":categoria", $this->categoria);
        $stmt->bindParam(":valor", $this->valor);
        $stmt->bindParam(":unidade", $this->unidade);
        $stmt->bindParam(":data_inicio", $this->data_inicio);
        $stmt->bindParam(":data_conclusao", $this->data_conclusao);
        $stmt->bindParam(":progresso", $this->progresso);        

        if($stmt->execute()) return true;
        
        return false;
    }

    public function listarPorUsuario($usuario_id) {
        $sql = "SELECT * FROM {$this->table} WHERE usuario_id = :usuario_id ORDER BY id DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(":usuario_id", $usuario_id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function atualizar() {
        $sql = "UPDATE {$this->table} 
                SET titulo = :titulo, 
                    descricao = :descricao, 
                    status = :status,
                    progresso = :progresso
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":descricao", $this->descricao);
        $stmt->bindParam(":status", $this->status);
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
?>