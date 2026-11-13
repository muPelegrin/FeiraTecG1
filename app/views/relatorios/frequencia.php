<div class="page-header">
    <div>
        <h1 class="page-header-title">📈 Relatório de Frequência e Absenteísmo</h1>
        <p class="breadcrumb-info">
            Período: <?= formatDate($dataInicio) ?> até <?= formatDate($dataFim) ?>
        </p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/relatorios/frequencia?data_inicio=<?= $dataInicio ?>&data_fim=<?= $dataFim ?>&turma_id=<?= $turmaIdFiltro ?>&export=csv" class="btn btn-success btn-sm">
            📥 Exportar CSV
        </a>
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">🖨️ Imprimir</button>
        <a href="<?= BASE_URL ?>/relatorios" class="btn btn-outline btn-sm">Voltar</a>
    </div>
</div>

<!-- Filtros de Pesquisa -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/relatorios/frequencia" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div>
                <label for="data_inicio" class="form-label">Data Início</label>
                <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?= $dataInicio ?>">
            </div>

            <div>
                <label for="data_fim" class="form-label">Data Fim</label>
                <input type="date" id="data_fim" name="data_fim" class="form-control" value="<?= $dataFim ?>">
            </div>

            <div>
                <label for="turma_id" class="form-label">Turma</label>
                <select id="turma_id" name="turma_id" class="form-control">
                    <option value="">Todas as turmas</option>
                    <?php foreach ($turmas as $t): ?>
                        <option value="<?= $t->id ?>" <?= ($t->id == $turmaIdFiltro) ? 'selected' : '' ?>>Turma <?= sanitize($t->nome) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filtrar</button>
        </form>
    </div>
</div>

<!-- Indicadores de Taxa de Presença e Ausência -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success">📊</div>
        <div class="stat-data">
            <div class="stat-number"><?= $taxaPresenca ?>%</div>
            <div class="stat-label">Taxa Geral de Presença</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">📉</div>
        <div class="stat-data">
            <div class="stat-number"><?= $taxaAusencia ?>%</div>
            <div class="stat-label">Taxa Geral de Ausência</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon primary">✅</div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalPresentes ?></div>
            <div class="stat-label">Total de Presenças</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">❌</div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalAusentes + $totalJustificados ?></div>
            <div class="stat-label">Total de Faltas (Aus. + Just.)</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Histórico Consolidado de Chamadas no Período</h3>
    </div>
    <div class="card-body">
        <?php if (empty($chamadas)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">📈</div>
                <div class="empty-state-title">Nenhum registro encontrado no período selecionado</div>
                <p>Altere os filtros de data ou turma para realizar uma nova pesquisa.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Turma</th>
                            <th>Turno</th>
                            <th>Presentes</th>
                            <th>Ausentes</th>
                            <th>Justificados</th>
                            <th>% Presença</th>
                            <th>Responsável</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamadas as $c): 
                            $tot = $c->total_presentes + $c->total_ausentes + $c->total_justificados;
                            $pct = $tot > 0 ? round(($c->total_presentes / $tot) * 100, 1) : 0;
                        ?>
                            <tr>
                                <td><strong><?= formatDate($c->data) ?></strong></td>
                                <td><?= sanitize($c->turma_nome) ?></td>
                                <td><?= ucfirst(sanitize($c->turno)) ?></td>
                                <td><span class="badge badge-success"><?= $c->total_presentes ?></span></td>
                                <td><span class="badge badge-danger"><?= $c->total_ausentes ?></span></td>
                                <td><span class="badge badge-warning"><?= $c->total_justificados ?></span></td>
                                <td>
                                    <strong><?= $pct ?>%</strong>
                                </td>
                                <td><?= sanitize($c->usuario_nome) ?></td>
                                <td>
                                    <a href="<?= BASE_URL ?>/chamadas/visualizar/<?= $c->id ?>" class="btn btn-outline btn-sm">Ver Chamada</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
