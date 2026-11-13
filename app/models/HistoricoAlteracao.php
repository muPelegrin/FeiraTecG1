<?php

/**
 * Modelo de Histórico de Alteração
 * Gerencia operações de banco de dados para a tabela de histórico de alterações,
 * registrando todas as mudanças feitas nos dados do sistema para auditoria.
 */
class HistoricoAlteracao extends Model
{
    protected string $table = 'historico_alteracao';

    /**
     * Registra uma alteração no histórico de auditoria.
     *
     * @param string $tabela Nome da tabela alterada
     * @param int $registroId ID do registro alterado
     * @param string $campo Nome do campo alterado
     * @param string|null $valorAnterior Valor anterior do campo
     * @param string|null $valorNovo Novo valor do campo
     * @param int $usuarioId ID do usuário que realizou a alteração
     * @param string $justificativa Motivo da alteração
     * @return int|false ID do registro de histórico criado ou false em caso de erro
     */
    public function registrar(string $tabela, int $registroId, string $campo, ?string $valorAnterior, ?string $valorNovo, int $usuarioId, string $justificativa): int|false
    {
        return $this->create([
            'tabela' => $tabela,
            'registro_id' => $registroId,
            'campo' => $campo,
            'valor_anterior' => $valorAnterior,
            'valor_novo' => $valorNovo,
            'usuario_id' => $usuarioId,
            'justificativa' => $justificativa,
        ]);
    }

    /**
     * Busca o histórico de alterações de um registro específico.
     *
     * @param string $tabela Nome da tabela
     * @param int $registroId ID do registro
     * @return array Lista de alterações ordenadas da mais recente para a mais antiga
     */
    public function findByRegistro(string $tabela, int $registroId): array
    {
        $sql = "SELECT h.*, u.nome as usuario_nome FROM historico_alteracao h
                INNER JOIN usuario u ON u.id = h.usuario_id
                WHERE h.tabela = :tabela AND h.registro_id = :registro_id
                ORDER BY h.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['tabela' => $tabela, 'registro_id' => $registroId]);
        return $stmt->fetchAll();
    }

    /**
     * Retorna as alterações mais recentes do sistema.
     *
     * @param int $limite Número máximo de registros a retornar (padrão: 50)
     * @return array Lista das alterações mais recentes
     */
    public function findRecentes(int $limite = 50): array
    {
        $sql = "SELECT h.*, u.nome as usuario_nome FROM historico_alteracao h
                INNER JOIN usuario u ON u.id = h.usuario_id
                ORDER BY h.created_at DESC LIMIT :limite";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
