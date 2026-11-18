<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    <path d="M9 12l2 2 4-4"></path>
                </svg>
            </span>
            <span>Trilha de Auditoria do Sistema</span>
        </h1>
        <p class="breadcrumb-info">Registro imutável de alterações manuais, correções de frequência e alterações de cardápio</p>
    </div>
    <div class="page-header-actions">
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Imprimir Trilha</span>
        </button>
        <a href="<?= BASE_URL ?>/relatorios" class="btn btn-outline btn-sm">Voltar</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Histórico de Alterações Auditadas (Mais Recentes)</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($historicos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                    </svg>
                </div>
                <div class="empty-state-title">Nenhuma alteração auditada registrada ainda</div>
                <p>Todas as correções de frequência e edições críticas de cardápio ficarão registradas aqui.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 150px;">Data / Horário</th>
                            <th style="width: 120px;">Entidade / Tabela</th>
                            <th style="width: 90px;">Registro</th>
                            <th style="width: 110px;">Campo</th>
                            <th>Valor Anterior</th>
                            <th>Valor Novo</th>
                            <th style="width: 140px;">Autor</th>
                            <th>Justificativa Obrigatória</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historicos as $h): ?>
                            <tr>
                                <td><small style="color: var(--text-muted);"><?= formatDateTime($h->created_at) ?></small></td>
                                <td><code><?= sanitize($h->tabela) ?></code></td>
                                <td>#<?= $h->registro_id ?></td>
                                <td><strong><?= sanitize($h->campo) ?></strong></td>
                                <td>
                                    <span style="color: var(--danger-dark); background-color: var(--danger-light); padding: 0.2rem 0.45rem; border-radius: 4px; font-size: 0.825rem; font-weight: 600;">
                                        <?= sanitize($h->valor_anterior ?? '(vazio)') ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color: var(--success-dark); background-color: var(--success-light); padding: 0.2rem 0.45rem; border-radius: 4px; font-size: 0.825rem; font-weight: 600;">
                                        <?= sanitize($h->valor_novo ?? '(vazio)') ?>
                                    </span>
                                </td>
                                <td><strong><?= sanitize($h->usuario_nome) ?></strong></td>
                                <td>
                                    <div style="font-size: 0.86rem; font-style: italic; color: var(--text-main);">
                                        "<?= sanitize($h->justificativa) ?>"
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
