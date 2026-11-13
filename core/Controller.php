<?php

/**
 * Controlador base da aplicação
 * Fornece métodos para carregar modelos e renderizar views
 *
 * @package FeiraTecG1
 */
class Controller
{
    /**
     * Carrega e instancia um modelo
     *
     * @param string $model Nome do modelo a ser carregado
     * @return object Instância do modelo
     */
    protected function model(string $model): object
    {
        $arquivoModelo = ROOT_PATH . '/app/models/' . $model . '.php';

        if (!file_exists($arquivoModelo)) {
            die('Erro: Modelo "' . $model . '" não encontrado.');
        }

        require_once $arquivoModelo;

        return new $model();
    }

    /**
     * Renderiza uma view com os dados fornecidos
     * Inclui automaticamente o cabeçalho e rodapé do template
     *
     * @param string $view Caminho da view (ex: 'home/index')
     * @param array  $data Dados a serem passados para a view
     * @return void
     */
    protected function view(string $view, array $data = []): void
    {
        $arquivoView = ROOT_PATH . '/app/views/' . $view . '.php';

        if (!file_exists($arquivoView)) {
            die('Erro: View "' . $view . '" não encontrada.');
        }

        // Extrai as variáveis do array para uso direto na view
        extract($data);

        // Inclui o cabeçalho do template
        $header = ROOT_PATH . '/app/views/templates/header.php';
        if (file_exists($header)) {
            require_once $header;
        }

        // Inclui a view principal
        require $arquivoView;

        // Inclui o rodapé do template
        $footer = ROOT_PATH . '/app/views/templates/footer.php';
        if (file_exists($footer)) {
            require_once $footer;
        }
    }
}
