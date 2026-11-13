<div class="page-header">
    <div>
        <h1 class="page-header-title">📋 Detalhes da Chamada: Turma <?= sanitize($chamada->turma_nome) ?></h1>
        <p class="breadcrumb-info">
            Data: <?= formatDate($chamada->data) ?> • Turno <?= ucfirst(sanitize($chamada->turno)) ?> • Série: <?= sanitize($chamada->serie) ?>
        </p>
    </div>
    <div class="page-header-actions">
        <?php if ($chamada->status === 'aberta' && (Middleware::hasRole('admin') || Middleware::hasRole('inspetor'))): ?>
            <a href="<?= BASE_URL ?>/chamadas/registrar/<?= $chamada->id ?>" class="btn btn-warning">Continuar Registro</a>
        <?php endif; ?>
        <a href="<?= BASE_URL ?>/chamadas" class="btn btn-outline">Voltar às Chamadas</a>
        <button type="button" onclick="window.print()" class="btn btn-outline">🖨️ Imprimir</button>
    </div>
</div>

<!-- Resumo dos Indicadores da Chamada -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success">✅</div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamada->total_presentes ?></div>
            <div class="stat-label">Presentes Confirmados</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">❌</div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamada->total_ausentes ?></div>
            <div class="stat-label">Ausentes</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">📝</div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamada->total_justificados ?></div>
            <div class="stat-label">Faltas Justificadas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon <?= ($chamada->status === 'concluida') ? 'primary' : 'warning' ?>">
            <?= ($chamada->status === 'concluida') ? '🔒' : '⏳' ?>
        </div>
        <div class="stat-data">
            <div class="stat-number" style="font-size: 1.2rem; text-transform: uppercase;">
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
                        <th style="width: 100px;">Matrícula</th>
                        <th>Nome do Estudante</th>
                        <th>Situação Registrada</th>
                        <th>Observações</th>
                        <th>Registrado Por</th>
                        <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                            <th style="width: 120px;">Auditoria</th>
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
                                    <div style="margin-top: 0.35rem; font-size: 0.75rem; background-color: #fef3c7; border-left: 3px solid #f59e0b; padding: 0.25rem 0.5rem; border-radius: 4px;">
                                        <strong>Histórico de Correção:</strong>
                                        <?php foreach ($historicos[$f->id] as $h): ?>
                                            <div>
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
                                <td>
                                    <a href="<?= BASE_URL ?>/chamadas/corrigir/<?= $f->id ?>" class="btn btn-outline btn-sm" title="Corrigir com justificativa obrigatória">
                                        ✏️ Corrigir
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
