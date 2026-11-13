<?php

/**
 * Modelo de Usuário
 * Gerencia operações de banco de dados para a tabela de usuários do sistema.
 */
class Usuario extends Model
{
    protected string $table = 'usuario';

    /**
     * Busca um usuário ativo pelo endereço de e-mail.
     *
     * @param string $email E-mail do usuário
     * @return object|false Objeto do usuário ou false se não encontrado
     */
    public function findByEmail(string $email): object|false
    {
        $stmt = $this->db->prepare("SELECT * FROM usuario WHERE email = :email AND ativo = 1 LIMIT 1");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }

    /**
     * Retorna todos os usuários ativos ordenados pelo nome.
     *
     * @return array Lista de usuários ativos
     */
    public function findAllAtivos(): array
    {
        $stmt = $this->db->query("SELECT * FROM usuario WHERE ativo = 1 ORDER BY nome");
        return $stmt->fetchAll();
    }
}
