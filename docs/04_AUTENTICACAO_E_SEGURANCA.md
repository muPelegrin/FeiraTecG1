# 4. Backend: Autenticação, Perfis e Segurança
**Papel Técnico:** Backend – Autenticação e Segurança  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Arquitetura de Segurança Implementada

### 1.1 Hash de Senhas com Bcrypt
Todas as senhas são armazenadas utilizando a função nativa `password_hash($senha, PASSWORD_BCRYPT)`, que gera um salt criptográfico aleatório com fator de custo 10. A validação ocorre através de `password_verify($senha, $hash)` em tempo constante contra ataques de temporização.

### 1.2 Proteção Contra CSRF (Cross-Site Request Forgery)
- Foi implementada uma geração de tokens pseudo-aleatórios criptograficamente seguros em `core/Helpers.php`:
  `bin2hex(random_bytes(32))`
- Toda requisição `POST` em formulários do sistema inclui um campo oculto `csrf_token`.
- O método `verifyCsrf($token)` compara o token via `hash_equals()` e invalida o token após a verificação para impedir replay attacks.

### 1.3 Prevenção Contra SQL Injection
- Todos os acessos ao banco de dados utilizam **PDO com prepared statements** e parâmetros nomeados vinculados (`bindValue` / `bindParam`).
- A diretiva `PDO::ATTR_EMULATE_PREPARES => false` está ativa, garantindo compilação nativa no MariaDB/MySQL.

### 1.4 Prevenção Contra XSS (Cross-Site Scripting)
- Todo dado fornecido pelo usuário e impresso nas views passa pela função `sanitize($valor)` que executa `htmlspecialchars(trim($valor), ENT_QUOTES, 'UTF-8')`.

---

## 2. Matriz de Autorização por Perfis (RBAC)

O controle de rotas é centralizado na classe `core/Middleware.php` através dos métodos `Middleware::requireLogin()` e `Middleware::requireRole(['perfil1', 'perfil2'])`.

| Módulo / Rota | Admin | Gestor | Inspetor | Cozinha |
|---|:---:|:---:|:---:|:---:|
| **Dashboard Principal** (`/`) | ✅ | ✅ | ✅ | ✅ |
| **Painel da Inspetoria** (`/inspetoria`) | ✅ | ✅ | ✅ | ❌ |
| **Abrir / Registrar Chamada** (`/chamadas/abrir`, `registrar`) | ✅ | ❌ | ✅ | ❌ |
| **Visualizar Chamada** (`/chamadas/visualizar`) | ✅ | ✅ | ✅ | ❌ |
| **Corrigir Chamada Pós-Fechamento** (`/chamadas/corrigir`) | ✅ | ✅ | ❌ | ❌ |
| **Painel da Cozinha** (`/cozinha`) | ✅ | ✅ | ❌ | ✅ |
| **Gestão de Cardápios** (`/cardapios`) | ✅ | ❌ | ❌ | ✅ |
| **Relatórios e Frequência** (`/relatorios`) | ✅ | ✅ | ❌ | ❌ |
| **Auditoria do Sistema** (`/relatorios/auditoria`) | ✅ | ✅ | ❌ | ❌ |
| **Gestão de Usuários** (`/usuarios`) | ✅ | ❌ | ❌ | ❌ |

---

## 3. Usuários Fictícios de Demonstração (Senha Padrão: `senha123`)

| Nome | E-mail | Perfil | Papel no Roteiro da Mostra |
|---|---|---|---|
| **Maria Inspetora** | `maria@escola.com` | `inspetor` | Demonstra abertura e registro de presença em sala |
| **João Cozinha** | `joao@escola.com` | `cozinha` | Demonstra painel de refeições e previsão de porções |
| **Ana Gestora** | `ana@escola.com` | `gestor` | Demonstra correção auditada e relatórios gerenciais |
| **Admin Geral** | `admin@escola.com` | `admin` | Demonstra administração total e controle de usuários |
