<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
            </span>
            <span>Relatório de Frequência e Absenteísmo</span>
        </h1>
        <p class="breadcrumb-info">
            Período: <strong><?= formatDate($dataInicio) ?></strong> até <strong><?= formatDate($dataFim) ?></strong>
        </p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/relatorios/frequencia?data_inicio=<?= $dataInicio ?>&data_fim=<?= $dataFim ?>&turma_id=<?= $turmaIdFiltro ?>&export=csv" class="btn btn-success btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                <polyline points="7 10 12 15 17 10"></polyline>
                <line x1="12" y1="15" x2="12" y2="3"></line>
            </svg>
            <span>Exportar CSV</span>
        </a>
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Imprimir</span>
        </button>
        <a href="<?= BASE_URL ?>/relatorios" class="btn btn-outline btn-sm">Voltar</a>
    </div>
</div>

<!-- Filtros de Pesquisa -->
<div class="card" style="margin-bottom: 1.6rem;">
    <div class="card-body">
        <form action="<?= BASE_URL ?>/relatorios/frequencia" method="GET" style="display: flex; gap: 1.1rem; flex-wrap: wrap; align-items: flex-end;">
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

            <button type="submit" class="btn btn-primary">Filtrar Dados</button>
        </form>
    </div>
</div>

<!-- Indicadores de Taxa de Presença e Ausência -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="20" x2="18" y2="10"></line>
                <line x1="12" y1="20" x2="12" y2="4"></line>
                <line x1="6" y1="20" x2="6" y2="14"></line>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $taxaPresenca ?>%</div>
            <div class="stat-label">Taxa Geral de Presença</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 18 13.5 8.5 8.5 13.5 1 6"></polyline>
                <polyline points="17 18 23 18 23 12"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $taxaAusencia ?>%</div>
            <div class="stat-label">Taxa Geral de Ausência</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalPresentes ?></div>
            <div class="stat-label">Total de Presenças</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
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
    <div class="card-body" style="padding: 0;">
        <?php if (empty($chamadas)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="20" x2="18" y2="10"></line>
                        <line x1="12" y1="20" x2="12" y2="4"></line>
                        <line x1="6" y1="20" x2="6" y2="14"></line>
                    </svg>
                </div>
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
                            <th style="text-align: right;">Ações</th>
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
                                <td style="text-align: right;">
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
