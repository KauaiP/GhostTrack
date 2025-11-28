# 📊 GhostTrack - Overview do Projeto

## 🎯 **Conceito Geral**
Sistema web para rastreamento de metas pessoais com categorias (Saúde, Estudos, Finanças, Pessoal). Permite criar, consultar, atualizar, excluir metas e visualizar relatórios de progresso.

---

## 🏗️ **Arquitetura**

### **Frontend** (HTML/CSS/JavaScript)
- Interface responsiva com Bootstrap 5.3.2
- Comunicação com backend via Fetch API
- Detecção automática de ambiente (localhost vs produção)

### **Backend** (PHP/MySQL)
- API REST com arquitetura MVC
- PDO para acesso ao banco
- Hospedado no InfinityFree (produção)

---

## 📁 **Estrutura de Arquivos**

### **Backend**

#### db.php
```php
// Conexão com banco MySQL do InfinityFree
- Host: sql201.infinityfree.com
- Database: if0_40540057_ghosttrackdb
- Charset: UTF-8
- Retorna: objeto PDO conectado
```

#### Meta.php
```php
// Modelo de dados para metas
Métodos:
- criar(): INSERT nova meta
- listarPorUsuario($id): SELECT metas do usuário
- atualizar(): UPDATE meta existente
- deletar(): DELETE meta por ID
```

#### Usuario.php
```php
// Modelo de dados para usuários
Métodos:
- criar(): INSERT com senha hash
- listar(): SELECT todos usuários
- lerUm(): SELECT usuário por ID
- deletar(): DELETE usuário
```

#### metas.php
```php
// Endpoint REST para metas
GET: /api/metas.php?usuario_id=1
  → Lista metas do usuário

POST: /api/metas.php
  - Sem acao: Cria nova meta
  - acao=atualizar: Atualiza meta existente
  - acao=deletar: Remove meta

PUT: /api/metas.php (compatibilidade)
  → Atualiza meta

DELETE: /api/metas.php?id=1
  → Remove meta

// POST com acao é solução para InfinityFree que bloqueia PUT/DELETE
```

#### usuarios.php
```php
// Endpoint REST para usuários
GET: /api/usuarios.php → Lista todos
GET: /api/usuarios.php?id=1 → Busca um
POST: /api/usuarios.php → Cria usuário
DELETE: /api/usuarios.php?id=1 → Remove
```

#### Response.php
```php
// Padroniza respostas JSON
success($data, $msg): {"success":true, "data":[], "message":""}
error($msg, $code): {"success":false, "message":""}
```

---

### **Frontend**

#### api.js
```javascript
// Cliente API - centraliza todas as chamadas fetch()

// Detecção de ambiente
isLocalhost ? "http://localhost:8000" : "https://ghosttrackk.free.nf"

// Funções:
- criarMeta() → POST /api/metas.php
- listarMetas() → GET /api/metas.php
- atualizarMeta() → POST com acao=atualizar
- deletarMeta() → POST com acao=deletar
- criarUsuario() → POST /api/usuarios.php
- listarUsuarios() → GET /api/usuarios.php
```

#### cadastro.html
```html
<!-- Formulário de criação de metas -->
Campos:
- Título (text, obrigatório)
- Categoria (radio buttons: saúde/estudos/finanças/pessoal)
- Descrição (textarea, opcional)
- Meta/valor (number, obrigatório)
- Unidade (text, ex: km, horas)
- Data início/fim (date, obrigatórios)

JavaScript:
- Valida datas mínimas (hoje)
- Captura categoria via querySelector(':checked')
- Envia para criarMeta()
- Exibe alertas de sucesso/erro
- Reset form após sucesso
```

#### consulta.html
```html
<!-- Listagem de metas com filtros -->
Recursos:
- Abas: "Em Andamento" e "Concluídas"
- Filtros por categoria (Todas/Saúde/Estudos/Finanças/Pessoal)
- Cards com progresso visual (barra colorida)
- Botão "Editar" apenas para metas ativas

JavaScript:
- Carrega metas na inicialização
- isConcluida(): progresso >= valor OU status='concluida'
- Separa metas em containerActive/containerCompleted
- Filtros dinâmicos por categoria
- Cores por categoria (gradientes)
```

#### atualizacao.html
```html
<!-- Seleção e edição de metas ativas -->
Fluxo:
1. Lista metas NÃO concluídas (filtro)
2. Clique → carrega dados no formulário
3. Slider para progresso (0-100%)
4. Atualiza via atualizarMeta()
5. Auto-calcula status: progresso >= valor → 'concluida'

JavaScript:
- filter(m => !isConcluida(m)): mostra só ativas
- Atualiza progresso, título, descrição
- Redireciona para Consulta após sucesso
```

#### exclusao.html
```html
<!-- Remoção de metas com confirmação -->
Recursos:
- Lista todas as metas com botão "Excluir"
- Modal Bootstrap para confirmação
- Aceita ?id=X na URL (auto-abre modal)
- Remove visualmente após DELETE

JavaScript:
- prepararExclusao(id, titulo): armazena e exibe modal
- btnConfirmDelete: chama deletarMeta()
- Remove DOM element após sucesso
- Mostra mensagem se lista ficar vazia
```

#### relatorios.html
```html
<!-- Dashboard com estatísticas e conquistas -->
Seções:
1. Cards do Topo:
   - Total ativas, concluídas, média progresso, total geral
   - Animação de contagem (animateValue)

2. Relatório por Categoria:
   - Agrupa metas por saúde/estudos/finanças/pessoal
   - Calcula média de progresso por categoria
   - Soma valores alvo vs atual
   - Barra de progresso colorida

3. Atividade Recente:
   - Últimas 3 metas criadas (sort por ID desc)
   - Ícones: criada/em andamento/concluída

4. Sistema de Conquistas:
   - "Primeiro Passo": cadastrou 1+ meta
   - "No Meio do Caminho": 50%+ em alguma meta
   - "Vencedor": completou 1+ meta
   - "Mestre": completou 3+ metas
   - Visual: locked (grayscale) vs unlocked (colorido)

JavaScript:
- calcularEstatisticasGerais(): processa totais
- gerarRelatorioCategorias(): agrupa e calcula médias
- gerarAtividadeRecente(): ordena por criação
- verificarConquistas(): valida regras e desbloqueia
```

#### home.html
```html
<!-- Página inicial (landing page) -->
Estrutura:
- Hero Section: título, subtítulo, botão CTA
- Features: cards de funcionalidades (rastreamento/categorias/relatórios)
- Stats: números de impacto (1000+ usuários, etc)
- Footer: links sociais, navegação

Objetivo: apresentar o sistema e direcionar para Cadastro
```

#### qs.html
```html
<!-- Quem Somos -->
Conteúdo:
- Missão: ajudar pessoas a alcançar metas
- Valores: foco, persistência, transparência, comunidade
- Equipe/time (cards com ícones)

Estático, informativo
```

---

## 🎨 **Estilos CSS**

#### styles.css
```css
// CSS Global
- Variáveis: cores primárias/secundárias
- Navbar: sticky, hover effects
- Page Header: gradientes roxos
- Footer: 4 colunas, social links
- Forms: inputs estilizados, focus states
- Buttons: gradientes, hover lift
- Progress bars: categorias coloridas
- Badges por categoria: gradientes
- Animations: fadeInUp
- Responsive: adaptações mobile
```

#### Arquivos CSS específicos por página:
- cadastro.css: category selector (grid 2x2, radio visual)
- consulta.css: filter buttons, goal cards, progress bars
- atualizacao.css: goal options (lista clicável), form slider
- exclusao.css: delete cards, modal styling
- relatorios.css: stats grid, category reports, achievements
- home.css: hero section, feature cards
- qs.css: about cards, values grid

---

## 🔧 **Funcionalidades Principais**

### **CRUD de Metas**
1. **Create**: cadastro.html → criarMeta() → POST metas.php
2. **Read**: consulta.html → listarMetas() → GET metas.php
3. **Update**: atualizacao.html → atualizarMeta() → POST com acao=atualizar
4. **Delete**: exclusao.html → deletarMeta() → POST com acao=deletar

### **Recursos Avançados**
- **Filtros**: por categoria e status (ativa/concluída)
- **Progresso Visual**: barras coloridas por categoria
- **Relatórios**: estatísticas agregadas, médias, totais
- **Conquistas**: gamificação com badges desbloqueáveis
- **Auto-conclusão**: status='concluida' quando progresso >= valor

### **Tratamento de Erros**
- Try/catch em todas as chamadas fetch
- Mensagens específicas para "Failed to fetch"
- Alertas Bootstrap com auto-dismiss (5s)
- Validação de campos obrigatórios (HTML5 required)

---

## 🌐 **Deploy & Ambiente**

### **Localhost**
```
Frontend: file:/// ou http-server
Backend: php -S localhost:8000
```

### **Produção (InfinityFree)**
```
URL: https://ghosttrackk.free.nf
API: https://ghosttrackk.free.nf/backend/api/
Limitações:
- Bloqueia PUT/DELETE → solução: POST com acao=atualizar/deletar
- Sem acesso MySQL remoto → usar phpMyAdmin
```

---

## 🗄️ **Banco de Dados**

### **Tabela: usuarios**
```sql
id (PK), nome, email, senha (hash), criado_em
```

### **Tabela: metas**
```sql
id (PK), usuario_id (FK), titulo, descricao,
categoria (enum: saude/estudos/financas/pessoal),
status (enum: nao_concluida/em_andamento/concluida),
valor (alvo), unidade, progresso (atual),
data_inicio, data_conclusao, criado_em
```

---

## 🔑 **Pontos-Chave Técnicos**

1. **Method Override Pattern**: POST com parâmetro `acao` para contornar bloqueio do InfinityFree
2. **Environment Detection**: hostname check para alternar entre localhost/produção
3. **Status Auto-update**: progresso >= valor automaticamente muda status para 'concluida'
4. **Optional Chaining**: `resultado?.message` para evitar errors em objetos nulos
5. **Radio Button Validation**: `querySelector(':checked')` para capturar categoria selecionada
6. **Date Min Attribute**: impede seleção de datas passadas
7. **PDO Prepared Statements**: todas as queries usam bindParam para segurança
8. **CORS Headers**: configurados em todos os endpoints PHP

---

**Resumo**: Sistema completo de gestão de metas com frontend responsivo, backend REST API, relatórios visuais e gamificação. Código limpo (sem console.log/comentários), pronto para produção. ✅