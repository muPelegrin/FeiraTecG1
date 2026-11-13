<?php

/**
 * Script de inicialização e carga do banco de dados
 * Executa schema.sql e seed.sql
 *
 * @package FeiraTecG1
 */

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/config/config.php';

echo "=== Configurando banco de dados: " . DB_NAME . " ===\n";

try {
    // Conecta sem selecionar banco para criá-lo se não existir
    $pdo = new PDO('mysql:host=' . DB_HOST . ';charset=' . DB_CHARSET, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    ]);

    echo "1. Executando schema.sql...\n";
    $schemaSql = file_get_contents(ROOT_PATH . '/database/schema.sql');
    $pdo->exec($schemaSql);
    echo " -> Schema executado com sucesso!\n";

    echo "2. Executando seed.sql...\n";
    $seedSql = file_get_contents(ROOT_PATH . '/database/seed.sql');
    $pdo->exec($seedSql);
    echo " -> Seed de dados fictícios executado com sucesso!\n";

    echo "=== Concluído com sucesso! ===\n";
    echo "Usuários de teste disponíveis (senha padrão: senha123):\n";
    echo " - admin@escola.com    (perfil: admin)\n";
    echo " - maria@escola.com    (perfil: inspetor)\n";
    echo " - joao@escola.com     (perfil: cozinha)\n";
    echo " - ana@escola.com      (perfil: gestor)\n";
    echo " - carlos@escola.com   (perfil: gestor)\n";

} catch (Exception $e) {
    echo "ERRO: " . $e->getMessage() . "\n";
    exit(1);
}
