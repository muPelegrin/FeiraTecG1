<?php

/**
 * Modelo base da aplicação
 * Fornece conexão com o banco de dados e métodos CRUD genéricos
 * Todos os métodos utilizam prepared statements para segurança
 *
 * @package FeiraTecG1
 */
class Model
{
    /** @var PDO Instância da conexão com o banco de dados */
    protected PDO $db;

    /** @var string Nome da tabela associada ao modelo */
    protected string $table = '';

    /**
     * Construtor - obtém a conexão com o banco de dados
     */
    public function __construct()
    {
        $this->db = Database::connect();
    }

    /**
     * Busca todos os registros da tabela
     *
     * @return array Lista de todos os registros
     */
    public function findAll(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);

        return $stmt->fetchAll();
    }

    /**
     * Busca um registro pelo ID
     *
     * @param int $id Identificador do registro
     * @return object|false Registro encontrado ou false
     */
    public function findById(int $id): object|false
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Insere um novo registro na tabela
     *
     * @param array $data Dados do registro (chave => valor)
     * @return int|false ID do registro inserido ou false em caso de falha
     */
    public function create(array $data): int|false
    {
        $campos = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$campos}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $chave => $valor) {
            $stmt->bindValue(':' . $chave, $valor);
        }

        if ($stmt->execute()) {
            return (int) $this->db->lastInsertId();
        }

        return false;
    }

    /**
     * Atualiza um registro existente pelo ID
     *
     * @param int   $id   Identificador do registro
     * @param array $data Dados a serem atualizados (chave => valor)
     * @return bool Verdadeiro se a atualização foi bem-sucedida
     */
    public function update(int $id, array $data): bool
    {
        $campos = '';
        foreach (array_keys($data) as $chave) {
            $campos .= "{$chave} = :{$chave}, ";
        }
        $campos = rtrim($campos, ', ');

        $sql = "UPDATE {$this->table} SET {$campos} WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        foreach ($data as $chave => $valor) {
            $stmt->bindValue(':' . $chave, $valor);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Remove um registro pelo ID
     *
     * @param int $id Identificador do registro a ser removido
     * @return bool Verdadeiro se a remoção foi bem-sucedida
     */
    public function delete(int $id): bool
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
