-- ============================================================
-- Central de Frequência e Alimentação Escolar
-- Dados de Demonstração (Seed)
-- ============================================================
-- ATENÇÃO: Execute este arquivo APÓS o schema.sql
-- Todas as senhas dos usuários são '$2y$10$placeholder'
--   e devem ser geradas corretamente via PHP seeder.
-- ============================================================

USE feira_tec_g1;

-- ============================================================
-- 1. ANO LETIVO (2026)
-- ============================================================
INSERT INTO ano_letivo (id, ano, descricao, ativo) VALUES
(1, 2026, 'Ano Letivo 2026 - Ensino Fundamental II', 1);

-- ============================================================
-- 2. TURMAS (6A, 6B matutino | 7A, 7B vespertino)
-- ============================================================
INSERT INTO turma (id, nome, ano_letivo_id, turno, serie) VALUES
(1, '6A', 1, 'matutino',    '6º Ano'),
(2, '6B', 1, 'matutino',    '6º Ano'),
(3, '7A', 1, 'vespertino',  '7º Ano'),
(4, '7B', 1, 'vespertino',  '7º Ano');

-- ============================================================
-- 3. ALUNOS (30 alunos com nomes brasileiros fictícios)
-- ============================================================
INSERT INTO aluno (id, nome, data_nascimento, matricula_codigo, ativo) VALUES
-- Turma 6A (8 alunos)
( 1, 'Ana Carolina Silva',          '2014-03-15', '2026001', 1),
( 2, 'Bruno Henrique Oliveira',     '2014-07-22', '2026002', 1),
( 3, 'Camila Ferreira Santos',      '2014-01-10', '2026003', 1),
( 4, 'Daniel Souza Costa',          '2013-11-05', '2026004', 1),
( 5, 'Eduarda Lima Pereira',        '2014-05-30', '2026005', 1),
( 6, 'Felipe Rodrigues Almeida',    '2014-02-18', '2026006', 1),
( 7, 'Gabriela Martins Ribeiro',    '2013-09-25', '2026007', 1),
( 8, 'Hugo Nascimento Barbosa',     '2014-06-12', '2026008', 1),

-- Turma 6B (7 alunos)
( 9, 'Isabela Carvalho Araújo',     '2014-04-08', '2026009', 1),
(10, 'João Pedro Gomes Cardoso',    '2013-12-20', '2026010', 1),
(11, 'Karina Mendes Teixeira',      '2014-08-14', '2026011', 1),
(12, 'Lucas Gabriel Rocha',         '2014-01-27', '2026012', 1),
(13, 'Mariana Dias Correia',        '2013-10-03', '2026013', 1),
(14, 'Nicolas Batista Moreira',     '2014-06-19', '2026014', 1),
(15, 'Olívia Ramos Freitas',        '2014-03-01', '2026015', 1),

-- Turma 7A (8 alunos)
(16, 'Pedro Lucas Azevedo',         '2013-05-17', '2026016', 1),
(17, 'Quésia Vieira Monteiro',      '2013-02-28', '2026017', 1),
(18, 'Rafael Castro Pinto',         '2013-07-09', '2026018', 1),
(19, 'Sofia Moura Cavalcanti',      '2013-04-23', '2026019', 1),
(20, 'Thiago Lopes Fonseca',        '2013-11-11', '2026020', 1),
(21, 'Valentina Duarte Campos',     '2013-08-06', '2026021', 1),
(22, 'William Borges Peixoto',      '2013-01-14', '2026022', 1),
(23, 'Yasmin Nogueira Machado',     '2013-06-30', '2026023', 1),

-- Turma 7B (7 alunos)
(24, 'Arthur Melo Sampaio',         '2013-03-21', '2026024', 1),
(25, 'Beatriz Cunha Rezende',       '2013-09-12', '2026025', 1),
(26, 'Caio Henrique Andrade',       '2013-05-04', '2026026', 1),
(27, 'Débora Farias Brito',         '2013-12-08', '2026027', 1),
(28, 'Enzo Gabriel Siqueira',       '2013-07-26', '2026028', 1),
(29, 'Fernanda Barros Pacheco',     '2013-02-15', '2026029', 1),
(30, 'Gustavo Reis Medeiros',       '2013-10-19', '2026030', 1);

-- ============================================================
-- 4. USUÁRIOS (5 usuários do sistema)
-- Senha padrão para todos os usuários: 'senha123'
-- ============================================================
INSERT INTO usuario (id, nome, email, senha_hash, perfil, ativo) VALUES
(1, 'Admin Sistema',   'admin@escola.com',     '$2y$10$MHc5V.NMHz1aCvAgWtkDaO8aCLPwLB80dFyGh8fhXARmvQDIWIaqO', 'admin',    1),
(2, 'Maria Inspetora',  'maria@escola.com',     '$2y$10$MHc5V.NMHz1aCvAgWtkDaO8aCLPwLB80dFyGh8fhXARmvQDIWIaqO', 'inspetor', 1),
(3, 'João Cozinha',     'joao@escola.com',      '$2y$10$MHc5V.NMHz1aCvAgWtkDaO8aCLPwLB80dFyGh8fhXARmvQDIWIaqO', 'cozinha',  1),
(4, 'Ana Gestora',      'ana@escola.com',       '$2y$10$MHc5V.NMHz1aCvAgWtkDaO8aCLPwLB80dFyGh8fhXARmvQDIWIaqO', 'gestor',   1),
(5, 'Carlos Diretor',   'carlos@escola.com',    '$2y$10$MHc5V.NMHz1aCvAgWtkDaO8aCLPwLB80dFyGh8fhXARmvQDIWIaqO', 'gestor',   1);

-- ============================================================
-- 5. MATRÍCULAS (todos os 30 alunos matriculados)
-- ============================================================
INSERT INTO matricula (aluno_id, turma_id, ano_letivo_id, status) VALUES
-- Alunos da Turma 6A (ids 1-8)
( 1, 1, 1, 'ativa'),
( 2, 1, 1, 'ativa'),
( 3, 1, 1, 'ativa'),
( 4, 1, 1, 'ativa'),
( 5, 1, 1, 'ativa'),
( 6, 1, 1, 'ativa'),
( 7, 1, 1, 'ativa'),
( 8, 1, 1, 'ativa'),

-- Alunos da Turma 6B (ids 9-15)
( 9, 2, 1, 'ativa'),
(10, 2, 1, 'ativa'),
(11, 2, 1, 'ativa'),
(12, 2, 1, 'ativa'),
(13, 2, 1, 'ativa'),
(14, 2, 1, 'ativa'),
(15, 2, 1, 'ativa'),

-- Alunos da Turma 7A (ids 16-23)
(16, 3, 1, 'ativa'),
(17, 3, 1, 'ativa'),
(18, 3, 1, 'ativa'),
(19, 3, 1, 'ativa'),
(20, 3, 1, 'ativa'),
(21, 3, 1, 'ativa'),
(22, 3, 1, 'ativa'),
(23, 3, 1, 'ativa'),

-- Alunos da Turma 7B (ids 24-30)
(24, 4, 1, 'ativa'),
(25, 4, 1, 'ativa'),
(26, 4, 1, 'ativa'),
(27, 4, 1, 'ativa'),
(28, 4, 1, 'ativa'),
(29, 4, 1, 'ativa'),
(30, 4, 1, 'ativa');

-- ============================================================
-- 6. CARDÁPIOS (hoje e amanhã)
-- ============================================================
INSERT INTO cardapio (id, data, tipo_refeicao, descricao, previsao_quantidade) VALUES
-- Cardápio de hoje - Almoço
(1, CURDATE(), 'almoco',
 'Arroz integral, feijão carioca, frango grelhado, salada de alface e tomate, suco de laranja natural',
 120),
-- Cardápio de hoje - Lanche da tarde
(2, CURDATE(), 'lanche_tarde',
 'Pão com manteiga, vitamina de banana com aveia',
 80),
-- Cardápio de amanhã - Almoço
(3, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'almoco',
 'Arroz branco, feijão preto, carne moída refogada, purê de batata, salada de cenoura ralada, suco de goiaba',
 120),
-- Cardápio de amanhã - Lanche da tarde
(4, DATE_ADD(CURDATE(), INTERVAL 1 DAY), 'lanche_tarde',
 'Biscoito integral, fruta da estação (maçã), leite com achocolatado',
 80);

-- ============================================================
-- 7. ITENS DO CARDÁPIO
-- ============================================================
INSERT INTO item_cardapio (cardapio_id, nome_item, quantidade_prevista, observacao) VALUES
-- Itens do almoço de hoje (cardapio_id = 1)
(1, 'Arroz integral',                  15, 'Quilos'),
(1, 'Feijão carioca',                  10, 'Quilos'),
(1, 'Frango grelhado (sobrecoxa)',      20, 'Quilos'),
(1, 'Alface americana',                 5, 'Unidades (pés)'),
(1, 'Tomate',                           8, 'Quilos'),
(1, 'Suco de laranja natural',         30, 'Litros'),

-- Itens do lanche de hoje (cardapio_id = 2)
(2, 'Pão francês',                    100, 'Unidades'),
(2, 'Manteiga',                         3, 'Quilos'),
(2, 'Banana',                          90, 'Unidades'),
(2, 'Aveia em flocos',                   2, 'Quilos'),
(2, 'Leite integral',                  15, 'Litros'),

-- Itens do almoço de amanhã (cardapio_id = 3)
(3, 'Arroz branco',                    18, 'Quilos'),
(3, 'Feijão preto',                    12, 'Quilos'),
(3, 'Carne moída',                     20, 'Quilos'),
(3, 'Batata inglesa',                  15, 'Quilos - para o purê'),
(3, 'Cenoura',                          8, 'Quilos'),
(3, 'Suco de goiaba',                  30, 'Litros'),

-- Itens do lanche de amanhã (cardapio_id = 4)
(4, 'Biscoito integral',               80, 'Pacotes individuais'),
(4, 'Maçã',                           100, 'Unidades'),
(4, 'Leite integral',                  15, 'Litros'),
(4, 'Achocolatado em pó',               2, 'Quilos');

-- ============================================================
-- 8. CHAMADAS
-- Chamada 1: Turma 6A, hoje, CONCLUÍDA (com registros de frequência)
-- Chamada 2: Turma 7A, hoje, ABERTA (sem registros completos)
-- ============================================================
INSERT INTO chamada (id, turma_id, data, status, usuario_id, aberta_em, concluida_em) VALUES
-- Chamada concluída da turma 6A (criada pela inspetora Maria)
(1, 1, CURDATE(), 'concluida', 2,
    CONCAT(CURDATE(), ' 07:30:00'),
    CONCAT(CURDATE(), ' 07:45:00')),
-- Chamada aberta da turma 7A (criada pela inspetora Maria)
(2, 3, CURDATE(), 'aberta', 2,
    CONCAT(CURDATE(), ' 13:00:00'),
    NULL);

-- ============================================================
-- 9. FREQUÊNCIA (registros da chamada concluída - Turma 6A)
-- Todos os 8 alunos da turma 6A devem ter registro
-- ============================================================
INSERT INTO frequencia (chamada_id, aluno_id, situacao, observacao, registrado_por) VALUES
-- Chamada 1 (Turma 6A - concluída) - registrada pela inspetora (usuario_id=2)
(1,  1, 'presente',    NULL,                                 2),
(1,  2, 'presente',    NULL,                                 2),
(1,  3, 'ausente',     'Responsável não comunicou motivo',   2),
(1,  4, 'presente',    NULL,                                 2),
(1,  5, 'presente',    NULL,                                 2),
(1,  6, 'justificado', 'Atestado médico apresentado',        2),
(1,  7, 'presente',    NULL,                                 2),
(1,  8, 'presente',    NULL,                                 2);

-- ============================================================
-- 10. RESTRIÇÕES ALIMENTARES
-- IMPORTANTE: Somente orientações operacionais.
--   NÃO armazenar diagnósticos médicos.
-- ============================================================
INSERT INTO restricao_alimentar (aluno_id, orientacao_operacional, registrado_por, ativo) VALUES
-- Aluna Ana Carolina: restrição a glúten (registrado pela cozinha)
(1,  'Sem glúten - substituir pão por tapioca ou cuscuz. Verificar rótulos de biscoitos.',
     3, 1),
-- Aluno Daniel: restrição a lactose (registrado pela gestora)
(4,  'Sem lactose - substituir leite por suco ou água. Não servir vitamina de leite, purê com leite ou achocolatado.',
     4, 1),
-- Aluna Yasmin: restrição a amendoim e castanhas (registrado pela cozinha)
(23, 'Sem amendoim e castanhas - atenção especial a sobremesas e granolas. Verificar composição de barras de cereais.',
     3, 1);

-- ============================================================
-- 11. HISTÓRICO DE ALTERAÇÕES (exemplos de auditoria)
-- ============================================================
INSERT INTO historico_alteracao (tabela, registro_id, campo, valor_anterior, valor_novo, usuario_id, justificativa) VALUES
-- Exemplo 1: Alteração de situação de frequência
('frequencia', 6, 'situacao', 'ausente', 'justificado', 2,
 'Aluno apresentou atestado médico após o registro inicial da chamada'),

-- Exemplo 2: Alteração de turma de aluno
('matricula', 3, 'turma_id', '1', '2', 1,
 'Remanejamento do aluno para a turma 6B a pedido da coordenação pedagógica'),

-- Exemplo 3: Atualização de cardápio
('cardapio', 1, 'descricao',
 'Arroz integral, feijão carioca, frango assado, salada verde, suco de laranja',
 'Arroz integral, feijão carioca, frango grelhado, salada de alface e tomate, suco de laranja natural',
 4, 'Ajuste no cardápio conforme disponibilidade de ingredientes na despensa'),

-- Exemplo 4: Desativação de restrição alimentar
('restricao_alimentar', 2, 'ativo', '1', '0', 4,
 'Orientação atualizada conforme nova avaliação nutricional - restrição não mais necessária');
