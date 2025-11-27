<?php
// Configuração de Debug
ini_set('display_errors', 0);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // Caminhos
    $baseDir = __DIR__ . "/../";
    $dbPath = $baseDir . "config/db.php";
    $metaPath = $baseDir . "models/Meta.php";
    $responsePath = $baseDir . "utils/Response.php";

    if (!file_exists($dbPath)) throw new Exception("Arquivo db.php não encontrado.");
    if (!file_exists($metaPath)) throw new Exception("Arquivo Meta.php não encontrado.");
    if (!file_exists($responsePath)) throw new Exception("Arquivo Response.php não encontrado.");

    require_once $dbPath;
    require_once $metaPath;
    require_once $responsePath;

    $database = new Database();
    $db = $database->getConnection();
    
    if ($db === null) throw new Exception("Erro de conexão com o banco.");

    $meta = new Meta($db);
    $response = new Response();

    $method = $_SERVER["REQUEST_METHOD"];

    switch ($method) {
        case "GET":
            if (!isset($_GET["usuario_id"])) throw new Exception("Faltou usuario_id.");
            $result = $meta->listarPorUsuario($_GET["usuario_id"]);
            $response->success($result);
            break;

        case "POST":
            $jsonRaw = file_get_contents("php://input");
            $dados = json_decode($jsonRaw);

            if (!$dados) throw new Exception("JSON inválido.");
            
            // Validação
            if (empty($dados->titulo) || empty($dados->usuario_id)) {
                throw new Exception("Título e Usuario ID obrigatórios.");
            }

            // Atribuição de dados
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

            if ($meta->criar()) {
                $response->success([], "Meta criada!");
            } else {
                throw new Exception("Falha ao executar INSERT no banco.");
            }
            break;

        case "PUT":
            $dados = json_decode(file_get_contents("php://input"));
            if (!isset($dados->id)) throw new Exception("ID obrigatório.");

            $meta->id = $dados->id;
            $meta->titulo = $dados->titulo;
            $meta->descricao = $dados->descricao;
            $meta->progresso = $dados->progresso ?? 0;
            $meta->status = $dados->status ?? "em_andamento"; 

            if ($meta->atualizar()) $response->success([], "Atualizado!");
            else throw new Exception("Falha ao atualizar.");
            break;

        case "DELETE":
            $id = $_GET['id'] ?? json_decode(file_get_contents("php://input"))->id ?? null;
            if (!$id) throw new Exception("ID obrigatório.");
            $meta->id = $id;
            if ($meta->deletar()) $response->success([], "Deletado!");
            else throw new Exception("Falha ao deletar.");
            break;
            
        default:
            $response->error("Método inválido", 405);
    }

} catch (Throwable $e) { 
    // MUDANÇA IMPORTANTE: "Throwable" pega erros fatais que "Exception" não pega
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro Fatal: " . $e->getMessage()
    ]);
}
?>