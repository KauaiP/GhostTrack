const API_URL = "http://localhost/TrabalhoDevWeb/backend/api";

// -----------------------
//     USUÁRIOS
// -----------------------

// Criar usuário
export async function criarUsuario(nome, email, senha) {
    const resposta = await fetch(`${API_URL}/usuarios.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ nome, email, senha })
    });

    return resposta.json();
}

// Listar usuários
export async function listarUsuarios() {
    const resposta = await fetch(`${API_URL}/usuarios.php`);
    return resposta.json();
}

// Atualizar usuário
export async function atualizarUsuario(id, nome, email, senha) {
    const resposta = await fetch(`${API_URL}/usuarios.php`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id, nome, email, senha })
    });

    return resposta.json();
}

// Deletar usuário
export async function deletarUsuario(id) {
    const resposta = await fetch(`${API_URL}/usuarios.php`, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    });

    return resposta.json();
}



// -----------------------
//        METAS
// -----------------------

// Criar meta
export async function criarMeta(usuario_id, titulo, descricao, categoria, valor, unidade, data_inicio, data_conclusao, progresso) {
    const resposta = await fetch(`${API_URL}/metas.php`, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ usuario_id, titulo, descricao, categoria, valor, unidade, data_inicio, data_conclusao, progresso})
    });

    return resposta.json();
}

// Listar metas
export async function listarMetas() {
    const resposta = await fetch(`${API_URL}/metas.php`);
    return resposta.json();
}

// Atualizar meta
export async function atualizarMeta(usuario_id, titulo, descricao, categoria, valor, unidade, data_inicio, data_conclusao, progresso) {
    const resposta = await fetch(`${API_URL}/metas.php`, {
        method: "PUT",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ usuario_id, titulo, descricao, categoria, valor, unidade, data_inicio, data_conclusao, progresso })
    });

    return resposta.json();
}

// Deletar meta
export async function deletarMeta(id) {
    const resposta = await fetch(`${API_URL}/metas.php`, {
        method: "DELETE",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id })
    });

    return resposta.json();
}
