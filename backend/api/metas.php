<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once "../config/db.php";
require_once "../models/Meta.php";
require_once "../utils/Response.php";

$db = new Database();
$conn = $db->getConnection(); 

$meta = new Meta($conn);
$response = new Response();

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {
    case "GET":
        if (!isset($_GET["usuario_id"])) $response->error("Faltou usuario_id.");
        $result = $meta->listarPorUsuario($_GET["usuario_id"]);
        $response->success($result);
        break;

    case "POST":
        $dados = json_decode(file_get_contents("php://input"));
        if (!isset($dados->titulo) || !isset($dados->usuario_id)) $response->error("Dados incompletos.");

        $meta->usuario_id = $dados->usuario_id;
        $meta->titulo = $dados->titulo;
        $meta->descricao = $dados->descricao ?? "";
        $meta->categoria = $dados->categoria ?? "pessoal"; 
        $meta->status = "nao_concluida"; 
        $meta->valor = $dados->valor ?? 0;
        $meta->unidade = $dados->unidade ?? "un";
        $meta->data_inicio = $dados->data_inicio ?? date('Y-m-d');
        $meta->data_conclusao = $dados->data_conclusao ?? null;
        $meta->progresso = $dados->progresso ?? 0;

        if ($meta->criar()) $response->success([], "Criado com sucesso!");
        else $response->error("Erro ao criar.");
        break;

    case "PUT":
        $dados = json_decode(file_get_contents("php://input"));
        if (!isset($dados->id)) $response->error("ID obrigatório.");

        $meta->id = $dados->id;
        $meta->titulo = $dados->titulo;
        $meta->descricao = $dados->descricao;
        $meta->progresso = $dados->progresso ?? 0;
        $meta->status = $dados->status ?? "em_andamento"; 

        if ($meta->atualizar()) $response->success([], "Atualizado!");
        else $response->error("Erro ao atualizar.");
        break;

    case "DELETE":
        $id = $_GET['id'] ?? json_decode(file_get_contents("php://input"))->id ?? null;
        if (!$id) $response->error("ID obrigatório.");
        $meta->id = $id;
        if ($meta->deletar()) $response->success([], "Deletado!");
        else $response->error("Erro ao deletar.");
        break;
}
?>