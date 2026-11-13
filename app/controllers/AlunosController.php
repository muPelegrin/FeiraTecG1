<?php

/**
 * Controlador de Alunos
 * Cadastro de alunos, matrículas e orientações operacionais de alimentação
 *
 * @package FeiraTecG1
 */
class AlunosController extends Controller
{
    /**
     * Lista alunos cadastrados com turma e restrição alimentar operacional
     */
    public function index(): void
    {
        Middleware::requireRole(['admin', 'gestor', 'inspetor']);

        $alunoModel = $this->model('Aluno');
        $alunos = $alunoModel->findComTurma();

        $this->view('alunos/index', [
            'titulo' => 'Alunos Cadastrados',
            'alunos' => $alunos,
        ]);
    }

    /**
     * Cadastro de novo estudante com matrícula em turma
     */
    public function criar(): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $turmaModel = $this->model('Turma');
        $anoLetivoModel = $this->model('AnoLetivo');
        $anoLetivo = $anoLetivoModel->findAtivo();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/alunos/criar');
            }

            $nome = sanitize($_POST['nome'] ?? '');
            $dataNascimento = $_POST['data_nascimento'] ?? '';
            $matriculaCodigo = sanitize($_POST['matricula_codigo'] ?? '');
            $turmaId = (int) ($_POST['turma_id'] ?? 0);
            $restricaoOperacional = sanitize($_POST['orientacao_operacional'] ?? '');

            if (empty($nome) || empty($dataNascimento) || empty($matriculaCodigo) || empty($turmaId)) {
                flash('mensagem', 'Preencha todos os campos obrigatórios.', 'warning');
                $_SESSION['old_input'] = $_POST;
                redirect('/alunos/criar');
            }

            $alunoModel = $this->model('Aluno');
            if ($alunoModel->existeMatriculaCodigo($matriculaCodigo)) {
                flash('mensagem', 'O código de matrícula informado já pertence a outro aluno.', 'danger');
                $_SESSION['old_input'] = $_POST;
                redirect('/alunos/criar');
            }

            $alunoId = $alunoModel->create([
                'nome'             => $nome,
                'data_nascimento'  => $dataNascimento,
                'matricula_codigo' => $matriculaCodigo,
                'ativo'            => 1,
            ]);

            if ($alunoId && $anoLetivo) {
                // Registra a matrícula
                $matriculaModel = $this->model('Matricula');
                $matriculaModel->create([
                    'aluno_id'      => $alunoId,
                    'turma_id'      => $turmaId,
                    'ano_letivo_id' => $anoLetivo->id,
                    'status'        => 'ativa',
                ]);

                // Se houver restrição alimentar operacional indispensável, registra (SEM diagnósticos)
                if (!empty($restricaoOperacional)) {
                    $restricaoModel = $this->model('RestricaoAlimentar');
                    $restricaoModel->create([
                        'aluno_id'               => $alunoId,
                        'orientacao_operacional' => $restricaoOperacional,
                        'registrado_por'         => Middleware::currentUserId(),
                        'ativo'                  => 1,
                    ]);
                }

                flash('mensagem', 'Aluno(a) "' . $nome . '" cadastrado(a) com sucesso!', 'success');
                redirect('/alunos');
            } else {
                flash('mensagem', 'Erro ao salvar matrícula do aluno.', 'danger');
                redirect('/alunos/criar');
            }
        }

        $turmas = $anoLetivo ? $turmaModel->findByAnoLetivo($anoLetivo->id) : [];

        $this->view('alunos/criar', [
            'titulo' => 'Novo Aluno',
            'turmas' => $turmas,
        ]);
    }

    /**
     * Edição dos dados cadastrais do aluno
     */
    public function editar(int $id = 0): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $alunoModel = $this->model('Aluno');
        $aluno = $alunoModel->findById($id);

        if (!$aluno) {
            flash('mensagem', 'Aluno não encontrado.', 'danger');
            redirect('/alunos');
        }

        $turmaModel = $this->model('Turma');
        $anoLetivoModel = $this->model('AnoLetivo');
        $anoLetivo = $anoLetivoModel->findAtivo();
        $turmas = $anoLetivo ? $turmaModel->findByAnoLetivo($anoLetivo->id) : [];

        $restricaoModel = $this->model('RestricaoAlimentar');
        $restricoes = $restricaoModel->findByAluno($id);
        $restricaoAtual = !empty($restricoes) ? $restricoes[0]->orientacao_operacional : '';

        // Turma atual
        $matriculaModel = $this->model('Matricula');
        $stmtMatricula = Database::connect()->prepare("SELECT turma_id FROM matricula WHERE aluno_id = :aluno_id AND status = 'ativa' LIMIT 1");
        $stmtMatricula->execute(['aluno_id' => $id]);
        $turmaAtualId = $stmtMatricula->fetchColumn() ?: 0;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/alunos/editar/' . $id);
            }

            $nome = sanitize($_POST['nome'] ?? '');
            $dataNascimento = $_POST['data_nascimento'] ?? '';
            $matriculaCodigo = sanitize($_POST['matricula_codigo'] ?? '');
            $turmaId = (int) ($_POST['turma_id'] ?? 0);
            $restricaoOperacional = sanitize($_POST['orientacao_operacional'] ?? '');

            if ($alunoModel->existeMatriculaCodigo($matriculaCodigo, $id)) {
                flash('mensagem', 'Código de matrícula já utilizado por outro aluno.', 'danger');
                redirect('/alunos/editar/' . $id);
            }

            $alunoModel->update($id, [
                'nome'             => $nome,
                'data_nascimento'  => $dataNascimento,
                'matricula_codigo' => $matriculaCodigo,
            ]);

            // Atualiza turma se mudou
            if ($turmaId > 0 && $turmaId != $turmaAtualId && $anoLetivo) {
                // Atualiza matricula anterior para transferida
                $stmtUpd = Database::connect()->prepare("UPDATE matricula SET status = 'transferida' WHERE aluno_id = :aluno_id AND status = 'ativa'");
                $stmtUpd->execute(['aluno_id' => $id]);

                // Cria nova matricula
                $matriculaModel->create([
                    'aluno_id'      => $id,
                    'turma_id'      => $turmaId,
                    'ano_letivo_id' => $anoLetivo->id,
                    'status'        => 'ativa',
                ]);
            }

            // Atualiza restrição alimentar
            if (!empty($restricaoOperacional)) {
                if (!empty($restricoes)) {
                    $restricaoModel->update($restricoes[0]->id, [
                        'orientacao_operacional' => $restricaoOperacional,
                    ]);
                } else {
                    $restricaoModel->create([
                        'aluno_id'               => $id,
                        'orientacao_operacional' => $restricaoOperacional,
                        'registrado_por'         => Middleware::currentUserId(),
                        'ativo'                  => 1,
                    ]);
                }
            }

            flash('mensagem', 'Dados do aluno atualizados com sucesso!', 'success');
            redirect('/alunos');
        }

        $this->view('alunos/editar', [
            'titulo'               => 'Editar Aluno',
            'aluno'                => $aluno,
            'turmas'               => $turmas,
            'turmaAtualId'         => $turmaAtualId,
            'restricaoOperacional' => $restricaoAtual,
        ]);
    }

    /**
     * Desativação de aluno
     */
    public function excluir(int $id = 0): void
    {
        Middleware::requireRole(['admin']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $alunoModel = $this->model('Aluno');
            $alunoModel->update($id, ['ativo' => 0]);
            flash('mensagem', 'Aluno desativado com sucesso!', 'success');
        }

        redirect('/alunos');
    }
}
