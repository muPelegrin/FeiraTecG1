<?php

/**
 * Modelo de Ano Letivo
 * Gerencia operações de banco de dados para a tabela de anos letivos.
 */
class AnoLetivo extends Model
{
    protected string $table = 'ano_letivo';

    /**
     * Busca o ano letivo ativo mais recente.
     *
     * @return object|false Objeto do ano letivo ativo ou false se não encontrado
     */
    public function findAtivo(): object|false
    {
        $stmt = $this->db->query("SELECT * FROM ano_letivo WHERE ativo = 1 ORDER BY ano DESC LIMIT 1");
        return $stmt->fetch();
    }
}
