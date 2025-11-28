<?php
header("Content-Type: application/json");

require_once "../config/db.php";
require_once "../models/Usuario.php";
require_once "../utils/Response.php";

$database = new Database();
$db = $database->getConnection();

$usuario = new Usuario($db);
$response = new Response();

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    case "GET": // select em um usuário
        if (!empty($_GET["id"])) {
            $usuario->id = $_GET["id"];
            $stmt = $usuario->lerUm();
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($data) $response->success($data);
            else $response->error("Usuário não encontrado", 404);

        } else {
            $stmt = $usuario->listar();
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $response->success($usuarios);
        }
        break;

    case "POST":
        $dados = json_decode(file_get_contents("php://input"));

        if (!$dados->nome || !$dados->email || !$dados->senha) {
            $response->error("Dados insuficientes");
        }

        $usuario->nome = $dados->nome;
        $usuario->email = $dados->email;
        $usuario->senha = password_hash($dados->senha, PASSWORD_DEFAULT);

        if ($usuario->criar()) $response->success([], "Usuário criado");
        else $response->error("Erro ao criar usuário");

        break;

    case "DELETE":
        if (!isset($_GET["id"])) $response->error("ID obrigatório");

        $usuario->id = $_GET["id"];

        if ($usuario->deletar()) $response->success([], "Usuário deletado");
        else $response->error("Erro ao deletar usuário");

        break;

    default:
        $response->error("Método não permitido", 405);
}
