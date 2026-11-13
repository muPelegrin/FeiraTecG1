<?php

/**
 * Classe principal da aplicação - Roteador/Despachante
 * Analisa a URL e direciona para o controlador e método corretos
 *
 * @package FeiraTecG1
 */
class App
{
    /** @var object|string Instância ou nome do controlador */
    protected object|string $controller = 'HomeController';

    /** @var string Nome do método padrão */
    protected string $method = 'index';

    /** @var array Parâmetros da URL */
    protected array $params = [];

    /**
     * Construtor - inicializa o roteamento da aplicação
     * Analisa a URL, carrega o controlador e executa o método solicitado
     */
    public function __construct()
    {
        $url = $this->parseUrl();

        // Verifica se um controlador foi informado na URL
        if (isset($url[0]) && !empty($url[0])) {
            $nomeControlador = ucfirst(strtolower($url[0])) . 'Controller';
            $arquivoControlador = ROOT_PATH . '/app/controllers/' . $nomeControlador . '.php';

            if (file_exists($arquivoControlador)) {
                $this->controller = $nomeControlador;
                unset($url[0]);
            } else {
                // Controlador não encontrado - exibe página 404
                $this->exibir404();
                return;
            }
        }

        // Carrega o arquivo do controlador
        $arquivoControlador = ROOT_PATH . '/app/controllers/' . $this->controller . '.php';

        if (!file_exists($arquivoControlador)) {
            $this->exibir404();
            return;
        }

        require_once $arquivoControlador;

        // Instancia o controlador
        $this->controller = new $this->controller();

        // Verifica se um método foi informado na URL
        if (isset($url[1]) && !empty($url[1])) {
            $metodo = strtolower($url[1]);

            if (method_exists($this->controller, $metodo)) {
                $this->method = $metodo;
                unset($url[1]);
            } else {
                // Método não encontrado - exibe página 404
                $this->exibir404();
                return;
            }
        }

        // Coleta os parâmetros restantes da URL
        $this->params = $url ? array_values($url) : [];

        // Executa o método do controlador com os parâmetros
        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    /**
     * Analisa e sanitiza a URL recebida
     *
     * @return array Segmentos da URL em formato de array
     */
    protected function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            return explode('/', $url);
        }

        return [];
    }

    /**
     * Exibe uma página de erro 404 (Não Encontrado)
     *
     * @return void
     */
    protected function exibir404(): void
    {
        http_response_code(404);

        $arquivo404 = ROOT_PATH . '/app/views/errors/404.php';

        if (file_exists($arquivo404)) {
            require_once $arquivo404;
        } else {
            echo '<h1>404 - Página Não Encontrada</h1>';
            echo '<p>A página que você procura não existe.</p>';
            echo '<a href="' . BASE_URL . '/">Voltar ao Início</a>';
        }

        exit;
    }
}
