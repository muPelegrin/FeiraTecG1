<?php

/**
 * Modelo de Item do Cardápio
 * Gerencia operações de banco de dados para a tabela de itens do cardápio,
 * representando os alimentos individuais de cada refeição.
 */
class ItemCardapio extends Model
{
    protected string $table = 'item_cardapio';

    /**
     * Retorna todos os itens de um cardápio específico.
     *
     * @param int $cardapioId ID do cardápio
     * @return array Lista de itens do cardápio
     */
    public function findByCardapio(int $cardapioId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM item_cardapio WHERE cardapio_id = :cardapio_id ORDER BY id");
        $stmt->execute(['cardapio_id' => $cardapioId]);
        return $stmt->fetchAll();
    }

    /**
     * Remove todos os itens de um cardápio (usado antes de recadastrar os itens).
     *
     * @param int $cardapioId ID do cardápio
     * @return bool True se a operação foi bem-sucedida
     */
    public function deleteByCardapio(int $cardapioId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM item_cardapio WHERE cardapio_id = :cardapio_id");
        return $stmt->execute(['cardapio_id' => $cardapioId]);
    }
}
