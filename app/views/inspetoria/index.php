<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <span>Painel da Inspetoria Escolar</span>
        </h1>
        <p class="breadcrumb-info">Monitoramento das chamadas em tempo real e controle de pendências</p>
    </div>
    <div class="page-header-actions">
        <span style="font-size: 0.85rem; color: var(--text-muted); margin-right: 0.5rem;">
            Atualização: <strong><?= $horarioAtualizacao ?></strong>
        </span>
        <button type="button" onclick="window.location.reload();" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            <span>Atualizar</span>
        </button>
    </div>
</div>

<!-- Indicadores de Acompanhamento da Inspetoria -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 21h18"></path>
                <path d="M5 21V7l7-4 7 4v14"></path>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalTurmas ?></div>
            <div class="stat-label">Total de Turmas Cadastradas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalConcluidas ?></div>
            <div class="stat-label">Chamadas Concluídas Hoje</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon <?= ($totalPendentes > 0) ? 'warning' : 'info' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalPendentes ?></div>
            <div class="stat-label">Chamadas Pendentes de Fechamento</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Quadro Operacional de Turmas - <?= formatDate(date('Y-m-d')) ?></h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Turno</th>
                        <th>Série</th>
                        <th>Alunos Matriculados</th>
                        <th>Status da Chamada</th>
                        <th>Abertura</th>
                        <th>Conclusão</th>
                        <th style="text-align: right;">Ações da Inspetoria</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turmasComStatus as $t): ?>
                        <tr>
                            <td><strong><?= sanitize($t->nome) ?></strong></td>
                            <td><span class="badge badge-info"><?= ucfirst(sanitize($t->turno)) ?></span></td>
                            <td><?= sanitize($t->serie) ?></td>
                            <td><strong><?= $t->total_alunos ?></strong> alunos</td>
                            <td>
                                <?php if (empty($t->chamada_id)): ?>
                                    <span class="badge badge-warning">Não Iniciada</span>
                                <?php elseif ($t->chamada_status === 'aberta'): ?>
                                    <span class="badge badge-info">Em Aberto</span>
                                <?php elseif ($t->chamada_status === 'concluida'): ?>
                                    <span class="badge badge-success">Concluída</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($t->aberta_em) ? formatDateTime($t->aberta_em) : '-' ?></td>
                            <td><?= !empty($t->concluida_em) ? formatDateTime($t->concluida_em) : '-' ?></td>
                            <td style="text-align: right;">
                                <?php if (empty($t->chamada_id)): ?>
                                    <a href="<?= BASE_URL ?>/chamadas/abrir/<?= $t->id ?>" class="btn btn-primary btn-sm">Abrir Chamada</a>
                                <?php elseif ($t->chamada_status === 'aberta'): ?>
                                    <a href="<?= BASE_URL ?>/chamadas/registrar/<?= $t->chamada_id ?>" class="btn btn-warning btn-sm">Continuar</a>
                                <?php elseif ($t->chamada_status === 'concluida'): ?>
                                    <a href="<?= BASE_URL ?>/chamadas/visualizar/<?= $t->chamada_id ?>" class="btn btn-outline btn-sm">Visualizar</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
