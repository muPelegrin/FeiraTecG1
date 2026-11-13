<div class="page-header">
    <div>
        <h1 class="page-header-title">🛡️ Trilha de Auditoria do Sistema</h1>
        <p class="breadcrumb-info">Registro imutável de alterações manuais, correções de frequência e alterações de cardápio</p>
    </div>
    <div class="page-header-actions">
        <button type="button" onclick="window.print()" class="btn btn-outline btn-sm">🖨️ Imprimir Trilha</button>
        <a href="<?= BASE_URL ?>/relatorios" class="btn btn-outline btn-sm">Voltar</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Histórico de Alterações Auditadas (Mais Recentes)</h3>
    </div>
    <div class="card-body">
        <?php if (empty($historicos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🛡️</div>
                <div class="empty-state-title">Nenhuma alteração auditada registrada ainda</div>
                <p>Todas as correções de frequência e edições críticas de cardápio ficarão registradas aqui.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 140px;">Data / Horário</th>
                            <th style="width: 110px;">Entidade / Tabela</th>
                            <th style="width: 90px;">Registro</th>
                            <th style="width: 100px;">Campo</th>
                            <th>Valor Anterior</th>
                            <th>Valor Novo</th>
                            <th style="width: 140px;">Autor</th>
                            <th>Justificativa Obrigatória</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historicos as $h): ?>
                            <tr>
                                <td><small><?= formatDateTime($h->created_at) ?></small></td>
                                <td><code><?= sanitize($h->tabela) ?></code></td>
                                <td>#<?= $h->registro_id ?></td>
                                <td><strong><?= sanitize($h->campo) ?></strong></td>
                                <td>
                                    <span style="color: var(--danger); background-color: var(--danger-light); padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.825rem;">
                                        <?= sanitize($h->valor_anterior ?? '(vazio)') ?>
                                    </span>
                                </td>
                                <td>
                                    <span style="color: var(--success); background-color: var(--success-light); padding: 0.15rem 0.4rem; border-radius: 4px; font-size: 0.825rem;">
                                        <?= sanitize($h->valor_novo ?? '(vazio)') ?>
                                    </span>
                                </td>
                                <td><strong><?= sanitize($h->usuario_nome) ?></strong></td>
                                <td>
                                    <div style="font-size: 0.85rem; font-style: italic; color: var(--text-main);">
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
