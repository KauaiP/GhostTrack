<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once "../config/db.php";
require_once "../models/Meta.php";
require_once "../utils/Response.php";

$db = new Database();
$conn = $db->connect();

$meta = new Meta($conn);
$response = new Response();

$method = $_SERVER["REQUEST_METHOD"];

switch ($method) {

    //  LISTAR METAS DE UM USUÁRIO
    case "GET":
        if (!isset($_GET["usuario_id"])) {
            $response->error("É necessário informar o usuario_id.");
        }

        $usuario_id = $_GET["usuario_id"];
        $result = $meta->listarPorUsuario($usuario_id);

        $response->success("Metas encontradas.", $result);
        break;

    //  CRIAR META
    case "POST":
        $dados = json_decode(file_get_contents("php://input"));

        if (!isset($dados->titulo) || !isset($dados->descricao) || !isset($dados->usuario_id)) {
            $response->error("Título, descrição e usuário_id são obrigatórios.");
        }

        $meta->titulo = $dados->titulo;
        $meta->descricao = $dados->descricao;
        $meta->usuario_id = $dados->usuario_id;

        if ($meta->criar()) {
            $response->success("Meta criada com sucesso!");
        } else {
            $response->error("Erro ao criar meta.");
        }
        break;

    //  EDITAR META
    case "PUT":
        $dados = json_decode(file_get_contents("php://input"));

        if (!isset($dados->id)) {
            $response->error("O ID da meta é obrigatório para atualização.");
        }

        $meta->id = $dados->id;
        $meta->titulo = $dados->titulo ?? null;
        $meta->descricao = $dados->descricao ?? null;
        $meta->concluida = $dados->concluida ?? null;

        if ($meta->atualizar()) {
            $response->success("Meta atualizada com sucesso!");
        } else {
            $response->error("Erro ao atualizar meta.");
        }
        break;

    // DELETAR META
    case "DELETE":
        $dados = json_decode(file_get_contents("php://input"));

        if (!isset($dados->id)) {
            $response->error("O ID da meta é obrigatório para exclusão.");
        }

        $meta->id = $dados->id;

        if ($meta->deletar()) {
            $response->success("Meta deletada com sucesso!");
        } else {
            $response->error("Erro ao deletar meta.");
        }
        break;

    default:
        $response->error("Método HTTP não suportado.");
        break;
}
?>
