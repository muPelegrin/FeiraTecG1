<?php

/**
 * Ponto de entrada principal da aplicação
 * Central de Frequência e Alimentação Escolar
 *
 * @package FeiraTecG1
 * @version 1.0.0
 */

// Inicia a sessão
session_start();

// Define o caminho raiz do projeto
define('ROOT_PATH', __DIR__);

// Detecta automaticamente a URL base da aplicação
$scriptName = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$baseUrl = rtrim($scriptName, '/');
define('BASE_URL', $baseUrl);

// Carrega as configurações da aplicação
require_once ROOT_PATH . '/config/config.php';

// Carrega a classe de conexão com o banco de dados
require_once ROOT_PATH . '/config/database.php';

// Carrega os componentes do núcleo (core)
require_once ROOT_PATH . '/core/App.php';
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/Middleware.php';
require_once ROOT_PATH . '/core/Helpers.php';

// Instancia e executa a aplicação
$app = new App();
