<div class="page-header">
    <div>
        <h1 class="page-header-title">🔍 Painel da Inspetoria Escolar</h1>
        <p class="breadcrumb-info">Monitoramento das chamadas em tempo real e controle de pendências</p>
    </div>
    <div class="page-header-actions">
        <span style="font-size: 0.85rem; color: var(--text-muted); margin-right: 0.5rem;">
            Atualização: <strong><?= $horarioAtualizacao ?></strong>
        </span>
        <button type="button" onclick="window.location.reload();" class="btn btn-outline btn-sm">🔄 Atualizar</button>
    </div>
</div>

<!-- Indicadores de Acompanhamento da Inspetoria -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">🏫</div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalTurmas ?></div>
            <div class="stat-label">Total de Turmas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">✅</div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalConcluidas ?></div>
            <div class="stat-label">Chamadas Concluídas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon <?= ($totalPendentes > 0) ? 'warning' : 'info' ?>">
            <?= ($totalPendentes > 0) ? '⏳' : '🎉' ?>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalPendentes ?></div>
            <div class="stat-label">Chamadas Pendentes</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Quadro Operacional de Turmas - <?= formatDate(date('Y-m-d')) ?></h3>
    </div>
    <div class="card-body">
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
                        <th>Ações da Inspetoria</th>
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
                            <td>
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
