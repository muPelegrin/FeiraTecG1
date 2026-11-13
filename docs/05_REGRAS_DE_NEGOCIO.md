# 5. Backend: Regras de Negócio e Serviços Centrais
**Papel Técnico:** Backend – Regras de Negócio  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Regras de Negócio Centrais Implementadas

### Regra 1: Unicidade e Identificação da Chamada
- Uma chamada é estritamente identificada pelo par `(turma_id, data)`.
- **Implementação:** No banco, a chave `uk_chamada_turma_data` impede duplicações em nível atômico. No controlador `ChamadasController::abrir()`, o sistema valida antecipadamente a existência de chamada aberta ou fechada e redireciona de forma amigável ao registro já existente.

### Regra 2: Integridade da Lista Nominal
- Cada estudante matriculado na turma deve possuir **exatamente uma situação** na chamada concluída (`presente`, `ausente` ou `justificado`).
- **Implementação:** `Frequencia::registrarPresenca()` e `Frequencia::atualizarSituacao()`. A chave `uk_frequencia_chamada_aluno` assegura a unicidade do aluno por chamada.

### Regra 3: Tratamento de Chamada Não Realizada
- Chamada não realizada **NÃO pode** ser interpretada como ausência de estudantes.
- **Implementação:** Caso uma turma não tenha chamada concluída hoje, ela é classificada como **Turma Pendente**. A Cozinha computa seus matriculados como **Projeção Provisória Estimada** e o sistema exibe um alerta visual proativo de **Informação Incompleta**.

### Regra 4: Cálculo da Previsão de Refeições para a Cozinha
A demanda total da Cozinha é calculada pela fórmula:
$$\text{Demanda Prevista} = \sum (\text{Presentes Confirmados das Chamadas Concluídas}) + \sum (\text{Total de Matriculados das Turmas Pendentes})$$
- Quando todas as turmas fecham as chamadas, a previsão provisória zera e o valor torna-se 100% oficial e confirmado.

### Regra 5: Restrição e Privacidade de Dados Alimentares
- As restrições alimentares têm acesso limitado.
- A Cozinha recebe **exclusivamente as orientações operacionais indispensáveis para o preparo dos alimentos** (ex: "Sem glúten - substituir por fruta ou cuscuz"), sendo proibido o registro de prontuários, laudos ou diagnósticos médicos.

### Regra 6: Auditoria Obrigatória de Correção Pós-Fechamento
- Alterações em frequência após a conclusão da chamada exigem autorização específica (perfis `admin` ou `gestor`).
- É mandatório fornecer uma **justificativa textual** detalhada.
- O sistema grava no modelo `HistoricoAlteracao` os dados:
  - `tabela`: `frequencia`
  - `registro_id`: ID da frequência
  - `campo`: `situacao`
  - `valor_anterior`: situação prévia (ex: `ausente`)
  - `valor_novo`: nova situação (ex: `presente`)
  - `usuario_id`: operador logado
  - `justificativa`: texto justificado obrigatório
  - `created_at`: timestamp do servidor
