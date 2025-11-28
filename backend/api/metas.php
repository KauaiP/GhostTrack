<?php
ini_set('display_errors', 0);
error_reporting(E_ALL);

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

try {
    $database = new Database();
    $db = $database->getConnection();
    
    if ($db === null) throw new Exception("Erro de conexao com o banco.");
    
    $meta = new Meta($db);
    $response = new Response();
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($method) {
        case "GET":
            if (!isset($_GET["usuario_id"])) $response->error("Faltou usuario_id.");
            $result = $meta->listarPorUsuario($_GET["usuario_id"]);
            $response->success($result);
            break;

        case "POST":
            $dados = json_decode(file_get_contents("php://input"));
            if (!$dados) throw new Exception("JSON invalido.");

            $acao = $dados->acao ?? null;

            if ($acao === 'atualizar') {
                if (!isset($dados->id)) throw new Exception("ID obrigatorio.");
                $meta->id = $dados->id;
                $meta->titulo = $dados->titulo ?? null;
                $meta->descricao = $dados->descricao ?? null;
                $meta->progresso = $dados->progresso ?? 0;
                $meta->status = $dados->status ?? "em_andamento";
                if ($meta->atualizar()) $response->success([], "Atualizado!");
                else throw new Exception("Falha ao atualizar.");
            }

            if ($acao === 'deletar') {
                $id = $dados->id ?? null;
                if (!$id) throw new Exception("ID obrigatorio.");
                $meta->id = $id;
                if ($meta->deletar()) $response->success([], "Deletado!");
                else throw new Exception("Falha ao deletar.");
            }

            if (empty($dados->titulo) || empty($dados->usuario_id)) {
                throw new Exception("Titulo e Usuario ID obrigatorios.");
            }

            $meta->usuario_id = $dados->usuario_id;
            $meta->titulo = $dados->titulo;
            $meta->descricao = $dados->descricao ?? "";
            $meta->categoria = $dados->categoria ?? "pessoal";
            $meta->status = "nao_concluida";
            $meta->valor = $dados->valor ?? 0;
            $meta->unidade = $dados->unidade ?? "un";
            $meta->data_inicio = $dados->data_inicio ?? date('Y-m-d');
            $meta->data_conclusao = $dados->data_conclusao ?? null;
            $meta->progresso = 0;

            if ($meta->criar()) $response->success([], "Criado com sucesso!");
            else throw new Exception("Erro ao criar.");
            break;

        case "PUT":
            $dados = json_decode(file_get_contents("php://input"));
            if (!isset($dados->id)) throw new Exception("ID obrigatorio.");
            $meta->id = $dados->id;
            $meta->titulo = $dados->titulo ?? null;
            $meta->descricao = $dados->descricao ?? null;
            $meta->progresso = $dados->progresso ?? 0;
            $meta->status = $dados->status ?? "em_andamento"; 
            if ($meta->atualizar()) $response->success([], "Atualizado!");
            else throw new Exception("Falha ao atualizar.");
            break;

        case "DELETE":
            $id = $_GET['id'] ?? json_decode(file_get_contents("php://input"))->id ?? null;
            if (!$id) throw new Exception("ID obrigatorio.");
            $meta->id = $id;
            if ($meta->deletar()) $response->success([], "Deletado!");
            else throw new Exception("Falha ao deletar.");
            break;
            
        default:
            $response->error("Metodo invalido", 405);
    }

} catch (Throwable $e) { 
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro Fatal: " . $e->getMessage()
    ]);
}
?>