<?php

/**
 * VertixController — Dashboard SaaS Vertix UI
 *
 * @package FeiraTecG1
 */
class VertixController extends Controller
{
    /**
     * Exibe o dashboard moderno SaaS Vertix UI
     */
    public function index(): void
    {
        $htmlFile = ROOT_PATH . '/vertix.html';
        if (file_exists($htmlFile)) {
            $content = file_get_contents($htmlFile);
            // Garante que os caminhos públicos funcionem independentemente do nível de rota
            $content = str_replace('href="public/', 'href="' . BASE_URL . '/public/', $content);
            $content = str_replace('src="public/', 'src="' . BASE_URL . '/public/', $content);
            $content = str_replace('href="index.php"', 'href="' . BASE_URL . '/"', $content);
            echo $content;
            exit;
        } else {
            echo "Vertix UI não encontrado.";
        }
    }
}
