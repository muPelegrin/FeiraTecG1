<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
            </span>
            <span>Correção Autorizada de Frequência</span>
        </h1>
        <p class="breadcrumb-info">
            Turma: <?= sanitize($chamada->turma_nome) ?> • Data: <strong><?= formatDate($chamada->data) ?></strong>
        </p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/chamadas/visualizar/<?= $chamada->id ?>" class="btn btn-outline btn-sm">Voltar à Chamada</a>
    </div>
</div>

<div class="card" style="max-width: 650px;">
    <div class="card-header">
        <h3>Alteração com Trilha de Auditoria Obrigatória</h3>
    </div>
    <div class="card-body">
        <div style="background-color: #FAF7F2; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1.1rem; margin-bottom: 1.35rem;">
            <div><strong>Aluno:</strong> <?= sanitize($aluno->nome) ?> (Matrícula: <code><?= sanitize($aluno->matricula_codigo) ?></code>)</div>
            <div style="margin-top: 0.35rem;">
                <strong>Situação Atual:</strong> 
                <?php if ($frequencia->situacao === 'presente'): ?>
                    <span class="badge badge-success">Presente</span>
                <?php elseif ($frequencia->situacao === 'ausente'): ?>
                    <span class="badge badge-danger">Ausente</span>
                <?php elseif ($frequencia->situacao === 'justificado'): ?>
                    <span class="badge badge-warning">Justificado</span>
                <?php endif; ?>
            </div>
            <?php if (!empty($frequencia->observacao)): ?>
                <div style="margin-top: 0.35rem; font-size: 0.85rem; color: var(--text-muted);">
                    <strong>Observação anterior:</strong> <?= sanitize($frequencia->observacao) ?>
                </div>
            <?php endif; ?>
        </div>

        <form action="<?= BASE_URL ?>/chamadas/corrigir/<?= $frequencia->id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="situacao" class="form-label required">Nova Situação de Presença</label>
                <select id="situacao" name="situacao" class="form-control" required>
                    <option value="presente" <?= ($frequencia->situacao === 'presente') ? 'selected' : '' ?>>Presente</option>
                    <option value="ausente" <?= ($frequencia->situacao === 'ausente') ? 'selected' : '' ?>>Ausente</option>
                    <option value="justificado" <?= ($frequencia->situacao === 'justificado') ? 'selected' : '' ?>>Justificado</option>
                </select>
            </div>

            <div class="form-group">
                <label for="observacao" class="form-label">Nova Observação (Opcional)</label>
                <input type="text" id="observacao" name="observacao" class="form-control" 
                       value="<?= sanitize($frequencia->observacao ?? '') ?>" 
                       placeholder="Ex: Atestado entregue na coordenação após o fechamento">
            </div>

            <div class="form-group">
                <label for="justificativa" class="form-label required">Justificativa da Correção (Obrigatória por Regra de Negócio)</label>
                <textarea id="justificativa" name="justificativa" class="form-control" rows="3" required 
                          placeholder="Informe detalhadamente o motivo da correção após o fechamento da chamada..."><?= old('justificativa') ?></textarea>
                <div class="form-help">
                    <strong>Regra de Auditoria:</strong> Esta alteração será gravada permanentemente na tabela <code>historico_alteracao</code> com seu nome de usuário (<?= sanitize($_SESSION['usuario_nome'] ?? '') ?>), data, horário, valor anterior e valor novo.
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-warning">Salvar Correção e Gravar Auditoria</button>
                <a href="<?= BASE_URL ?>/chamadas/visualizar/<?= $chamada->id ?>" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
