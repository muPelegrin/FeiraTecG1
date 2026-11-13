<?php

/**
 * Middleware de Autenticação e Autorização
 * Controla o acesso às páginas com base na sessão do usuário
 *
 * @package FeiraTecG1
 */
class Middleware
{
    /**
     * Exige que o usuário esteja autenticado
     * Redireciona para a página de login caso não esteja
     *
     * @return void
     */
    public static function requireLogin(): void
    {
        if (!self::isLoggedIn()) {
            flash('mensagem', 'Você precisa estar logado para acessar esta página.', 'warning');
            redirect('/auth/login');
        }
    }

    /**
     * Exige que o usuário possua um dos perfis autorizados
     * Exibe erro 403 caso o perfil não seja permitido
     *
     * @param array $roles Lista de perfis permitidos
     * @return void
     */
    public static function requireRole(array $roles): void
    {
        self::requireLogin();

        if (!in_array($_SESSION['usuario_perfil'], $roles)) {
            http_response_code(403);

            $arquivo403 = ROOT_PATH . '/app/views/errors/403.php';

            if (file_exists($arquivo403)) {
                require_once $arquivo403;
            } else {
                echo '<h1>403 - Acesso Negado</h1>';
                echo '<p>Você não tem permissão para acessar esta página.</p>';
                echo '<a href="' . BASE_URL . '/">Voltar ao Início</a>';
            }

            exit;
        }
    }

    /**
     * Verifica se o usuário está autenticado
     *
     * @return bool Verdadeiro se o usuário está logado
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['usuario_id']);
    }

    /**
     * Retorna os dados do usuário logado armazenados na sessão
     *
     * @return array|null Dados do usuário ou null se não logado
     */
    public static function currentUser(): ?array
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        return [
            'id'    => $_SESSION['usuario_id'],
            'nome'  => $_SESSION['usuario_nome'],
            'email' => $_SESSION['usuario_email'],
            'perfil' => $_SESSION['usuario_perfil'],
        ];
    }

    /**
     * Retorna o ID do usuário logado
     *
     * @return int|null ID do usuário ou null se não logado
     */
    public static function currentUserId(): ?int
    {
        return $_SESSION['usuario_id'] ?? null;
    }

    /**
     * Verifica se o usuário logado possui um determinado perfil
     *
     * @param string $role Perfil a ser verificado
     * @return bool Verdadeiro se o usuário possui o perfil
     */
    public static function hasRole(string $role): bool
    {
        if (!self::isLoggedIn()) {
            return false;
        }

        return $_SESSION['usuario_perfil'] === $role;
    }
}
