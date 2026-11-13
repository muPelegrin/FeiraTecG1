# 3. Dicionário de Dados e Diagrama Entidade-Relacionamento (DER)
**Papel Técnico:** Analista de Dados  
**Projeto:** Central de Frequência e Alimentação Escolar (Grupo 01)  
**Evento:** Mostra de Tecnologia 2026  

---

## 1. Diagrama Entidade-Relacionamento (Mermaid)

```mermaid
erDiagram
    ANO_LETIVO ||--o{ TURMA : "possui"
    TURMA ||--o{ MATRICULA : "recebe"
    ALUNO ||--o{ MATRICULA : "tem"
    TURMA ||--o{ CHAMADA : "possui"
    USUARIO ||--o{ CHAMADA : "abre"
    CHAMADA ||--|{ FREQUENCIA : "contem"
    ALUNO ||--o{ FREQUENCIA : "possui"
    ALUNO ||--o{ RESTRICAO_ALIMENTAR : "tem"
    CARDAPIO ||--o{ ITEM_CARDAPIO : "contem"
    USUARIO ||--o{ HISTORICO_ALTERACAO : "registra"

    ANO_LETIVO {
        int id PK
        year ano
        varchar descricao
        tinyint ativo
    }

    TURMA {
        int id PK
        varchar nome
        int ano_letivo_id FK
        enum turno
        varchar serie
    }

    ALUNO {
        int id PK
        varchar nome
        date data_nascimento
        varchar matricula_codigo UK
        tinyint ativo
    }

    MATRICULA {
        int id PK
        int aluno_id FK
        int turma_id FK
        int ano_letivo_id FK
        enum status
    }

    USUARIO {
        int id PK
        varchar nome
        varchar email UK
        varchar senha_hash
        enum perfil
        tinyint ativo
    }

    CHAMADA {
        int id PK
        int turma_id FK
        date data
        enum status
        int usuario_id FK
        datetime aberta_em
        datetime concluida_em
    }

    FREQUENCIA {
        int id PK
        int chamada_id FK
        int aluno_id FK
        enum situacao
        text observacao
        int registrado_por FK
    }

    RESTRICAO_ALIMENTAR {
        int id PK
        int aluno_id FK
        text orientacao_operacional
        int registrado_por FK
        tinyint ativo
    }

    CARDAPIO {
        int id PK
        date data
        enum tipo_refeicao
        text descricao
        int previsao_quantidade
    }

    ITEM_CARDAPIO {
        int id PK
        int cardapio_id FK
        varchar nome_item
        int quantidade_prevista
        text observacao
    }

    HISTORICO_ALTERACAO {
        int id PK
        varchar tabela
        int registro_id
        varchar campo
        text valor_anterior
        text valor_novo
        int usuario_id FK
        text justificativa
        datetime created_at
    }
```

---

## 2. Dicionário de Dados das Tabelas Principais

### Tabela `chamada`
- **id** (INT UNSIGNED, PK, Auto Increment): Identificador único da chamada.
- **turma_id** (INT UNSIGNED, FK turma.id): Turma da chamada.
- **data** (DATE): Data da chamada escolar.
- **status** (ENUM 'aberta', 'concluida'): Situação operacional da chamada.
- **usuario_id** (INT UNSIGNED, FK usuario.id): Inspetor que abriu a chamada.
- **aberta_em** (DATETIME): Timestamp de abertura.
- **concluida_em** (DATETIME, Nullable): Timestamp de fechamento.
- **Restrição de Integridade:** `UNIQUE KEY uk_chamada_turma_data (turma_id, data)` garante que nenhuma turma tenha duas chamadas no mesmo dia.

### Tabela `frequencia`
- **id** (INT UNSIGNED, PK, Auto Increment): Identificador do registro.
- **chamada_id** (INT UNSIGNED, FK chamada.id, ON DELETE CASCADE): Chamada correspondente.
- **aluno_id** (INT UNSIGNED, FK aluno.id): Aluno avaliado.
- **situacao** (ENUM 'presente', 'ausente', 'justificado'): Estado de presença.
- **observacao** (TEXT, Nullable): Motivo informado ou atestado.
- **registrado_por** (INT UNSIGNED, FK usuario.id): Inspetor ou gestor que marcou.
- **Restrição de Integridade:** `UNIQUE KEY uk_frequencia_chamada_aluno (chamada_id, aluno_id)` garante exatamente um status por aluno em cada chamada.

### Tabela `restricao_alimentar`
- **id** (INT UNSIGNED, PK, Auto Increment): Identificador.
- **aluno_id** (INT UNSIGNED, FK aluno.id): Aluno associado.
- **orientacao_operacional** (TEXT): Orientações estritamente práticas de preparo de alimentos (ex: "Sem glúten", "Sem lactose"). Sem dados clínicos/diagnósticos.
- **registrado_por** (INT UNSIGNED, FK usuario.id): Operador que cadastrou.
- **ativo** (TINYINT): 1 ativo, 0 inativo.

### Tabela `historico_alteracao`
- **id** (INT UNSIGNED, PK, Auto Increment): Identificador do log.
- **tabela** (VARCHAR(100)): Nome da tabela modificada.
- **registro_id** (INT UNSIGNED): ID do registro modificado.
- **campo** (VARCHAR(100)): Coluna alterada (ex: `situacao`).
- **valor_anterior** (TEXT, Nullable): Valor original antes da edição.
- **valor_novo** (TEXT, Nullable): Novo valor gravado.
- **usuario_id** (INT UNSIGNED, FK usuario.id): Autor da modificação.
- **justificativa** (TEXT, NOT NULL): Motivo formal obrigatório da correção.
- **created_at** (DATETIME): Data e hora exatas da operação.
