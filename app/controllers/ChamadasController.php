<?php

/**
 * Controlador de Chamadas (Frequência Escolar)
 * Abertura, registro nominal rápido, conclusão e correção auditada
 *
 * @package FeiraTecG1
 */
class ChamadasController extends Controller
{
    /**
     * Listagem geral de chamadas e turmas com status do dia
     */
    public function index(): void
    {
        Middleware::requireRole(['admin', 'gestor', 'inspetor']);

        $chamadaModel = $this->model('Chamada');
        $turmaModel = $this->model('Turma');

        $dataFiltro = $_GET['data'] ?? date('Y-m-d');
        $turmasComStatus = $turmaModel->findComStatusChamadaHoje();
        $chamadas = $chamadaModel->findHoje();

        $this->view('chamadas/index', [
            'titulo'          => 'Chamada Digital',
            'turmasComStatus' => $turmasComStatus,
            'chamadas'        => $chamadas,
            'dataFiltro'      => $dataFiltro,
        ]);
    }

    /**
     * Abertura de chamada por turma para a data atual
     * Regra: uma chamada é identificada por turma e data; não pode haver duplicidade
     */
    public function abrir(int $turmaId = 0): void
    {
        Middleware::requireRole(['admin', 'inspetor']);

        $turmaModel = $this->model('Turma');
        $turma = $turmaModel->findById($turmaId);
        if (!$turma) {
            flash('mensagem', 'Turma não encontrada.', 'danger');
            redirect('/chamadas');
        }

        $chamadaModel = $this->model('Chamada');
        $dataHoje = date('Y-m-d');

        // Verifica se já existe chamada aberta ou concluída hoje
        $chamadaExistente = $chamadaModel->findByTurmaData($turmaId, $dataHoje);
        if ($chamadaExistente) {
            flash('mensagem', 'Já existe um registro de chamada para a turma ' . $turma->nome . ' na data de hoje.', 'info');
            redirect('/chamadas/registrar/' . $chamadaExistente->id);
        }

        // Cria a nova chamada com status 'aberta'
        $chamadaId = $chamadaModel->create([
            'turma_id'   => $turmaId,
            'data'       => $dataHoje,
            'status'     => 'aberta',
            'usuario_id' => Middleware::currentUserId(),
            'aberta_em'  => date('Y-m-d H:i:s'),
        ]);

        flash('mensagem', 'Chamada aberta para a turma ' . $turma->nome . '. Realize o registro dos estudantes.', 'success');
        redirect('/chamadas/registrar/' . $chamadaId);
    }

    /**
     * Registro nominal de frequência dos alunos da turma
     * Permite marcação rápida (Presente / Ausente / Justificado)
     */
    public function registrar(int $chamadaId = 0): void
    {
        Middleware::requireRole(['admin', 'inspetor']);

        $chamadaModel = $this->model('Chamada');
        $chamada = $chamadaModel->findComDetalhes($chamadaId);

        if (!$chamada) {
            flash('mensagem', 'Chamada não encontrada.', 'danger');
            redirect('/chamadas');
        }

        $alunoModel = $this->model('Aluno');
        $frequenciaModel = $this->model('Frequencia');

        $alunos = $alunoModel->findByTurma($chamada->turma_id);
        $frequencias = $frequenciaModel->findByChamada($chamadaId);

        // Mapa de frequências existentes por aluno_id
        $frequenciaMap = [];
        foreach ($frequencias as $f) {
            $frequenciaMap[$f->aluno_id] = $f;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/chamadas/registrar/' . $chamadaId);
            }

            $situacoes = $_POST['situacao'] ?? [];
            $observacoes = $_POST['observacao'] ?? [];
            $usuarioId = Middleware::currentUserId();

            foreach ($alunos as $aluno) {
                $situacao = $situacoes[$aluno->id] ?? 'presente';
                $obs = !empty($observacoes[$aluno->id]) ? sanitize($observacoes[$aluno->id]) : null;

                $frequenciaExistente = $frequenciaModel->findByAlunoChamada($aluno->id, $chamadaId);

                if ($frequenciaExistente) {
                    if ($frequenciaExistente->situacao !== $situacao || $frequenciaExistente->observacao !== $obs) {
                        $frequenciaModel->atualizarSituacao($frequenciaExistente->id, $situacao, $obs, $usuarioId);
                    }
                } else {
                    $frequenciaModel->registrarPresenca($chamadaId, $aluno->id, $situacao, $obs, $usuarioId);
                }
            }

            // Conclui a chamada caso solicitado
            if (isset($_POST['acao']) && $_POST['acao'] === 'concluir') {
                $chamadaModel->concluir($chamadaId);
                flash('mensagem', 'Chamada da turma ' . $chamada->turma_nome . ' concluída com sucesso! Os totais foram enviados à Cozinha.', 'success');
                redirect('/chamadas/visualizar/' . $chamadaId);
            }

            flash('mensagem', 'Frequência salva temporariamente como rascunho.', 'info');
            redirect('/chamadas/registrar/' . $chamadaId);
        }

        $this->view('chamadas/registrar', [
            'titulo'        => 'Registro de Chamada - Turma ' . $chamada->turma_nome,
            'chamada'       => $chamada,
            'alunos'        => $alunos,
            'frequenciaMap' => $frequenciaMap,
        ]);
    }

    /**
     * Visualização detalhada de chamada concluída
     */
    public function visualizar(int $chamadaId = 0): void
    {
        Middleware::requireRole(['admin', 'gestor', 'inspetor']);

        $chamadaModel = $this->model('Chamada');
        $chamada = $chamadaModel->findComDetalhes($chamadaId);

        if (!$chamada) {
            flash('mensagem', 'Chamada não encontrada.', 'danger');
            redirect('/chamadas');
        }

        $frequenciaModel = $this->model('Frequencia');
        $frequencias = $frequenciaModel->findByChamada($chamadaId);

        // Histórico de auditoria para as frequências desta chamada
        $historicoModel = $this->model('HistoricoAlteracao');
        $historicos = [];
        foreach ($frequencias as $f) {
            $alteracoes = $historicoModel->findByRegistro('frequencia', $f->id);
            if (!empty($alteracoes)) {
                $historicos[$f->id] = $alteracoes;
            }
        }

        $this->view('chamadas/visualizar', [
            'titulo'      => 'Chamada - Turma ' . $chamada->turma_nome,
            'chamada'     => $chamada,
            'frequencias' => $frequencias,
            'historicos'  => $historicos,
        ]);
    }

    /**
     * Correção autorizada pós-fechamento
     * Regra: exige perfil autorizado (admin ou gestor) e registra valor anterior,
     * novo valor, autor e justificativa obrigatória na auditoria.
     */
    public function corrigir(int $frequenciaId = 0): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $frequenciaModel = $this->model('Frequencia');
        $frequencia = $frequenciaModel->findById($frequenciaId);

        if (!$frequencia) {
            flash('mensagem', 'Registro de frequência não encontrado.', 'danger');
            redirect('/chamadas');
        }

        $chamadaModel = $this->model('Chamada');
        $chamada = $chamadaModel->findComDetalhes($frequencia->chamada_id);

        $alunoModel = $this->model('Aluno');
        $aluno = $alunoModel->findById($frequencia->aluno_id);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verifyCsrf($_POST['csrf_token'] ?? '')) {
                flash('mensagem', 'Token de segurança inválido.', 'danger');
                redirect('/chamadas/corrigir/' . $frequenciaId);
            }

            $novaSituacao = sanitize($_POST['situacao'] ?? '');
            $novaObservacao = sanitize($_POST['observacao'] ?? '');
            $justificativa = sanitize($_POST['justificativa'] ?? '');

            if (empty($justificativa)) {
                flash('mensagem', 'A justificativa é OBRIGATÓRIA para registrar qualquer correção de frequência.', 'danger');
                $_SESSION['old_input'] = $_POST;
                redirect('/chamadas/corrigir/' . $frequenciaId);
            }

            $usuarioId = Middleware::currentUserId();

            // Grava na trilha de auditoria
            $historicoModel = $this->model('HistoricoAlteracao');
            $historicoModel->registrar(
                'frequencia',
                $frequenciaId,
                'situacao',
                $frequencia->situacao,
                $novaSituacao,
                $usuarioId,
                $justificativa
            );

            // Atualiza a frequência
            $frequenciaModel->atualizarSituacao($frequenciaId, $novaSituacao, $novaObservacao, $usuarioId);

            flash('mensagem', 'Correção registrada e auditada com sucesso para o aluno ' . $aluno->nome . '!', 'success');
            redirect('/chamadas/visualizar/' . $frequencia->chamada_id);
        }

        $this->view('chamadas/corrigir', [
            'titulo'     => 'Correção Autorizada de Frequência',
            'frequencia' => $frequencia,
            'chamada'    => $chamada,
            'aluno'      => $aluno,
        ]);
    }
}
