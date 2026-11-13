<?php

/**
 * Modelo de Aluno
 * Gerencia operações de banco de dados para a tabela de alunos,
 * incluindo consultas com informações de turma e matrícula.
 */
class Aluno extends Model
{
    protected string $table = 'aluno';

    /**
     * Retorna todos os alunos ativos ordenados pelo nome.
     *
     * @return array Lista de alunos ativos
     */
    public function findAllAtivos(): array
    {
        return $this->db->query("SELECT * FROM aluno WHERE ativo = 1 ORDER BY nome")->fetchAll();
    }

    /**
     * Retorna alunos matriculados e ativos em uma turma específica.
     *
     * @param int $turmaId ID da turma
     * @return array Lista de alunos da turma
     */
    public function findByTurma(int $turmaId): array
    {
        $sql = "SELECT a.* FROM aluno a
                INNER JOIN matricula m ON m.aluno_id = a.id
                WHERE m.turma_id = :turma_id AND m.status = 'ativa' AND a.ativo = 1
                ORDER BY a.nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['turma_id' => $turmaId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna todos os alunos ativos com informações da turma atual.
     *
     * @return array Lista de alunos com dados da turma
     */
    public function findComTurma(): array
    {
        $sql = "SELECT a.*, t.nome as turma_nome, t.turno, m.status as matricula_status
                FROM aluno a
                LEFT JOIN matricula m ON m.aluno_id = a.id AND m.status = 'ativa'
                LEFT JOIN turma t ON t.id = m.turma_id
                WHERE a.ativo = 1
                ORDER BY a.nome";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Retorna o total de alunos ativos cadastrados.
     *
     * @return int Quantidade de alunos ativos
     */
    public function totalAtivos(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM aluno WHERE ativo = 1")->fetchColumn();
    }

    /**
     * Verifica se já existe um aluno com o código de matrícula informado.
     *
     * @param string $codigo Código de matrícula a verificar
     * @param int|null $excludeId ID do aluno a excluir da verificação (para edição)
     * @return bool True se o código já existe
     */
    public function existeMatriculaCodigo(string $codigo, ?int $excludeId = null): bool
    {
        $sql = "SELECT COUNT(*) FROM aluno WHERE matricula_codigo = :codigo";
        $params = ['codigo' => $codigo];
        if ($excludeId) {
            $sql .= " AND id != :id";
            $params['id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (bool) $stmt->fetchColumn();
    }
}
