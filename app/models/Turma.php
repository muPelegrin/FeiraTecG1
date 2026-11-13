<?php

/**
 * Modelo de Turma
 * Gerencia operações de banco de dados para a tabela de turmas,
 * incluindo consultas com informações do ano letivo e status de chamada.
 */
class Turma extends Model
{
    protected string $table = 'turma';

    /**
     * Retorna todas as turmas com informações do ano letivo e total de alunos matriculados.
     *
     * @return array Lista de turmas com dados do ano letivo
     */
    public function findAllComAnoLetivo(): array
    {
        $sql = "SELECT t.*, al.ano, al.descricao as ano_letivo_descricao,
                (SELECT COUNT(*) FROM matricula m WHERE m.turma_id = t.id AND m.status = 'ativa') as total_alunos
                FROM turma t
                INNER JOIN ano_letivo al ON t.ano_letivo_id = al.id
                ORDER BY t.nome";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Retorna turmas filtradas por ano letivo com total de alunos.
     *
     * @param int $anoLetivoId ID do ano letivo
     * @return array Lista de turmas do ano letivo especificado
     */
    public function findByAnoLetivo(int $anoLetivoId): array
    {
        $stmt = $this->db->prepare("SELECT t.*, 
                (SELECT COUNT(*) FROM matricula m WHERE m.turma_id = t.id AND m.status = 'ativa') as total_alunos
                FROM turma t WHERE t.ano_letivo_id = :ano_letivo_id ORDER BY t.turno, t.nome");
        $stmt->execute(['ano_letivo_id' => $anoLetivoId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna turmas do ano letivo ativo com o status da chamada do dia atual.
     *
     * @return array Lista de turmas com status da chamada de hoje
     */
    public function findComStatusChamadaHoje(): array
    {
        $sql = "SELECT t.*, al.ano,
                (SELECT COUNT(*) FROM matricula m WHERE m.turma_id = t.id AND m.status = 'ativa') as total_alunos,
                c.id as chamada_id, c.status as chamada_status, c.aberta_em, c.concluida_em
                FROM turma t
                INNER JOIN ano_letivo al ON t.ano_letivo_id = al.id AND al.ativo = 1
                LEFT JOIN chamada c ON c.turma_id = t.id AND c.data = CURDATE()
                ORDER BY t.turno, t.nome";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Retorna turmas que ainda não tiveram a chamada concluída no dia atual.
     *
     * @return array Lista de turmas pendentes de chamada hoje
     */
    public function turmasPendentesHoje(): array
    {
        $sql = "SELECT t.*, al.ano,
                (SELECT COUNT(*) FROM matricula m WHERE m.turma_id = t.id AND m.status = 'ativa') as total_alunos
                FROM turma t
                INNER JOIN ano_letivo al ON t.ano_letivo_id = al.id AND al.ativo = 1
                LEFT JOIN chamada c ON c.turma_id = t.id AND c.data = CURDATE()
                WHERE c.id IS NULL OR c.status = 'aberta'
                ORDER BY t.turno, t.nome";
        return $this->db->query($sql)->fetchAll();
    }
}
