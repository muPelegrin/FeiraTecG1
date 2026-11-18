<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <polyline points="9 13 11 15 15 11"></polyline>
                </svg>
            </span>
            <span>Chamada Digital Escolar</span>
        </h1>
        <p class="breadcrumb-info">Registro diário de frequência nominal por turma e data</p>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Quadro das Turmas Hoje (<?= formatDate(date('Y-m-d')) ?>)</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Turma</th>
                        <th>Série</th>
                        <th>Turno</th>
                        <th>Total Alunos</th>
                        <th>Status da Chamada</th>
                        <th>Horários</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($turmasComStatus as $t): ?>
                        <tr>
                            <td><strong><?= sanitize($t->nome) ?></strong></td>
                            <td><?= sanitize($t->serie) ?></td>
                            <td><span class="badge badge-info"><?= ucfirst(sanitize($t->turno)) ?></span></td>
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
                            <td>
                                <?php if (!empty($t->aberta_em)): ?>
                                    <small style="color: var(--text-muted);">
                                        Início: <?= formatDateTime($t->aberta_em) ?>
                                        <?php if (!empty($t->concluida_em)): ?>
                                            <br>Fim: <?= formatDateTime($t->concluida_em) ?>
                                        <?php endif; ?>
                                    </small>
                                <?php else: ?>
                                    <small style="color: var(--text-light);">-</small>
                                <?php endif; ?>
                            </td>
                            <td style="text-align: right;">
                                <?php if (empty($t->chamada_id)): ?>
                                    <?php if (Middleware::hasRole('admin') || Middleware::hasRole('inspetor')): ?>
                                        <a href="<?= BASE_URL ?>/chamadas/abrir/<?= $t->id ?>" class="btn btn-primary btn-sm">Abrir Chamada</a>
                                    <?php else: ?>
                                        <span style="font-size: 0.8rem; color: var(--text-muted);">Aguardando Inspetor</span>
                                    <?php endif; ?>
                                <?php elseif ($t->chamada_status === 'aberta'): ?>
                                    <a href="<?= BASE_URL ?>/chamadas/registrar/<?= $t->chamada_id ?>" class="btn btn-warning btn-sm">Continuar Registro</a>
                                <?php elseif ($t->chamada_status === 'concluida'): ?>
                                    <a href="<?= BASE_URL ?>/chamadas/visualizar/<?= $t->chamada_id ?>" class="btn btn-outline btn-sm">Ver Detalhes</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Histórico de Chamadas Registradas Hoje</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($chamadas)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                        <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    </svg>
                </div>
                <div class="empty-state-title">Nenhuma chamada iniciada ainda hoje</div>
                <p>Abra a chamada de uma das turmas acima para iniciar o fluxo.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Turno</th>
                            <th>Presentes</th>
                            <th>Ausentes</th>
                            <th>Justificados</th>
                            <th>Responsável</th>
                            <th>Situação</th>
                            <th style="text-align: right;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamadas as $ch): ?>
                            <tr>
                                <td><strong><?= sanitize($ch->turma_nome) ?></strong></td>
                                <td><?= ucfirst(sanitize($ch->turno)) ?></td>
                                <td><span class="badge badge-success"><?= $ch->total_presentes ?></span></td>
                                <td><span class="badge badge-danger"><?= $ch->total_ausentes ?></span></td>
                                <td><span class="badge badge-warning"><?= $ch->total_justificados ?></span></td>
                                <td><?= sanitize($ch->usuario_nome) ?></td>
                                <td>
                                    <?php if ($ch->status === 'concluida'): ?>
                                        <span class="badge badge-success">Concluída</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Em Registro</span>
                                    <?php endif; ?>
                                </td>
                                <td style="text-align: right;">
                                    <a href="<?= BASE_URL ?>/chamadas/visualizar/<?= $ch->id ?>" class="btn btn-outline btn-sm">Visualizar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
