<?php

/**
 * Funções auxiliares (helpers) da aplicação
 * Funções utilitárias disponíveis globalmente em todo o sistema
 *
 * @package FeiraTecG1
 */

/**
 * Redireciona o usuário para uma URL interna da aplicação
 *
 * @param string $url Caminho relativo (ex: '/auth/login')
 * @return void
 */
function redirect(string $url): void
{
    header('Location: ' . BASE_URL . $url);
    exit;
}

/**
 * Sanitiza dados de entrada para prevenir XSS
 *
 * @param string $data Dado a ser sanitizado
 * @return string Dado sanitizado
 */
function sanitize(string $data): string
{
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Formata uma data para o padrão brasileiro
 *
 * @param string $date   Data a ser formatada
 * @param string $format Formato desejado (padrão: d/m/Y)
 * @return string Data formatada
 */
function formatDate(string $date, string $format = 'd/m/Y'): string
{
    $dateTime = new DateTime($date);
    return $dateTime->format($format);
}

/**
 * Formata uma data e hora para o padrão brasileiro
 *
 * @param string $datetime Data e hora a ser formatada
 * @return string Data e hora formatada (d/m/Y H:i)
 */
function formatDateTime(string $datetime): string
{
    $dateTime = new DateTime($datetime);
    return $dateTime->format('d/m/Y H:i');
}

/**
 * Gerencia mensagens flash (mensagens temporárias de feedback)
 * Se $message for fornecida, armazena a mensagem na sessão.
 * Se não, retorna e remove a mensagem armazenada.
 *
 * @param string      $name    Nome identificador da mensagem
 * @param string|null $message Texto da mensagem (null para recuperar)
 * @param string      $type    Tipo da mensagem (success, danger, warning, info)
 * @return string|null HTML da mensagem flash ou null
 */
function flash(string $name, ?string $message = null, string $type = 'success'): ?string
{
    // Se a mensagem foi fornecida, armazena na sessão
    if ($message !== null) {
        $_SESSION['flash'][$name] = [
            'message' => $message,
            'type'    => $type,
        ];
        return null;
    }

    // Se existe uma mensagem flash com esse nome, exibe e remove
    if (isset($_SESSION['flash'][$name])) {
        $flash = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);

        return '<div class="alert alert-' . sanitize($flash['type']) . '">'
            . sanitize($flash['message'])
            . '<button class="alert-close" onclick="this.parentElement.remove()">&times;</button>'
            . '</div>';
    }

    return null;
}

/**
 * Gera e armazena um token CSRF na sessão
 *
 * @return string Token CSRF gerado
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Verifica se o token CSRF fornecido é válido
 *
 * @param string $token Token CSRF a ser verificado
 * @return bool Verdadeiro se o token é válido
 */
function verifyCsrf(string $token): bool
{
    if (isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token)) {
        // Remove o token após a verificação para prevenir reutilização
        unset($_SESSION['csrf_token']);
        return true;
    }

    return false;
}

/**
 * Verifica se o caminho atual corresponde ao item de menu
 * Retorna a classe CSS 'active' para destacar o menu ativo
 *
 * @param string $path Caminho do item de menu
 * @return string Classe 'active' ou string vazia
 */
function isActiveMenu(string $path): string
{
    $urlAtual = $_GET['url'] ?? '';
    $urlAtual = '/' . trim($urlAtual, '/');
    $path = '/' . trim($path, '/');

    // Verifica correspondência exata ou se a URL começa com o caminho
    if ($urlAtual === $path || str_starts_with($urlAtual, $path . '/')) {
        return 'active';
    }

    return '';
}

/**
 * Retorna o valor anterior de um campo de formulário
 * Útil para repopular formulários após erros de validação
 *
 * @param string $field   Nome do campo
 * @param string $default Valor padrão caso não exista valor anterior
 * @return string Valor anterior do campo ou valor padrão
 */
function old(string $field, string $default = ''): string
{
    if (isset($_SESSION['old_input'][$field])) {
        $valor = $_SESSION['old_input'][$field];
        unset($_SESSION['old_input'][$field]);
        return sanitize($valor);
    }

    return $default;
}
