<?php

/**
 * Controlador de Usuários e Perfis de Acesso
 * Gestão de operadores do sistema (acesso restrito ao Administrador)
 *
 * @package FeiraTecG1
 */
class UsuariosController extends Controller
{
    /**
     * Lista todos os usuários cadastrados
     */
    public function index(): void
    {
        Middleware::requireRole(['admin']);

        $usuarioModel = $this->model('Usuario');
        $usuarios = $usuarioModel->findAll();

        $this->view('usuarios/index', [
            'titulo'   => 'Gestão de Usuários',
            'usuarios' => $usuarios,
        ]);
    }

    /**
     * Cadastro de novo usuário
     */
    public function criar(): void
    {
        Middleware::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/usuarios/criar');
            }

            $nome = sanitize($_POST['nome'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $perfil = sanitize($_POST['perfil'] ?? '');

            if (empty($nome) || empty($email) || empty($senha) || empty($perfil)) {
                flash('mensagem', 'Preencha todos os campos obrigatórios.', 'warning');
                $_SESSION['old_input'] = $_POST;
                redirect('/usuarios/criar');
            }

            $usuarioModel = $this->model('Usuario');
            if ($usuarioModel->findByEmail($email)) {
                flash('mensagem', 'O e-mail informado já está cadastrado no sistema.', 'danger');
                $_SESSION['old_input'] = $_POST;
                redirect('/usuarios/criar');
            }

            $usuarioModel->create([
                'nome'       => $nome,
                'email'      => $email,
                'senha_hash' => password_hash($senha, PASSWORD_BCRYPT),
                'perfil'     => $perfil,
                'ativo'      => 1,
            ]);

            flash('mensagem', 'Usuário "' . $nome . '" cadastrado com sucesso!', 'success');
            redirect('/usuarios');
        }

        $this->view('usuarios/criar', [
            'titulo' => 'Novo Usuário',
        ]);
    }

    /**
     * Edição de dados do usuário
     */
    public function editar(int $id = 0): void
    {
        Middleware::requireRole(['admin']);

        $usuarioModel = $this->model('Usuario');
        $usuario = $usuarioModel->findById($id);

        if (!$usuario) {
            flash('mensagem', 'Usuário não encontrado.', 'danger');
            redirect('/usuarios');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/usuarios/editar/' . $id);
            }

            $nome = sanitize($_POST['nome'] ?? '');
            $email = sanitize($_POST['email'] ?? '');
            $perfil = sanitize($_POST['perfil'] ?? '');
            $senha = $_POST['senha'] ?? '';

            $dados = [
                'nome'   => $nome,
                'email'  => $email,
                'perfil' => $perfil,
            ];

            // Atualiza senha apenas se informada
            if (!empty($senha)) {
                $dados['senha_hash'] = password_hash($senha, PASSWORD_BCRYPT);
            }

            $usuarioModel->update($id, $dados);

            flash('mensagem', 'Usuário atualizado com sucesso!', 'success');
            redirect('/usuarios');
        }

        $this->view('usuarios/editar', [
            'titulo'  => 'Editar Usuário',
            'usuario' => $usuario,
        ]);
    }

    /**
     * Desativação de usuário
     */
    public function excluir(int $id = 0): void
    {
        Middleware::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($id == Middleware::currentUserId()) {
                flash('mensagem', 'Você não pode desativar seu próprio usuário em uso.', 'danger');
                redirect('/usuarios');
            }

            $usuarioModel = $this->model('Usuario');
            $usuarioModel->update($id, ['ativo' => 0]);
            flash('mensagem', 'Usuário desativado com sucesso.', 'success');
        }

        redirect('/usuarios');
    }
}
