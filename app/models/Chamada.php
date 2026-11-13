<?php

/**
 * Modelo de Chamada
 * Gerencia operações de banco de dados para a tabela de chamadas (registro de presença diário),
 * incluindo consultas com detalhes de turma, usuário e estatísticas de frequência.
 */
class Chamada extends Model
{
    protected string $table = 'chamada';

    /**
     * Busca uma chamada pela turma e data, incluindo nome da turma e do usuário.
     *
     * @param int $turmaId ID da turma
     * @param string $data Data da chamada (formato Y-m-d)
     * @return object|false Objeto da chamada ou false se não encontrada
     */
    public function findByTurmaData(int $turmaId, string $data): object|false
    {
        $stmt = $this->db->prepare("SELECT c.*, t.nome as turma_nome, u.nome as usuario_nome
                FROM chamada c
                INNER JOIN turma t ON t.id = c.turma_id
                INNER JOIN usuario u ON u.id = c.usuario_id
                WHERE c.turma_id = :turma_id AND c.data = :data LIMIT 1");
        $stmt->execute(['turma_id' => $turmaId, 'data' => $data]);
        return $stmt->fetch();
    }

    /**
     * Retorna todas as chamadas do dia atual com estatísticas de frequência.
     *
     * @return array Lista de chamadas de hoje com totais de presentes, ausentes e justificados
     */
    public function findHoje(): array
    {
        $sql = "SELECT c.*, t.nome as turma_nome, t.turno, u.nome as usuario_nome,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'presente') as total_presentes,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'ausente') as total_ausentes,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'justificado') as total_justificados,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id) as total_registros,
                (SELECT COUNT(*) FROM matricula m WHERE m.turma_id = c.turma_id AND m.status = 'ativa') as total_matriculados
                FROM chamada c
                INNER JOIN turma t ON t.id = c.turma_id
                INNER JOIN usuario u ON u.id = c.usuario_id
                WHERE c.data = CURDATE()
                ORDER BY t.turno, t.nome";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Busca uma chamada pelo ID com detalhes completos e estatísticas.
     *
     * @param int $id ID da chamada
     * @return object|false Objeto da chamada com detalhes ou false se não encontrada
     */
    public function findComDetalhes(int $id): object|false
    {
        $sql = "SELECT c.*, t.nome as turma_nome, t.turno, t.serie, u.nome as usuario_nome,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'presente') as total_presentes,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'ausente') as total_ausentes,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'justificado') as total_justificados
                FROM chamada c
                INNER JOIN turma t ON t.id = c.turma_id
                INNER JOIN usuario u ON u.id = c.usuario_id
                WHERE c.id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    /**
     * Marca uma chamada como concluída, registrando a data/hora de conclusão.
     *
     * @param int $id ID da chamada
     * @return bool True se a operação foi bem-sucedida
     */
    public function concluir(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE chamada SET status = 'concluida', concluida_em = NOW() WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Retorna o total de alunos presentes nas chamadas do dia atual.
     *
     * @return int Quantidade de presenças hoje
     */
    public function totalPresentesHoje(): int
    {
        $sql = "SELECT COUNT(*) FROM frequencia f
                INNER JOIN chamada c ON c.id = f.chamada_id
                WHERE c.data = CURDATE() AND f.situacao = 'presente'";
        return (int) $this->db->query($sql)->fetchColumn();
    }

    /**
     * Retorna o total de alunos ausentes ou com falta justificada nas chamadas do dia atual.
     *
     * @return int Quantidade de ausências hoje
     */
    public function totalAusentesHoje(): int
    {
        $sql = "SELECT COUNT(*) FROM frequencia f
                INNER JOIN chamada c ON c.id = f.chamada_id
                WHERE c.data = CURDATE() AND f.situacao IN ('ausente','justificado')";
        return (int) $this->db->query($sql)->fetchColumn();
    }

    /**
     * Retorna o total de turmas que ainda não tiveram a chamada concluída hoje.
     *
     * @return int Quantidade de turmas pendentes
     */
    public function totalPendentesHoje(): int
    {
        $sql = "SELECT COUNT(*) FROM turma t
                INNER JOIN ano_letivo al ON al.id = t.ano_letivo_id AND al.ativo = 1
                LEFT JOIN chamada c ON c.turma_id = t.id AND c.data = CURDATE() AND c.status = 'concluida'
                WHERE c.id IS NULL";
        return (int) $this->db->query($sql)->fetchColumn();
    }

    /**
     * Busca chamadas dentro de um período, opcionalmente filtradas por turma.
     *
     * @param string $dataInicio Data inicial do período (formato Y-m-d)
     * @param string $dataFim Data final do período (formato Y-m-d)
     * @param int|null $turmaId ID da turma para filtro (opcional)
     * @return array Lista de chamadas do período com estatísticas
     */
    public function findPorPeriodo(string $dataInicio, string $dataFim, ?int $turmaId = null): array
    {
        $sql = "SELECT c.*, t.nome as turma_nome, t.turno, u.nome as usuario_nome,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'presente') as total_presentes,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'ausente') as total_ausentes,
                (SELECT COUNT(*) FROM frequencia f WHERE f.chamada_id = c.id AND f.situacao = 'justificado') as total_justificados
                FROM chamada c
                INNER JOIN turma t ON t.id = c.turma_id
                INNER JOIN usuario u ON u.id = c.usuario_id
                WHERE c.data BETWEEN :data_inicio AND :data_fim";
        $params = ['data_inicio' => $dataInicio, 'data_fim' => $dataFim];
        if ($turmaId) {
            $sql .= " AND c.turma_id = :turma_id";
            $params['turma_id'] = $turmaId;
        }
        $sql .= " ORDER BY c.data DESC, t.nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
}
