<?php

/**
 * Classe de conexão com o banco de dados
 * Implementa o padrão Singleton para garantir uma única instância PDO
 *
 * @package FeiraTecG1
 */
class Database
{
    /** @var Database|null Instância única da classe */
    private static ?Database $instance = null;

    /** @var PDO Objeto de conexão PDO */
    private PDO $pdo;

    /**
     * Construtor privado - impede instanciação direta
     * Estabelece a conexão com o banco de dados MySQL via PDO
     */
    private function __construct()
    {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;

        $opcoes = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        try {
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, $opcoes);
        } catch (PDOException $e) {
            // Encerra a execução com mensagem de erro amigável
            die('Erro ao conectar com o banco de dados: ' . $e->getMessage());
        }
    }

    /**
     * Retorna a instância PDO (Singleton)
     * Cria a conexão na primeira chamada e reutiliza nas seguintes
     *
     * @return PDO Instância da conexão PDO
     */
    public static function connect(): PDO
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance->pdo;
    }
}
