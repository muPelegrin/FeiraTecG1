<div class="page-header">
    <div>
        <h1 class="page-header-title">📈 Painel Gerencial & Relatórios</h1>
        <p class="breadcrumb-info">Consolidação estatística de frequência escolar e trilha de auditoria</p>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3>📊 Relatório de Frequência e Taxa de Ausência</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                Consulte o índice de presença e absenteísmo por intervalo de datas e por turma, com exportação para planilha CSV e impressão.
            </p>
            <form action="<?= BASE_URL ?>/relatorios/frequencia" method="GET">
                <div class="form-group">
                    <label for="data_inicio" class="form-label">Data Inicial</label>
                    <input type="date" id="data_inicio" name="data_inicio" class="form-control" value="<?= date('Y-m-01') ?>">
                </div>

                <div class="form-group">
                    <label for="data_fim" class="form-label">Data Final</label>
                    <input type="date" id="data_fim" name="data_fim" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group">
                    <label for="turma_id" class="form-label">Filtrar por Turma</label>
                    <select id="turma_id" name="turma_id" class="form-control">
                        <option value="">Todas as turmas</option>
                        <?php foreach ($turmas as $t): ?>
                            <option value="<?= $t->id ?>">Turma <?= sanitize($t->nome) ?> (<?= ucfirst(sanitize($t->turno)) ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%;">Gerar Relatório de Frequência</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>🛡️ Trilha de Auditoria e Integridade</h3>
        </div>
        <div class="card-body">
            <p style="color: var(--text-muted); margin-bottom: 1.25rem;">
                Acompanhe o log de auditoria de todas as alterações manuais e correções realizadas no sistema, incluindo autor, justificativa obrigatória, valor anterior e novo valor.
            </p>
            <div style="background-color: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1rem; margin-bottom: 1.5rem;">
                <div style="font-size: 0.9rem; font-weight: 600; margin-bottom: 0.35rem;">Conformidade e Segurança:</div>
                <ul style="font-size: 0.85rem; color: var(--text-muted); padding-left: 1.2rem;">
                    <li>Registro do operador autenticado</li>
                    <li>Timestamp com data e hora exatas</li>
                    <li>Rastreabilidade de justificativas</li>
                    <li>Imutabilidade dos dados históricos</li>
                </ul>
            </div>
            <a href="<?= BASE_URL ?>/relatorios/auditoria" class="btn btn-outline" style="width: 100%;">Ver Trilha de Auditoria</a>
        </div>
    </div>
</div>
