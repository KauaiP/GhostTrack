// Detecta automaticamente se esta em producao ou desenvolvimento
const isLocalhost = window.location.hostname === 'localhost' || 
                    window.location.hostname === '127.0.0.1' ||
                    window.location.hostname === '';

const isProduction = !isLocalhost;

// Para o servidor free.nf, usa o mesmo dominio em producao
const API_URL = isProduction 
    ? window.location.origin + "/backend/api"
    : "http://localhost:8000/backend/api";

console.log("===== DEBUG API =====");
console.log("Hostname:", window.location.hostname);
console.log("Origin:", window.location.origin);
console.log("Ambiente:", isProduction ? "PRODUCAO" : "DESENVOLVIMENTO");
console.log("API URL:", API_URL);
console.log("====================");

// -----------------------
//     USUARIOS
// -----------------------

export async function criarUsuario(nome, email, senha) {
    try {
        const resposta = await fetch(`${API_URL}/usuarios.php`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ nome, email, senha })
        });
        
        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }
        
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao criar usuário:", error);
        throw error;
    }
}

export async function listarUsuarios() {
    try {
        const resposta = await fetch(`${API_URL}/usuarios.php`);
        
        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }
        
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao listar usuários:", error);
        throw error;
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
        
        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }
        
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao criar meta:", error);
        throw error; // Re-throw para que o código chamador possa tratar
    }
}

export async function listarMetas(usuario_id) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php?usuario_id=${usuario_id}`);
        
        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }
        
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao listar metas:", error);
        throw error;
    }
}

export async function atualizarMeta(id, titulo, descricao, status, progresso) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php`, {
            method: "POST", // override para hosts que bloqueiam PUT
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ 
                acao: "atualizar",
                id, 
                titulo, 
                descricao, 
                status, 
                progresso 
            })
        });
        
        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }
        
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao atualizar meta:", error);
        throw error;
    }
}

// Deletar meta
export async function deletarMeta(id) {
    try {
        const resposta = await fetch(`${API_URL}/metas.php`, {
            method: "POST", // override para hosts que bloqueiam DELETE
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ acao: "deletar", id })
        });
        
        if (!resposta.ok) {
            throw new Error(`Erro HTTP: ${resposta.status}`);
        }
        
        return await resposta.json();
    } catch (error) {
        console.error("Erro ao deletar meta:", error);
        throw error;
    }
}