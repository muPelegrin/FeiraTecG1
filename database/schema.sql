-- ============================================================
-- Central de Frequência e Alimentação Escolar
-- Esquema do Banco de Dados MySQL
-- ============================================================
-- Criado em: 2026
-- Motor: InnoDB | Charset: utf8mb4 | Collation: utf8mb4_unicode_ci
-- ============================================================

-- Criação do banco de dados
CREATE DATABASE IF NOT EXISTS feira_tec_g1
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE feira_tec_g1;

-- ============================================================
-- TABELA: ano_letivo
-- Descrição: Armazena os anos letivos do sistema.
-- ============================================================
CREATE TABLE IF NOT EXISTS ano_letivo (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    ano         YEAR            NOT NULL COMMENT 'Ano letivo (ex: 2026)',
    descricao   VARCHAR(100)    NULL     COMMENT 'Descrição opcional do ano letivo',
    ativo       TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = ativo, 0 = inativo',
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Anos letivos cadastrados no sistema';

-- ============================================================
-- TABELA: turma
-- Descrição: Turmas vinculadas a um ano letivo.
-- ============================================================
CREATE TABLE IF NOT EXISTS turma (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nome            VARCHAR(50)     NOT NULL COMMENT 'Nome da turma (ex: 6A, 7B)',
    ano_letivo_id   INT UNSIGNED    NOT NULL COMMENT 'FK para ano_letivo',
    turno           ENUM('matutino','vespertino','noturno') NOT NULL COMMENT 'Turno da turma',
    serie           VARCHAR(30)     NOT NULL COMMENT 'Série/Ano escolar (ex: 6º Ano)',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_turma_ano_letivo
        FOREIGN KEY (ano_letivo_id) REFERENCES ano_letivo(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Turmas escolares por ano letivo';

-- ============================================================
-- TABELA: aluno
-- Descrição: Cadastro de alunos da escola.
-- ============================================================
CREATE TABLE IF NOT EXISTS aluno (
    id                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nome                VARCHAR(150)    NOT NULL COMMENT 'Nome completo do aluno',
    data_nascimento     DATE            NOT NULL COMMENT 'Data de nascimento',
    matricula_codigo    VARCHAR(30)     NOT NULL COMMENT 'Código único de matrícula do aluno',
    ativo               TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = ativo, 0 = inativo',
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_aluno_matricula_codigo (matricula_codigo)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Cadastro geral de alunos';

-- ============================================================
-- TABELA: matricula
-- Descrição: Vinculação do aluno a uma turma em um ano letivo.
-- Regra: um aluno só pode ter uma matrícula por turma/ano.
-- Relação: ALUNO 1---N MATRICULA N---1 TURMA
-- ============================================================
CREATE TABLE IF NOT EXISTS matricula (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    aluno_id        INT UNSIGNED    NOT NULL COMMENT 'FK para aluno',
    turma_id        INT UNSIGNED    NOT NULL COMMENT 'FK para turma',
    ano_letivo_id   INT UNSIGNED    NOT NULL COMMENT 'FK para ano_letivo',
    status          ENUM('ativa','cancelada','transferida') NOT NULL DEFAULT 'ativa'
                    COMMENT 'Situação da matrícula',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    -- Regra: aluno não pode ter matrícula duplicada na mesma turma/ano
    UNIQUE KEY uk_matricula_aluno_turma_ano (aluno_id, turma_id, ano_letivo_id),
    CONSTRAINT fk_matricula_aluno
        FOREIGN KEY (aluno_id) REFERENCES aluno(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_matricula_turma
        FOREIGN KEY (turma_id) REFERENCES turma(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_matricula_ano_letivo
        FOREIGN KEY (ano_letivo_id) REFERENCES ano_letivo(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Matrículas dos alunos nas turmas';

-- ============================================================
-- TABELA: usuario
-- Descrição: Usuários do sistema com perfis de acesso.
-- Perfis: admin, inspetor, cozinha, gestor
-- ============================================================
CREATE TABLE IF NOT EXISTS usuario (
    id          INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    nome        VARCHAR(150)    NOT NULL COMMENT 'Nome completo do usuário',
    email       VARCHAR(200)    NOT NULL COMMENT 'E-mail de login (único)',
    senha_hash  VARCHAR(255)    NOT NULL COMMENT 'Hash da senha (bcrypt)',
    perfil      ENUM('admin','inspetor','cozinha','gestor') NOT NULL
                COMMENT 'Perfil/papel do usuário no sistema',
    ativo       TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = ativo, 0 = inativo',
    created_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_usuario_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Usuários do sistema com controle de acesso';

-- ============================================================
-- TABELA: chamada
-- Descrição: Registro de chamadas (frequência) por turma/data.
-- Regra de negócio: NÃO pode haver duas chamadas ativas para
--   a mesma turma na mesma data (constraint UNIQUE turma_id+data).
-- Regra: chamada não realizada NÃO pode ser interpretada como falta.
-- Status: 'aberta' ou 'concluida'.
-- Relação: TURMA 1---N CHAMADA, USUARIO 1---N CHAMADA
-- ============================================================
CREATE TABLE IF NOT EXISTS chamada (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    turma_id        INT UNSIGNED    NOT NULL COMMENT 'FK para turma',
    data            DATE            NOT NULL COMMENT 'Data da chamada',
    status          ENUM('aberta','concluida') NOT NULL DEFAULT 'aberta'
                    COMMENT 'Situação da chamada',
    usuario_id      INT UNSIGNED    NOT NULL COMMENT 'FK para usuário que criou a chamada',
    aberta_em       DATETIME        NOT NULL COMMENT 'Data/hora em que a chamada foi aberta',
    concluida_em    DATETIME        NULL     COMMENT 'Data/hora em que a chamada foi concluída',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    -- Regra: apenas UMA chamada por turma/data
    UNIQUE KEY uk_chamada_turma_data (turma_id, data),
    CONSTRAINT fk_chamada_turma
        FOREIGN KEY (turma_id) REFERENCES turma(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_chamada_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Chamadas de frequência por turma e data';

-- ============================================================
-- TABELA: frequencia
-- Descrição: Registro individual de presença de cada aluno.
-- Situação: 'presente', 'ausente', 'justificado'.
-- Regra: cada aluno matriculado deve ter exatamente UM registro
--   por chamada concluída (UNIQUE chamada_id+aluno_id).
-- Relação: CHAMADA 1---N FREQUENCIA N---1 ALUNO
-- ============================================================
CREATE TABLE IF NOT EXISTS frequencia (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    chamada_id      INT UNSIGNED    NOT NULL COMMENT 'FK para chamada',
    aluno_id        INT UNSIGNED    NOT NULL COMMENT 'FK para aluno',
    situacao        ENUM('presente','ausente','justificado') NOT NULL
                    COMMENT 'Situação de presença do aluno',
    observacao      TEXT            NULL     COMMENT 'Observação opcional',
    registrado_por  INT UNSIGNED    NOT NULL COMMENT 'FK para usuário que registrou',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    -- Regra: cada aluno aparece apenas uma vez por chamada
    UNIQUE KEY uk_frequencia_chamada_aluno (chamada_id, aluno_id),
    CONSTRAINT fk_frequencia_chamada
        FOREIGN KEY (chamada_id) REFERENCES chamada(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_frequencia_aluno
        FOREIGN KEY (aluno_id) REFERENCES aluno(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_frequencia_usuario
        FOREIGN KEY (registrado_por) REFERENCES usuario(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Frequência individual dos alunos por chamada';

-- ============================================================
-- TABELA: restricao_alimentar
-- Descrição: Orientações operacionais de restrição alimentar.
-- ATENÇÃO: esta tabela armazena APENAS orientações operacionais
--   (ex: "Sem glúten", "Sem lactose"). NÃO armazenar diagnósticos
--   médicos para preservar a privacidade do aluno.
-- Acesso restrito conforme regra de negócio.
-- Relação: ALUNO 1---N RESTRICAO_ALIMENTAR
-- ============================================================
CREATE TABLE IF NOT EXISTS restricao_alimentar (
    id                      INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    aluno_id                INT UNSIGNED    NOT NULL COMMENT 'FK para aluno',
    orientacao_operacional  TEXT            NOT NULL
                            COMMENT 'Orientação operacional - SEM diagnósticos médicos',
    registrado_por          INT UNSIGNED    NOT NULL COMMENT 'FK para usuário que registrou',
    ativo                   TINYINT(1)      NOT NULL DEFAULT 1 COMMENT '1 = ativo, 0 = inativo',
    created_at              DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at              DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_restricao_aluno
        FOREIGN KEY (aluno_id) REFERENCES aluno(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    CONSTRAINT fk_restricao_usuario
        FOREIGN KEY (registrado_por) REFERENCES usuario(id)
        ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Restrições alimentares dos alunos (somente orientações operacionais)';

-- ============================================================
-- TABELA: cardapio
-- Descrição: Cardápio diário por tipo de refeição.
-- Regra: apenas UM cardápio por data+tipo_refeicao.
-- ============================================================
CREATE TABLE IF NOT EXISTS cardapio (
    id                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    data                DATE            NOT NULL COMMENT 'Data do cardápio',
    tipo_refeicao       ENUM('cafe_manha','almoco','lanche_tarde','jantar') NOT NULL
                        COMMENT 'Tipo de refeição',
    descricao           TEXT            NOT NULL COMMENT 'Descrição geral do cardápio',
    previsao_quantidade INT UNSIGNED    NULL     COMMENT 'Quantidade prevista de refeições',
    created_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    -- Regra: um cardápio por data e tipo de refeição
    UNIQUE KEY uk_cardapio_data_tipo (data, tipo_refeicao)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Cardápios diários por tipo de refeição';

-- ============================================================
-- TABELA: item_cardapio
-- Descrição: Itens individuais de cada cardápio.
-- Relação: CARDAPIO 1---N ITEM_CARDAPIO
-- ============================================================
CREATE TABLE IF NOT EXISTS item_cardapio (
    id                  INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    cardapio_id         INT UNSIGNED    NOT NULL COMMENT 'FK para cardapio',
    nome_item           VARCHAR(150)    NOT NULL COMMENT 'Nome do item (ex: Arroz integral)',
    quantidade_prevista INT UNSIGNED    NULL     COMMENT 'Quantidade prevista do item',
    observacao          TEXT            NULL     COMMENT 'Observação sobre o item',
    PRIMARY KEY (id),
    CONSTRAINT fk_item_cardapio_cardapio
        FOREIGN KEY (cardapio_id) REFERENCES cardapio(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Itens individuais dos cardápios';

-- ============================================================
-- TABELA: historico_alteracao
-- Descrição: Trilha de auditoria para rastrear todas as
--   alterações em registros do sistema.
-- Armazena: valor anterior, valor novo, autor, data/hora
--   e justificativa (obrigatória).
-- ============================================================
CREATE TABLE IF NOT EXISTS historico_alteracao (
    id              INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    tabela          VARCHAR(100)    NOT NULL COMMENT 'Nome da tabela alterada',
    registro_id     INT UNSIGNED    NOT NULL COMMENT 'ID do registro alterado',
    campo           VARCHAR(100)    NOT NULL COMMENT 'Nome do campo alterado',
    valor_anterior  TEXT            NULL     COMMENT 'Valor antes da alteração',
    valor_novo      TEXT            NULL     COMMENT 'Valor após a alteração',
    usuario_id      INT UNSIGNED    NOT NULL COMMENT 'FK para usuário que realizou a alteração',
    justificativa   TEXT            NOT NULL COMMENT 'Justificativa obrigatória para a alteração',
    created_at      DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    CONSTRAINT fk_historico_usuario
        FOREIGN KEY (usuario_id) REFERENCES usuario(id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    -- Índice para consultas por tabela e registro
    INDEX idx_historico_tabela_registro (tabela, registro_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Histórico de auditoria de todas as alterações do sistema';
