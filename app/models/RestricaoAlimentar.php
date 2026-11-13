<?php

/**
 * Modelo de Restrição Alimentar
 * Gerencia operações de banco de dados para a tabela de restrições alimentares dos alunos,
 * como alergias, intolerâncias e dietas especiais.
 */
class RestricaoAlimentar extends Model
{
    protected string $table = 'restricao_alimentar';

    /**
     * Retorna todas as restrições alimentares ativas de um aluno.
     *
     * @param int $alunoId ID do aluno
     * @return array Lista de restrições alimentares do aluno
     */
    public function findByAluno(int $alunoId): array
    {
        $stmt = $this->db->prepare("SELECT r.*, u.nome as registrado_por_nome FROM restricao_alimentar r 
                INNER JOIN usuario u ON u.id = r.registrado_por
                WHERE r.aluno_id = :aluno_id AND r.ativo = 1 ORDER BY r.created_at DESC");
        $stmt->execute(['aluno_id' => $alunoId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna todas as restrições alimentares ativas com dados do aluno.
     *
     * @return array Lista completa de restrições ativas com nome do aluno
     */
    public function findAllAtivas(): array
    {
        $sql = "SELECT r.*, a.nome as aluno_nome, a.matricula_codigo, u.nome as registrado_por_nome
                FROM restricao_alimentar r
                INNER JOIN aluno a ON a.id = r.aluno_id
                INNER JOIN usuario u ON u.id = r.registrado_por
                WHERE r.ativo = 1 ORDER BY a.nome";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Retorna o total de alunos que possuem pelo menos uma restrição alimentar ativa.
     *
     * @return int Quantidade de alunos com restrição
     */
    public function totalComRestricao(): int
    {
        return (int) $this->db->query("SELECT COUNT(DISTINCT aluno_id) FROM restricao_alimentar WHERE ativo = 1")->fetchColumn();
    }
}
