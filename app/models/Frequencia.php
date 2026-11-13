<?php

/**
 * Modelo de Frequência
 * Gerencia operações de banco de dados para a tabela de frequência,
 * registrando a presença ou ausência de cada aluno em uma chamada.
 */
class Frequencia extends Model
{
    protected string $table = 'frequencia';

    /**
     * Retorna todos os registros de frequência de uma chamada com dados do aluno.
     *
     * @param int $chamadaId ID da chamada
     * @return array Lista de registros de frequência com nome do aluno e quem registrou
     */
    public function findByChamada(int $chamadaId): array
    {
        $sql = "SELECT f.*, a.nome as aluno_nome, a.matricula_codigo, u.nome as registrado_por_nome
                FROM frequencia f
                INNER JOIN aluno a ON a.id = f.aluno_id
                INNER JOIN usuario u ON u.id = f.registrado_por
                WHERE f.chamada_id = :chamada_id
                ORDER BY a.nome";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['chamada_id' => $chamadaId]);
        return $stmt->fetchAll();
    }

    /**
     * Busca o registro de frequência de um aluno em uma chamada específica.
     *
     * @param int $alunoId ID do aluno
     * @param int $chamadaId ID da chamada
     * @return object|false Objeto da frequência ou false se não encontrado
     */
    public function findByAlunoChamada(int $alunoId, int $chamadaId): object|false
    {
        $stmt = $this->db->prepare("SELECT * FROM frequencia WHERE aluno_id = :aluno_id AND chamada_id = :chamada_id LIMIT 1");
        $stmt->execute(['aluno_id' => $alunoId, 'chamada_id' => $chamadaId]);
        return $stmt->fetch();
    }

    /**
     * Registra a presença de um aluno em uma chamada.
     *
     * @param int $chamadaId ID da chamada
     * @param int $alunoId ID do aluno
     * @param string $situacao Situação: 'presente', 'ausente' ou 'justificado'
     * @param string|null $observacao Observação opcional
     * @param int $usuarioId ID do usuário que está registrando
     * @return int|false ID do registro criado ou false em caso de erro
     */
    public function registrarPresenca(int $chamadaId, int $alunoId, string $situacao, ?string $observacao, int $usuarioId): int|false
    {
        return $this->create([
            'chamada_id' => $chamadaId,
            'aluno_id' => $alunoId,
            'situacao' => $situacao,
            'observacao' => $observacao,
            'registrado_por' => $usuarioId,
        ]);
    }

    /**
     * Atualiza a situação de frequência de um registro existente.
     *
     * @param int $id ID do registro de frequência
     * @param string $situacao Nova situação: 'presente', 'ausente' ou 'justificado'
     * @param string|null $observacao Observação atualizada
     * @param int $usuarioId ID do usuário que está alterando
     * @return bool True se a operação foi bem-sucedida
     */
    public function atualizarSituacao(int $id, string $situacao, ?string $observacao, int $usuarioId): bool
    {
        $stmt = $this->db->prepare("UPDATE frequencia SET situacao = :situacao, observacao = :observacao, registrado_por = :usuario_id WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'situacao' => $situacao,
            'observacao' => $observacao,
            'usuario_id' => $usuarioId,
        ]);
    }
}
