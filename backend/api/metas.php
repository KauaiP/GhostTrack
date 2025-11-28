<?php
// Configuracao de Debug
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

    if (!file_exists($dbPath)) throw new Exception("Arquivo db.php nao encontrado.");
    if (!file_exists($metaPath)) throw new Exception("Arquivo Meta.php nao encontrado.");
    if (!file_exists($responsePath)) throw new Exception("Arquivo Response.php nao encontrado.");

$meta = new Meta($conn);
$response = new Response();

    $database = new Database();
    $db = $database->getConnection();
    
    if ($db === null) throw new Exception("Erro de conexao com o banco.");

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

            if (!$dados) throw new Exception("JSON invalido.");

            // Suporte a override de metodo (InfinityFree bloqueia PUT/DELETE)
            $acao = $dados->acao ?? null; // 'criar' | 'atualizar' | 'deletar'

            if ($acao === 'atualizar') {
                if (!isset($dados->id)) throw new Exception("ID obrigatorio.");
                $meta->id = $dados->id;
                $meta->titulo = $dados->titulo ?? null;
                $meta->descricao = $dados->descricao ?? null;
                $meta->progresso = $dados->progresso ?? 0;
                $meta->status = $dados->status ?? "em_andamento";
                if ($meta->atualizar()) $response->success([], "Atualizado!");
                else throw new Exception("Falha ao atualizar.");
                break;
            }

            if ($acao === 'deletar') {
                $id = $dados->id ?? null;
                if (!$id) throw new Exception("ID obrigatorio.");
                $meta->id = $id;
                if ($meta->deletar()) $response->success([], "Deletado!");
                else throw new Exception("Falha ao deletar.");
                break;
            }

            // Default: criar
            // Validacao
            if (empty($dados->titulo) || empty($dados->usuario_id)) {
                throw new Exception("Titulo e Usuario ID obrigatorios.");
            }

            // Atribuicao de dados
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

        if ($meta->atualizar()) $response->success([], "Atualizado!");
        else $response->error("Erro ao atualizar.");
        break;

        case "PUT":
            // Alguns hosts bloqueiam PUT; mantemos por compatibilidade
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