<?php

/**
 * Modelo de Cardápio
 * Gerencia operações de banco de dados para a tabela de cardápios,
 * organizando as refeições por data e tipo.
 */
class Cardapio extends Model
{
    protected string $table = 'cardapio';

    /**
     * Retorna o cardápio do dia atual, ordenado pelo tipo de refeição.
     *
     * @return array Lista de refeições de hoje
     */
    public function findHoje(): array
    {
        $sql = "SELECT * FROM cardapio WHERE data = CURDATE() ORDER BY FIELD(tipo_refeicao, 'cafe_manha','almoco','lanche_tarde','jantar')";
        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Retorna o cardápio de uma data específica, ordenado pelo tipo de refeição.
     *
     * @param string $data Data do cardápio (formato Y-m-d)
     * @return array Lista de refeições da data informada
     */
    public function findByData(string $data): array
    {
        $stmt = $this->db->prepare("SELECT * FROM cardapio WHERE data = :data ORDER BY FIELD(tipo_refeicao, 'cafe_manha','almoco','lanche_tarde','jantar')");
        $stmt->execute(['data' => $data]);
        return $stmt->fetchAll();
    }

    /**
     * Busca um cardápio específico por data e tipo de refeição.
     *
     * @param string $data Data do cardápio (formato Y-m-d)
     * @param string $tipo Tipo de refeição (cafe_manha, almoco, lanche_tarde, jantar)
     * @return object|false Objeto do cardápio ou false se não encontrado
     */
    public function findByDataTipo(string $data, string $tipo): object|false
    {
        $stmt = $this->db->prepare("SELECT * FROM cardapio WHERE data = :data AND tipo_refeicao = :tipo LIMIT 1");
        $stmt->execute(['data' => $data, 'tipo' => $tipo]);
        return $stmt->fetch();
    }

    /**
     * Retorna o rótulo legível para um tipo de refeição.
     *
     * @param string $tipo Tipo de refeição armazenado no banco
     * @return string Nome legível do tipo de refeição
     */
    public function tipoRefeicaoLabel(string $tipo): string
    {
        return match($tipo) {
            'cafe_manha' => 'Café da Manhã',
            'almoco' => 'Almoço',
            'lanche_tarde' => 'Lanche da Tarde',
            'jantar' => 'Jantar',
            default => $tipo,
        };
    }
}
