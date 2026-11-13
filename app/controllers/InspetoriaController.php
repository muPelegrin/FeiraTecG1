<?php

/**
 * Controlador do Painel da Inspetoria
 * Monitoramento de status das chamadas em tempo real pelas turmas
 *
 * @package FeiraTecG1
 */
class InspetoriaController extends Controller
{
    /**
     * Exibe o painel de controle da Inspetoria
     */
    public function index(): void
    {
        Middleware::requireRole(['admin', 'gestor', 'inspetor']);

        $turmaModel = $this->model('Turma');
        $chamadaModel = $this->model('Chamada');

        $turmasComStatus = $turmaModel->findComStatusChamadaHoje();
        $chamadasHoje = $chamadaModel->findHoje();
        $pendentes = $turmaModel->turmasPendentesHoje();

        $totalTurmas = count($turmasComStatus);
        $totalPendentes = count($pendentes);
        $totalConcluidas = $totalTurmas - $totalPendentes;

        $this->view('inspetoria/index', [
            'titulo'             => 'Painel da Inspetoria',
            'turmasComStatus'    => $turmasComStatus,
            'chamadasHoje'       => $chamadasHoje,
            'totalTurmas'        => $totalTurmas,
            'totalPendentes'     => $totalPendentes,
            'totalConcluidas'    => $totalConcluidas,
            'horarioAtualizacao' => date('H:i:s'),
        ]);
    }
}
