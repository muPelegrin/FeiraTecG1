<?php

/**
 * Controlador do Painel da Cozinha
 * Consolidação operacional de refeições e previsão com base na frequência
 *
 * @package FeiraTecG1
 */
class CozinhaController extends Controller
{
    /**
     * Exibe o painel consolidado da Cozinha com previsão e alertas
     */
    public function index(): void
    {
        Middleware::requireRole(['admin', 'gestor', 'cozinha']);

        $chamadaModel = $this->model('Chamada');
        $turmaModel = $this->model('Turma');
        $cardapioModel = $this->model('Cardapio');
        $restricaoModel = $this->model('RestricaoAlimentar');

        $chamadasHoje = $chamadaModel->findHoje();
        $turmasPendentes = $turmaModel->turmasPendentesHoje();
        $cardapiosHoje = $cardapioModel->findHoje();

        // Totais confirmados de chamadas concluídas
        $presentesConfirmados = 0;
        $ausentesConfirmados = 0;
        $totalMatriculadosConcluidos = 0;

        foreach ($chamadasHoje as $ch) {
            if ($ch->status === 'concluida') {
                $presentesConfirmados += (int) $ch->total_presentes;
                $ausentesConfirmados += (int) $ch->total_ausentes;
                $totalMatriculadosConcluidos += (int) $ch->total_matriculados;
            }
        }

        // Estimativa para turmas ainda pendentes
        $estimativaPendentes = 0;
        foreach ($turmasPendentes as $tp) {
            $estimativaPendentes += (int) $tp->total_alunos;
        }

        $previsaoTotalEstimada = $presentesConfirmados + $estimativaPendentes;
        $todasChamadasConcluidas = count($turmasPendentes) === 0;

        // Restrições alimentares ativas (apenas orientações operacionais, sem diagnósticos)
        $restricoes = $restricaoModel->findAllAtivas();

        $this->view('cozinha/index', [
            'titulo'                  => 'Painel Operacional da Cozinha',
            'chamadasHoje'            => $chamadasHoje,
            'turmasPendentes'         => $turmasPendentes,
            'cardapiosHoje'           => $cardapiosHoje,
            'presentesConfirmados'    => $presentesConfirmados,
            'ausentesConfirmados'     => $ausentesConfirmados,
            'estimativaPendentes'     => $estimativaPendentes,
            'previsaoTotalEstimada'   => $previsaoTotalEstimada,
            'todasChamadasConcluidas' => $todasChamadasConcluidas,
            'restricoes'              => $restricoes,
            'horarioAtualizacao'      => date('H:i:s'),
        ]);
    }
}
