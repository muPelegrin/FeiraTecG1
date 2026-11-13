# 2. Documento de Requisitos e Rastreabilidade
**Papel Técnico:** Analista de Requisitos / Documentação  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Processo Atual vs. Processo Proposto

### Processo Atual (Legado / Manual)
1. Inspetores ou professores realizam a chamada em livro físico de papel.
2. A equipe da cozinha cozinha com base em uma estimativa estática média.
3. Se chove ou ocorrem faltas em massa, ocorre desperdício de dezenas de quilos de comida.
4. Se o número de estudantes excede a previsão, falta alimentação.
5. Qualquer alteração ou correção pós-fechamento não possui rastro de responsabilidade.

### Processo Proposto (Plataforma Digital FeiraTecG1)
1. Inspetoria abre a chamada digital na primeira aula.
2. Marcação rápida com 1 clique (Presente, Ausente, Justificado).
3. Ao concluir, os totais alimentam instantaneamente o painel da Cozinha.
4. Cozinha visualiza total exato de porções a preparar + alerta de turmas pendentes.
5. Alunos com dietas especiais são atendidos com orientações operacionais claras sem violar a privacidade médica.
6. Qualquer correção posterior requer justificativa e grava log auditado.

---

## 2. Requisitos Funcionais (RF)

| ID | Nome do Requisito | Descrição | Perfil Autorizado |
|---|---|---|---|
| **RF01** | Autenticação Segura | Permitir login via e-mail e senha com hash bcrypt e controle de sessão por perfil. | Todos |
| **RF02** | Cadastro de Turmas | Manter dados de nome, série, turno e ano letivo. | Admin, Gestor |
| **RF03** | Cadastro de Alunos | Registrar estudantes fictícios com código único de matrícula e vínculo a turmas. | Admin, Gestor |
| **RF04** | Abertura de Chamada | Criar a chamada do dia por turma, garantindo unicidade (sem duplicidade no mesmo dia). | Admin, Inspetor |
| **RF05** | Registro de Presença | Lista nominal para marcar situação (presente, ausente, justificado) e observações. | Admin, Inspetor |
| **RF06** | Conclusão de Chamada | Finalizar a chamada e computar horário e autor do fechamento. | Admin, Inspetor |
| **RF07** | Correção Auditada | Permitir alterar presença pós-fechamento exigindo justificativa e registrando histórico. | Admin, Gestor |
| **RF08** | Painel da Cozinha | Exibir contagem de presentes, turmas pendentes, estimativa final e alertas de incompletude. | Admin, Gestor, Cozinha |
| **RF09** | Gestão de Cardápios | Cadastrar pratos e ingredientes por data e refeição (almoço, lanche, etc.). | Admin, Cozinha |
| **RF10** | Orientações de Dieta | Exibir orientações operacionais de alimentação sem diagnósticos médicos. | Admin, Cozinha |
| **RF11** | Painel da Inspetoria | Exibir visão geral de turmas chamadas vs pendentes e horários de atualização. | Admin, Gestor, Inspetor |
| **RF12** | Relatórios Gerenciais | Gerar estatísticas de presença/ausência por período com exportação CSV. | Admin, Gestor |

---

## 3. Requisitos Não-Funcionais (RNF)

- **RNF01 (Tecnologia Obrigatória):** Desenvolvimento em PHP 8+, MySQL, PDO, HTML5, CSS3 puro e JavaScript puro sem dependência de frameworks externos.
- **RNF02 (Arquitetura):** Padrão arquitetural MVC (Model-View-Controller) estrito com separação clara de responsabilidades.
- **RNF03 (Segurança):** Proteção contra CSRF em todas as requisições POST, sanitização contra XSS e prepared statements contra SQL Injection.
- **RNF04 (Privacidade):** Uso exclusivo de dados fictícios. Proibição de exposição de diagnósticos clínicos.
- **RNF05 (Disponibilidade Offline):** Execução local estável sob XAMPP, imune a quedas de conexão com a Internet no dia da Mostra.
- **RNF06 (Responsividade):** Layout adaptável para notebooks, desktops e tablets (inspetoria móvel).

---

## 4. Matriz de Rastreabilidade

| Requisito | Modelo (Model) | Controlador (Controller) | Visão (View) |
|---|---|---|---|
| RF01 | `Usuario.php` | `AuthController.php` | `app/views/auth/login.php` |
| RF02 | `Turma.php`, `AnoLetivo.php` | `TurmasController.php` | `app/views/turmas/*` |
| RF03 | `Aluno.php`, `Matricula.php` | `AlunosController.php` | `app/views/alunos/*` |
| RF04, RF05, RF06 | `Chamada.php`, `Frequencia.php` | `ChamadasController.php` | `app/views/chamadas/*` |
| RF07 | `HistoricoAlteracao.php` | `ChamadasController::corrigir()` | `app/views/chamadas/corrigir.php` |
| RF08, RF10 | `Chamada.php`, `RestricaoAlimentar.php` | `CozinhaController.php` | `app/views/cozinha/index.php` |
| RF09 | `Cardapio.php`, `ItemCardapio.php` | `CardapiosController.php` | `app/views/cardapios/*` |
| RF11 | `Turma.php`, `Chamada.php` | `InspetoriaController.php` | `app/views/inspetoria/index.php` |
| RF12 | `Chamada.php`, `HistoricoAlteracao.php` | `RelatoriosController.php` | `app/views/relatorios/*` |
