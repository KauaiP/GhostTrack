<?php
// ATIVAR MODO DE DEBUG MÁXIMO
ini_set('display_errors', 0); // Não mostra erros na tela (quebra o JSON)
ini_set('log_errors', 1);     // Loga erros
error_reporting(E_ALL);       // Reporta tudo

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

try {
    // 1. Tratamento CORS
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    // 2. Definição e Verificação de Caminhos
    $baseDir = __DIR__ . "/../";
    $dbPath = $baseDir . "config/db.php";
    $userPath = $baseDir . "models/Usuario.php";
    $responsePath = $baseDir . "utils/Response.php";

    if (!file_exists($dbPath)) throw new Exception("Arquivo db.php não encontrado em: $dbPath");
    if (!file_exists($userPath)) throw new Exception("Arquivo Usuario.php não encontrado em: $userPath");
    if (!file_exists($responsePath)) throw new Exception("Arquivo Response.php não encontrado em: $responsePath");

    // 3. Includes
    require_once $dbPath;
    require_once $userPath;
    require_once $responsePath;

    // 4. Inicialização
    // Verifica se as classes existem
    if (!class_exists('Database')) throw new Exception("Classe Database não foi carregada.");
    if (!class_exists('Usuario')) throw new Exception("Classe Usuario não foi carregada.");

    $database = new Database();
    $db = $database->getConnection();
    
    // Teste de conexão imediato
    if ($db === null) {
        throw new Exception("Falha ao conectar no Banco de Dados. Verifique senha/usuário no db.php");
    }

    $usuario = new Usuario($db);
    $response = new Response();

    // 5. Processamento da Requisição
    $method = $_SERVER["REQUEST_METHOD"];

    if ($method == "POST") {
        // Lê o corpo da requisição
        $jsonRaw = file_get_contents("php://input");
        $dados = json_decode($jsonRaw);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("JSON inválido enviado pelo frontend.");
        }

        if (!$dados) {
            throw new Exception("Nenhum dado recebido.");
        }

        $acao = $dados->acao ?? 'cadastrar';

        // --- LOGIN ---
        if ($acao == 'login') {
            if (!isset($dados->email) || !isset($dados->senha)) {
                throw new Exception("Email e senha obrigatórios.");
            }

            $usuario->email = trim($dados->email);
            $stmt = $usuario->login();

            if ($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if (password_verify($dados->senha, $row['senha'])) {
                    unset($row['senha']);
                    $response->success($row, "Login realizado!");
                } else {
                    $response->error("Senha incorreta.", 401);
                }
            } else {
                $response->error("Usuário não encontrado.", 404);
            }
        } 
        
        // --- CADASTRO ---
        else {
            // Validação estrita
            if (empty($dados->nome) || empty($dados->email) || empty($dados->senha)) {
                throw new Exception("Preencha todos os campos (nome, email, senha).");
            }

            $usuario->nome = trim($dados->nome);
            $usuario->email = trim($dados->email);
            $usuario->senha = password_hash(trim($dados->senha), PASSWORD_DEFAULT);

            if ($usuario->criar()) {
                $response->success([], "Conta criada com sucesso!");
            } else {
                // Se falhar, geralmente é email duplicado
                $response->error("Não foi possível criar a conta. O email já pode estar em uso.");
            }
        }
    } 
    elseif ($method == "GET") {
        // Código para teste apenas
        $response->success([], "API funcionando. Use POST para login/cadastro.");
    }
    else {
        $response->error("Método não permitido", 405);
    }

} catch (Exception $e) {
    // CAPTURA QUALQUER ERRO FATAL E RETORNA COMO JSON
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erro Crítico: " . $e->getMessage()
    ]);
}
?>