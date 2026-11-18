<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                </svg>
            </span>
            <span>Detalhes da Chamada: Turma <?= sanitize($chamada->turma_nome) ?></span>
        </h1>
        <p class="breadcrumb-info">
            Data: <strong><?= formatDate($chamada->data) ?></strong> • Turno <?= ucfirst(sanitize($chamada->turno)) ?> • Série: <?= sanitize($chamada->serie) ?>
        </p>
    </div>
    <div class="page-header-actions">
        <?php if ($chamada->status === 'aberta' && (Middleware::hasRole('admin') || Middleware::hasRole('inspetor'))): ?>
            <a href="<?= BASE_URL ?>/chamadas/registrar/<?= $chamada->id ?>" class="btn btn-warning btn-sm">Continuar Registro</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/chamadas" class="btn btn-outline btn-sm">Voltar às Chamadas</a>
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Imprimir</span>
        </button>
    </div>
</div>

<!-- Resumo dos Indicadores da Chamada -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamada->total_presentes ?></div>
            <div class="stat-label">Presentes Confirmados</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamada->total_ausentes ?></div>
            <div class="stat-label">Ausentes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                <polyline points="14 2 14 8 20 8"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamada->total_justificados ?></div>
            <div class="stat-label">Faltas Justificadas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon <?= ($chamada->status === 'concluida') ? 'primary' : 'warning' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number" style="font-size: 1.25rem; text-transform: uppercase;">
                <?= sanitize($chamada->status) ?>
            </div>
            <div class="stat-label">
                Fechamento: <?= !empty($chamada->concluida_em) ? formatDateTime($chamada->concluida_em) : 'Em aberto' ?>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Lista Nominal e Situação Oficial</h3>
        <span style="font-size: 0.85rem; color: var(--text-muted);">
            Registrado por: <strong><?= sanitize($chamada->usuario_nome) ?></strong> em <?= formatDateTime($chamada->aberta_em) ?>
        </span>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 110px;">Matrícula</th>
                        <th>Nome do Estudante</th>
                        <th>Situação Registrada</th>
                        <th>Observações</th>
                        <th>Registrado Por</th>
                        <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                            <th style="width: 120px; text-align: right;">Auditoria</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($frequencias as $f): ?>
                        <tr>
                            <td><code><?= sanitize($f->matricula_codigo) ?></code></td>
                            <td>
                                <strong><?= sanitize($f->aluno_nome) ?></strong>
                                <?php if (!empty($historicos[$f->id])): ?>
                                    <div style="margin-top: 0.4rem; font-size: 0.76rem; background-color: var(--primary-light); border-left: 3px solid var(--primary); padding: 0.35rem 0.6rem; border-radius: 4px;">
                                        <strong style="color: var(--primary-dark);">Histórico de Correção:</strong>
                                        <?php foreach ($historicos[$f->id] as $h): ?>
                                            <div style="margin-top: 0.2rem;">
                                                • Alterado de <em><?= sanitize($h->valor_anterior) ?></em> para <em><?= sanitize($h->valor_novo) ?></em> 
                                                por <strong><?= sanitize($h->usuario_nome) ?></strong> em <?= formatDateTime($h->created_at) ?>.
                                                <br><em>Justificativa:</em> "<?= sanitize($h->justificativa) ?>"
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($f->situacao === 'presente'): ?>
                                    <span class="badge badge-success">Presente</span>
                                <?php elseif ($f->situacao === 'ausente'): ?>
                                    <span class="badge badge-danger">Ausente</span>
                                <?php elseif ($f->situacao === 'justificado'): ?>
                                    <span class="badge badge-warning">Justificado</span>
                                <?php endif; ?>
                            </td>
                            <td><?= !empty($f->observacao) ? sanitize($f->observacao) : '<span style="color: var(--text-light);">-</span>' ?></td>
                            <td><?= sanitize($f->registrado_por_nome) ?></td>
                            <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                                <td style="text-align: right;">
                                    <a href="<?= BASE_URL ?>/chamadas/corrigir/<?= $f->id ?>" class="btn btn-outline btn-sm" title="Corrigir com justificativa obrigatória">
                                        Corrigir
                                    </a>
                                </td>
                            <?php endif; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
