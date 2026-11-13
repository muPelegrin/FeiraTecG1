<?php

/**
 * Controlador da Página Inicial (Dashboard)
 * Exibe o painel principal com resumo das informações do sistema
 *
 * @package FeiraTecG1
 */
class HomeController extends Controller
{
    /**
     * Exibe o dashboard principal com indicadores consolidados
     */
    public function index(): void
    {
        Middleware::requireLogin();

        $chamadaModel = $this->model('Chamada');
        $alunoModel = $this->model('Aluno');
        $turmaModel = $this->model('Turma');
        $cardapioModel = $this->model('Cardapio');

        $dados = [
            'titulo'            => 'Painel Principal',
            'totalAlunos'       => $alunoModel->totalAtivos(),
            'presentesHoje'     => $chamadaModel->totalPresentesHoje(),
            'ausentesHoje'      => $chamadaModel->totalAusentesHoje(),
            'chamadasPendentes' => $chamadaModel->totalPendentesHoje(),
            'chamadasHoje'      => $chamadaModel->findHoje(),
            'turmasPendentes'   => $turmaModel->turmasPendentesHoje(),
            'cardapiosHoje'     => $cardapioModel->findHoje(),
        ];

        $this->view('home/index', $dados);
    }
}
