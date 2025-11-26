const API_URL = "http://localhost:8000/backend/api";

// -----------------------
//     USUÁRIOS
// -----------------------

export async function criarUsuario(nome, email, senha) {
    try {
        const resposta = await fetch(`${API_URL}/usuarios.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nome, email, senha })
        });
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao criar usuário:", error);
    }
}

export async function listarUsuarios() {
    try {
        const resposta = await fetch(`${API_URL}/usuarios.php`);
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao listar usuários:", error);
    }
}

// -----------------------
//        METAS
// -----------------------

// Criar meta
export async function criarMeta(usuario_id, titulo, descricao, categoria, valor, unidade, data_inicio, data_conclusao, progresso) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                usuario_id, 
                titulo, 
                descricao, 
                categoria, 
                valor, 
                unidade, 
                data_inicio, 
                data_conclusao, 
                progresso
            })
        });
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao criar meta:", error);
    }
}

export async function listarMetas(usuario_id) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php?usuario_id=${usuario_id}`);
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao listar metas:", error);
    }
}

export async function atualizarMeta(id, titulo, descricao, status, progresso) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php`, {
            method: "PUT",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                id, 
                titulo, 
                descricao, 
                status, 
                progresso 
            })
        });
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao atualizar meta:", error);
    }
}

// Deletar meta
export async function deletarMeta(id) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php`, {
            method: "DELETE", 
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
        });
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao deletar meta:", error);
    }
}