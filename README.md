# Central de Frequência e Alimentação Escolar
**Mostra de Tecnologia 2026 | Projeto Final – Desenvolvimento de Sistemas**  
**Grupo 01**

Plataforma integrada desenvolvida em **PHP 8+ MVC**, **MySQL (PDO)**, **HTML5**, **CSS3 puro** e **JavaScript puro** para conectar em tempo real o registro diário de presença escolar à previsão de refeições na cozinha, reduzindo desperdícios e eliminando retrabalho entre inspetoria, cozinha e gestão escolar.

---

## 🚀 Como Executar o Projeto no XAMPP

### 1. Requisitos
- XAMPP com PHP 8.1+ e MySQL / MariaDB ativo.
- Apache com módulo `mod_rewrite` habilitado (padrão no XAMPP).

### 2. Localização da Aplicação
O projeto deve estar localizado na pasta `htdocs` do seu XAMPP:
```
C:\Turma2\xampp\htdocs\FeiraTecG1
```

### 3. Inicialização e Carga do Banco de Dados
Abra o prompt de comando ou PowerShell e execute o script de instalação automática:
```powershell
php C:\Turma2\xampp\htdocs\FeiraTecG1\database\setup.php
```
Este script cria o banco `feira_tec_g1`, roda a estrutura `database/schema.sql` e popula o banco com dados fictícios realistas via `database/seed.sql`.

### 4. Execução dos Testes Automatizados
Para verificar a integridade de todas as regras de negócio:
```powershell
php C:\Turma2\xampp\htdocs\FeiraTecG1\tests\run_tests.php
```

### 5. Acessar no Navegador
Abra seu navegador em:
```
http://localhost/FeiraTecG1/
```

---

## 👥 Contas de Acesso para Demonstração na Mostra

Todas as contas utilizam a senha padrão: **`senha123`** (na tela de login há botões de 1 clique para auto-preenchimento):

| Perfil | Usuário | E-mail | Funcionalidades Principais |
|---|---|---|---|
| **Inspetor** | Maria Inspetora | `maria@escola.com` | Painel da Inspetoria, abertura e registro nominal rápido de presença |
| **Cozinha** | João Cozinha | `joao@escola.com` | Painel consolidado da Cozinha, previsão de refeições, dietas especiais |
| **Gestor** | Ana Gestora | `ana@escola.com` | Correções autorizadas com justificativa, relatórios gerenciais e taxas |
| **Admin** | Admin Geral | `admin@escola.com` | Acesso global irrestrito, cadastro de usuários e turmas |

---

## 🏛️ Arquitetura MVC e Estrutura de Diretórios

```
FeiraTecG1/
├── .htaccess                     # Reescrita de URLs para index.php
├── index.php                     # Ponto de entrada único (Front Controller)
├── config/
│   ├── config.php                # Constantes gerais da aplicação
│   └── database.php              # Conexão PDO Singleton com MySQL
├── core/
│   ├── App.php                   # Roteador e despachante de URLs amigáveis
│   ├── Controller.php            # Classe base para controladores
│   ├── Model.php                 # Classe base para modelos com CRUD PDO
│   ├── Middleware.php            # Controle de sessão e perfis (RBAC)
│   └── Helpers.php               # Sanitização XSS, CSRF, formatações e alertas
├── database/
│   ├── schema.sql                # Estrutura com 11 tabelas, PKs, FKs e restrições UNIQUE
│   ├── seed.sql                  # Dados fictícios (4 turmas, 30 alunos, 5 usuários, cardápios)
│   └── setup.php                 # Instalador automatizado do banco
├── app/
│   ├── controllers/              # 10 Controladores da aplicação
│   │   ├── HomeController.php
│   │   ├── AuthController.php
│   │   ├── TurmasController.php
│   │   ├── AlunosController.php
│   │   ├── ChamadasController.php
│   │   ├── CardapiosController.php
│   │   ├── CozinhaController.php
│   │   ├── InspetoriaController.php
│   │   ├── RelatoriosController.php
│   │   └── UsuariosController.php
│   ├── models/                   # 11 Modelos de dados
│   │   ├── Usuario.php
│   │   ├── AnoLetivo.php
│   │   ├── Turma.php
│   │   ├── Aluno.php
│   │   ├── Matricula.php
│   │   ├── Chamada.php
│   │   ├── Frequencia.php
│   │   ├── RestricaoAlimentar.php
│   │   ├── Cardapio.php
│   │   ├── ItemCardapio.php
│   │   └── HistoricoAlteracao.php
│   └── views/                    # Telas em HTML5 / PHP sem frameworks externos
│       ├── templates/            # Header com Sidebar/Topbar e Footer
│       ├── auth/                 # Tela de login estilizada
│       ├── home/                 # Dashboard principal integrado
│       ├── turmas/               # Listagem, cadastro e edição de turmas
│       ├── alunos/               # Listagem, cadastro e edição de alunos
│       ├── chamadas/             # Lista, registro nominal, visualização e correção
│       ├── cardapios/            # Planejamento, cadastro e edição com itens dinâmicos
│       ├── cozinha/              # Painel consolidado, alertas e orientações operacionais
│       ├── inspetoria/           # Quadro operacional de turmas da inspetoria
│       ├── relatorios/           # Frequência, absenteísmo, exportação CSV e auditoria
│       ├── usuarios/             # Gestão de operadores e perfis
│       └── errors/               # Páginas 404 e 403
├── public/
│   ├── css/
│   │   ├── style.css             # Design system completo em CSS3 puro responsivo
│   │   └── login.css             # Estilos da tela de login
│   ├── js/
│   │   └── app.js                # Ações rápidas de chamada, itens dinâmicos e sidebar
│   └── img/
│       └── logo.svg              # Identidade visual da aplicação
├── docs/                         # Evidências individuais dos 8 integrantes da equipe
│   ├── 01_BACKLOG_E_DECISOES.md                 # 1. Líder / Product Owner
│   ├── 02_DOCUMENTO_DE_REQUISITOS.md             # 2. Analista de Requisitos
│   ├── 03_DICIONARIO_DE_DADOS_E_DER.md          # 3. Analista de Dados
│   ├── 04_AUTENTICACAO_E_SEGURANCA.md            # 4. Backend - Autenticação
│   ├── 05_REGRAS_DE_NEGOCIO.md                  # 5. Backend - Regras de Negócio
│   ├── 06_FRONTEND_INTERFACE.md                 # 6. Frontend - Interface
│   ├── 07_PAINEIS_E_RELATORIOS.md               # 7. Frontend - Painéis
│   └── 08_PLANO_DE_TESTES_E_ROTEIRO_MOSTRA.md   # 8. QA / Apresentação
└── tests/
    └── run_tests.php             # Suíte com 20 testes de regras de negócio automatizados
```

---

## 🎯 Regras de Negócio Atendidas (Edital da Mostra)

1. **Unicidade de Chamada:** Cada turma só pode possuir uma chamada por data (`UNIQUE(turma_id, data)`).
2. **Integridade da Frequência:** Cada aluno possui exatamente uma situação por chamada (`UNIQUE(chamada_id, aluno_id)`).
3. **Tratamento de Chamada Não Realizada:** Nunca é interpretada como falta geral. Turmas sem chamada concluída são calculadas pela Cozinha como projeção provisória de matriculados, com alerta visual de informação incompleta.
4. **Proteção da Privacidade:** Informações de dietas especiais exibem apenas a orientação operacional de preparo (ex: "Sem glúten", "Sem lactose"), sem laudos ou diagnósticos médicos invasivos.
5. **Auditoria Obrigatória:** Correções pós-fechamento exigem perfil autorizado e gravam valor original, valor novo, operador, horário e justificativa mandatória na tabela `historico_alteracao`.
6. **Exportação de Dados:** Exportação em formato CSV compatível com Excel para análise pedagógica.
