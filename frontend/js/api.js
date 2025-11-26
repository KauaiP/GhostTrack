// A URL agora aponta para o servidor PHP rodando na porta 8000
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
// Ajustei para receber todos os campos que seu banco suporta
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

// CORREÇÃO CRÍTICA: O PHP exige usuario_id no GET para saber de quem são as metas
export async function listarMetas(usuario_id) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php?usuario_id=${usuario_id}`);
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao listar metas:", error);
    }
}

// CORREÇÃO CRÍTICA: O PHP precisa do ID da meta para atualizar, não do usuario_id
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
            method: "DELETE", // Alguns servidores exigem body no DELETE, outros aceitam na URL
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id })
        });
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao deletar meta:", error);
    }
}