# 7. Frontend: Painéis Operacionais e Relatórios
**Papel Técnico:** Frontend – Painéis e Relatórios  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Painel Consolidado da Cozinha (`/cozinha`)

O painel da Cozinha foi projetado para operar em tablets fixados na parede do refeitório ou em computadores da equipe de nutrição.

### Elementos Chave do Painel
1. **Banner Dinâmico de Pendências:**
   - Em caso de turmas pendentes, destaca em âmbar o alerta de informação incompleta e lista as turmas que ainda não fecharam a chamada.
   - Quando todas concluem, transforma-se em um banner verde de fechamento completo com números consolidados.
2. **Quadro de Métricas em Tempo Real:**
   - **Presentes Confirmados:** soma das chamadas concluídas.
   - **Projeção Provisória:** alunos de turmas pendentes.
   - **Estimativa Final Total de Refeições:** soma operacional necessária.
   - **Faltas Confirmadas:** economia gerada por cancelamento de porções.
3. **Cardápio e Metas de Preparo:**
   - Apresenta pratos, ingredientes e metas de porções.
4. **Quadro Seguro de Orientações Alimentares:**
   - Lista alunos com dietas específicas com destaque em verde e orientações de preparo ("Sem glúten", "Sem lactose"), resguardando o sigilo de diagnósticos médicos.

---

## 2. Painel da Inspetoria (`/inspetoria`)

- **Objetivo:** Permitir ao inspetor escolar acompanhar quais turmas já iniciaram, quais estão em registro e quais faltam.
- **Destaques:**
  - Contador de Turmas Concluídas vs. Pendentes.
  - Tabela com horários exatos de abertura e conclusão de cada turma.
  - Botões rápidos com cores semânticas: *Abrir Chamada* (azul), *Continuar* (amarelo) e *Visualizar* (cinza).

---

## 3. Relatórios Gerenciais e Auditoria (`/relatorios`)

### Relatório de Frequência e Absenteísmo (`/relatorios/frequencia`)
- **Filtros Flexíveis:** intervalo de datas (início e fim) e seletor por turma.
- **KPIs Automáticos:**
  - Taxa Geral de Presença (%)
  - Taxa Geral de Ausência (%)
  - Total de Presenças e Faltas (Ausências + Justificadas)
- **Exportação CSV:** download de arquivo `frequencia.csv` com separador `;` e codificação UTF-8 com BOM para abertura perfeita no Microsoft Excel e LibreOffice Calc.
- **Impressão Limpa:** suporte completo a `@media print` que remove menus, barras laterais e botões, formatando a página para papel A4.

### Trilha de Auditoria (`/relatorios/auditoria`)
- Tabela de rastreabilidade completa de todas as alterações manuais com destaque visual entre valor original (vermelho) e novo valor (verde), carimbo de data/hora e a justificativa fornecida pelo operador.
