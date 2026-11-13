<?php

/**
 * Controlador de Autenticação
 * Gerencia login, logout e controle de acesso
 *
 * @package FeiraTecG1
 */
class AuthController extends Controller
{
    /**
     * Exibe formulário e processa autenticação de usuário
     */
    public function login(): void
    {
        // Se o usuário já estiver logado, redireciona ao painel principal
        if (Middleware::isLoggedIn()) {
            redirect('/');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validação de token CSRF
            $token = $_POST['csrf_token'] ?? '';
            if (!verifyCsrf($token)) {
                flash('mensagem', 'Token de segurança expirado ou inválido. Tente novamente.', 'danger');
                redirect('/auth/login');
            }

            $email = sanitize($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            if (empty($email) || empty($senha)) {
                flash('mensagem', 'Por favor, preencha o e-mail e a senha.', 'warning');
                $_SESSION['old_input'] = ['email' => $email];
                redirect('/auth/login');
            }

            $usuarioModel = $this->model('Usuario');
            $usuario = $usuarioModel->findByEmail($email);

            if ($usuario && password_verify($senha, $usuario->senha_hash)) {
                // Inicia a sessão com os dados do usuário autenticado
                $_SESSION['usuario_id']     = $usuario->id;
                $_SESSION['usuario_nome']   = $usuario->nome;
                $_SESSION['usuario_email']  = $usuario->email;
                $_SESSION['usuario_perfil'] = $usuario->perfil;

                flash('mensagem', 'Bem-vindo(a), ' . $usuario->nome . '!', 'success');
                redirect('/');
            } else {
                flash('mensagem', 'E-mail ou senha incorretos.', 'danger');
                $_SESSION['old_input'] = ['email' => $email];
                redirect('/auth/login');
            }
        }

        // Renderiza tela de login limpa (sem template de cabeçalho/menu padrão)
        require ROOT_PATH . '/app/views/auth/login.php';
    }

    /**
     * Encerra a sessão do usuário
     */
    public function logout(): void
    {
        session_unset();
        session_destroy();
        session_start();
        flash('mensagem', 'Você encerrou a sessão com sucesso.', 'info');
        redirect('/auth/login');
    }
}
