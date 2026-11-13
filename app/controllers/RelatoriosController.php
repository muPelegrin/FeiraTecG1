<?php

/**
 * Controlador de Relatórios Gerenciais e Auditoria
 * Relatórios de frequência por período, taxas de ausência e trilha de auditoria
 *
 * @package FeiraTecG1
 */
class RelatoriosController extends Controller
{
    /**
     * Menu inicial de relatórios
     */
    public function index(): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $turmaModel = $this->model('Turma');
        $anoLetivoModel = $this->model('AnoLetivo');
        $anoLetivo = $anoLetivoModel->findAtivo();
        $turmas = $anoLetivo ? $turmaModel->findByAnoLetivo($anoLetivo->id) : [];

        $this->view('relatorios/index', [
            'titulo' => 'Relatórios Gerenciais',
            'turmas' => $turmas,
        ]);
    }

    /**
     * Relatório de frequência por período e turma
     */
    public function frequencia(): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $dataInicio = $_GET['data_inicio'] ?? date('Y-m-01');
        $dataFim = $_GET['data_fim'] ?? date('Y-m-d');
        $turmaId = !empty($_GET['turma_id']) ? (int) $_GET['turma_id'] : null;

        $chamadaModel = $this->model('Chamada');
        $turmaModel = $this->model('Turma');
        $anoLetivoModel = $this->model('AnoLetivo');
        $anoLetivo = $anoLetivoModel->findAtivo();
        $turmas = $anoLetivo ? $turmaModel->findByAnoLetivo($anoLetivo->id) : [];

        $chamadas = $chamadaModel->findPorPeriodo($dataInicio, $dataFim, $turmaId);

        // Exportação CSV se solicitada
        if (isset($_GET['export']) && $_GET['export'] === 'csv') {
            header('Content-Type: text/csv; charset=utf-8');
            header('Content-Disposition: attachment; filename=frequencia_' . $dataInicio . '_a_' . $dataFim . '.csv');

            $output = fopen('php://output', 'w');
            // BOM UTF-8 para Excel
            fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($output, ['Data', 'Turma', 'Turno', 'Presentes', 'Ausentes', 'Justificados', 'Responsavel', 'Status'], ';');

            foreach ($chamadas as $c) {
                fputcsv($output, [
                    formatDate($c->data),
                    $c->turma_nome,
                    ucfirst($c->turno),
                    $c->total_presentes,
                    $c->total_ausentes,
                    $c->total_justificados,
                    $c->usuario_nome,
                    ucfirst($c->status),
                ], ';');
            }
            fclose($output);
            exit;
        }

        // Consolidação de estatísticas
        $totalPresentes = 0;
        $totalAusentes = 0;
        $totalJustificados = 0;

        foreach ($chamadas as $ch) {
            $totalPresentes += (int) $ch->total_presentes;
            $totalAusentes += (int) $ch->total_ausentes;
            $totalJustificados += (int) $ch->total_justificados;
        }

        $totalRegistros = $totalPresentes + $totalAusentes + $totalJustificados;
        $taxaPresenca = $totalRegistros > 0 ? round(($totalPresentes / $totalRegistros) * 100, 1) : 0;
        $taxaAusencia = $totalRegistros > 0 ? round((($totalAusentes + $totalJustificados) / $totalRegistros) * 100, 1) : 0;

        $this->view('relatorios/frequencia', [
            'titulo'            => 'Relatório de Frequência por Período',
            'chamadas'          => $chamadas,
            'turmas'            => $turmas,
            'dataInicio'        => $dataInicio,
            'dataFim'           => $dataFim,
            'turmaIdFiltro'     => $turmaId,
            'totalPresentes'    => $totalPresentes,
            'totalAusentes'     => $totalAusentes,
            'totalJustificados' => $totalJustificados,
            'taxaPresenca'      => $taxaPresenca,
            'taxaAusencia'      => $taxaAusencia,
        ]);
    }

    /**
     * Relatório da trilha de auditoria (histórico de alterações)
     */
    public function auditoria(): void
    {
        Middleware::requireRole(['admin', 'gestor']);

        $historicoModel = $this->model('HistoricoAlteracao');
        $historicos = $historicoModel->findRecentes(150);

        $this->view('relatorios/auditoria', [
            'titulo'     => 'Trilha de Auditoria do Sistema',
            'historicos' => $historicos,
        ]);
    }
}
