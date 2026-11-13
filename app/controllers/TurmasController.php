<?php

/**
 * Controlador de Turmas
 * Gerenciamento e listagem de turmas escolares
 *
 * @package FeiraTecG1
 */
class TurmasController extends Controller
{
    /**
     * Lista todas as turmas cadastradas
     */
    public function index(): void
    {
        Middleware::requireRole(['admin', 'gestor', 'inspetor']);

        $turmaModel = $this->model('Turma');
        $turmas = $turmaModel->findAllComAnoLetivo();

        $this->view('turmas/index', [
            'titulo' => 'Turmas Cadastradas',
            'turmas' => $turmas,
        ]);
    }

    /**
     * Cadastro de nova turma
     */
    public function criar(): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $anoLetivoModel = $this->model('AnoLetivo');
        $anoLetivo = $anoLetivoModel->findAtivo();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/turmas/criar');
            }

            if (!$anoLetivo) {
                flash('mensagem', 'Nenhum ano letivo ativo encontrado no sistema.', 'danger');
                redirect('/turmas');
            }

            $nome = sanitize($_POST['nome'] ?? '');
            $turno = sanitize($_POST['turno'] ?? '');
            $serie = sanitize($_POST['serie'] ?? '');

            if (empty($nome) || empty($turno) || empty($serie)) {
                flash('mensagem', 'Preencha todos os campos obrigatórios.', 'warning');
                $_SESSION['old_input'] = $_POST;
                redirect('/turmas/criar');
            }

            $turmaModel = $this->model('Turma');
            $turmaModel->create([
                'nome'          => $nome,
                'ano_letivo_id' => $anoLetivo->id,
                'turno'         => $turno,
                'serie'         => $serie,
            ]);

            flash('mensagem', 'Turma "' . $nome . '" cadastrada com sucesso!', 'success');
            redirect('/turmas');
        }

        $this->view('turmas/criar', [
            'titulo'    => 'Nova Turma',
            'anoLetivo' => $anoLetivo,
        ]);
    }

    /**
     * Edição de turma existente
     */
    public function editar(int $id = 0): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $turmaModel = $this->model('Turma');
        $turma = $turmaModel->findById($id);

        if (!$turma) {
            flash('mensagem', 'Turma não encontrada.', 'danger');
            redirect('/turmas');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/turmas/editar/' . $id);
            }

            $nome = sanitize($_POST['nome'] ?? '');
            $turno = sanitize($_POST['turno'] ?? '');
            $serie = sanitize($_POST['serie'] ?? '');

            if (empty($nome) || empty($turno) || empty($serie)) {
                flash('mensagem', 'Preencha todos os campos obrigatórios.', 'warning');
                redirect('/turmas/editar/' . $id);
            }

            $turmaModel->update($id, [
                'nome'  => $nome,
                'turno' => $turno,
                'serie' => $serie,
            ]);

            flash('mensagem', 'Turma "' . $nome . '" atualizada com sucesso!', 'success');
            redirect('/turmas');
        }

        $this->view('turmas/editar', [
            'titulo' => 'Editar Turma',
            'turma'  => $turma,
        ]);
    }

    /**
     * Exclusão de turma
     */
    public function excluir(int $id = 0): void
    {
        Middleware::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $turmaModel = $this->model('Turma');
            try {
                $turmaModel->delete($id);
                flash('mensagem', 'Turma excluída com sucesso!', 'success');
            } catch (PDOException $e) {
                flash('mensagem', 'Não é possível excluir esta turma pois ela possui alunos ou chamadas vinculadas.', 'danger');
            }
        }

        redirect('/turmas');
    }
}
