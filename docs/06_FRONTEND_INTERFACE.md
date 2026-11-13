# 6. Frontend: Interface, Design System e Telas Implementadas
**Papel Técnico:** Frontend – Interface  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Diretrizes de Interface e Aderência às Regras

Conforme o item 3 do edital da Mostra:
> *"Utilizar PHP 8+, MySQL, PDO, HTML5, CSS3 e JavaScript puro quando necessário; não utilizar frameworks CSS ou backend, salvo autorização do professor."*

Toda a camada de interface foi construída em **CSS3 Puro** e **JavaScript Puro (Vanilla JS)**, sem importação de Bootstrap, Tailwind, jQuery ou qualquer biblioteca externa.

---

## 2. Design System em CSS3 Puro (`public/css/style.css`)

### Variáveis CSS Nativas (`:root`)
- **Paleta de Cores Institucionais:**
  - Azul Primário (`#1e40af`, `#1d4ed8`, `#dbeafe`)
  - Sucesso / Presença (`#16a34a`, `#dcfce7`)
  - Alerta / Provisório (`#d97706`, `#fef3c7`)
  - Perigo / Ausência (`#dc2626`, `#fee2e2`)
  - Informação (`#0284c7`, `#e0f2fe`)
- **Superfícies e Tipografia:**
  - Font Stack moderno do sistema (`-apple-system`, `Segoe UI`, `Roboto`, `Helvetica`)
  - Fundo neutro suave (`#f8fafc`) e cartões em branco puro (`#ffffff`) com bordas sutis (`#e2e8f0`).
- **Sombras e Raios:**
  - Bordas arredondadas consistentes (`border-radius: 6px` a `14px`).
  - Elevações visuais suaves (`box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1)`).

### Componentes de Interface Criados
1. **Layout Estrutural:** Sidebar fixa escura de 260px com ícones claros + Topbar fixa com identificação de usuário logado e perfil.
2. **Cards & Cabeçalhos:** `.card`, `.card-header`, `.card-body` e `.card-footer`.
3. **Formulários:** `.form-group`, `.form-label` com indicador de obrigatoriedade (`.required`), `.form-control` e `.form-row` responsivo em grid.
4. **Tabelas Operacionais:** `.table-container` com rolagem horizontal em telas pequenas, cabeçalhos fixos com fundo cinza suave e realce de linhas no hover.
5. **Seletor Rápido de Presença:** Grupo de botões estilizáveis com rádio buttons ocultos (`.radio-attendance-group`) para alternar instantaneamente entre Verde (Presente), Vermelho (Ausente) e Amarelo (Justificado).
6. **Alerta de Turmas Pendentes:** Banner pontilhado de alto impacto visual em gradiente âmbar (`.alert-pending-banner`).

---

## 3. Comportamento JavaScript Puro (`public/js/app.js`)

1. **Ações em Massa na Chamada:**
   - Botão *"Marcar Todos Presentes"*: ativa todos os rádios `value="presente"` em 1 clique.
   - Botão *"Marcar Todos Ausentes"*: ativa todos os rádios `value="ausente"`.
2. **Adição Dinâmica de Ingredientes:**
   - Inserção de linhas dinâmicas de itens do cardápio com botão de exclusão sem recarregar a página.
3. **Toggle da Barra Lateral em Dispositivos Móveis:**
   - Botão hambúrguer retrátil com fechamento automático ao clicar fora do menu em telas `< 992px`.
4. **Preenchimento de Contas na Tela de Login:**
   - Cliques diretos nos cartões de Maria (Inspetora), João (Cozinha), Ana (Gestora) ou Admin preenchem e-mail e senha instantaneamente para agilizar a apresentação na Mostra.
5. **Auto-dismiss de Alertas:**
   - Fade-out suave automático após 6 segundos.
