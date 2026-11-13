# 1. Backlog, Atas de Decisões e Gestão do Produto
**Papel Técnico:** Líder / Product Owner  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Visão do Produto
Conectar o registro diário de presença escolar à previsão operacional da cozinha em tempo real, eliminando o desperdício de alimentos, reduzindo retrabalho manual e melhorando a comunicação entre Inspetoria, Gestão e Nutrição/Cozinha.

---

## 2. Backlog Priorizado (Metodologia MoSCoW)

### Must Have (Obrigatório para o MVP da Mostra)
- [x] **US01:** Autenticação por perfis com controle de acesso (Admin, Inspetor, Cozinha, Gestor).
- [x] **US02:** Cadastros institucionais de turmas e alunos fictícios com matrícula única.
- [x] **US03:** Abertura e registro nominal rápido de presença/ausência por turma e data.
- [x] **US04:** Bloqueio de chamadas duplicadas para a mesma turma/data (restrição de integridade).
- [x] **US05:** Painel da Cozinha com consolidação dos presentes confirmados e estimativa provisória.
- [x] **US06:** Alerta proativo na Cozinha e no Dashboard quando houver turmas com chamada pendente.
- [x] **US07:** Cadastro e consulta de cardápio do dia com itens e ingredientes.
- [x] **US08:** Restrição de acesso a informações de saúde: apenas orientações operacionais indispensáveis (sem diagnósticos médicos).
- [x] **US09:** Trilha de auditoria obrigatória para correções pós-fechamento com autor, timestamp, valor anterior/novo e justificativa.

### Should Have (Evoluções de Alto Valor)
- [x] **US10:** Relatório consolidado por intervalo de datas e turmas com taxas de frequência e absenteísmo.
- [x] **US11:** Exportação dos dados de frequência em formato CSV para planilhas.
- [x] **US12:** Painel específico da Inspetoria com horário de abertura e conclusão.

### Could Have (Futuro)
- [ ] **US13:** Leitura de chamada via crachá/código de barras.
- [ ] **US14:** Gráfico comparativo de consumo histórico por dia da semana.

---

## 3. Atas Curtas de Decisões Técnicas

### Decisão 01: Arquitetura MVC em PHP Puro sem Frameworks
- **Contexto:** Regra 3 do regulamento proíbe frameworks CSS e backend.
- **Decisão:** Construção de um micro-framework MVC próprio com Roteador (`App`), Controlador Base (`Controller`), Modelo Base (`Model` com PDO) e Middleware de autorização.
- **Impacto:** Sistema leve, 100% aderente ao regulamento da Mostra e compatível com qualquer instalação padrão de XAMPP.

### Decisão 02: Unicidade de Chamada e Tratamento de Não-Realizada
- **Contexto:** Chamadas não realizadas não podem ser assumidas como ausência geral nem gerar duplicidade.
- **Decisão:** Adicionada constraint `UNIQUE(turma_id, data)` na tabela `chamada`. Turmas sem chamada concluída são calculadas pela Cozinha como projeção provisória (total de matriculados), emitindo um banner de advertência de dados incompletos.

### Decisão 03: Proteção de Dados Sensíveis (Privacidade do Aluno)
- **Contexto:** Dados de saúde e laudos médicos não devem trafegar sem autorização nem ser expostos desnecessariamente à equipe de preparo.
- **Decisão:** Criação da tabela `restricao_alimentar` contendo exclusivamente o campo `orientacao_operacional` (ex: "Sem glúten", "Sem lactose"), proibindo diagnósticos médicos e códigos CID.
