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
            <span>Painel Gerencial & Relatórios</span>
        </h1>
        <p class="breadcrumb-info">Consolidação estatística de frequência escolar e trilha de auditoria</p>
    </div>
</div>

<div class="grid-2">
    <div class="card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                    <line x1="18" y1="20" x2="18" y2="10"></line>
                    <line x1="12" y1="20" x2="12" y2="4"></line>
                    <line x1="6" y1="20" x2="6" y2="14"></line>
                </svg>
                <span>Relatório de Frequência e Taxa de Ausência</span>
            </h3>
        </div>
        <div class="card-body">
            <p style="color: var(--text-muted); margin-bottom: 1.35rem; font-size: 0.92rem; line-height: 1.5;">
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

                <button type="submit" class="btn btn-primary" style="width: 100%; margin-top: 0.5rem;">Gerar Relatório de Frequência</button>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                </svg>
                <span>Trilha de Auditoria e Integridade</span>
            </h3>
        </div>
        <div class="card-body">
            <p style="color: var(--text-muted); margin-bottom: 1.35rem; font-size: 0.92rem; line-height: 1.5;">
                Acompanhe o log de auditoria de todas as alterações manuais e correções realizadas no sistema, incluindo operador, justificativa mandatória, valor anterior e novo valor.
            </p>
            <div style="background-color: #FAF7F2; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 1.1rem; margin-bottom: 1.5rem;">
                <div style="font-size: 0.88rem; font-weight: 700; margin-bottom: 0.4rem; color: var(--text-heading);">Conformidade e Segurança:</div>
                <ul style="font-size: 0.85rem; color: var(--text-muted); padding-left: 1.2rem; line-height: 1.6;">
                    <li>Registro do operador autenticado</li>
                    <li>Timestamp com data e hora exatas</li>
                    <li>Rastreabilidade de justificativas</li>
                    <li>Imutabilidade dos dados históricos</li>
                </ul>
            </div>
            <a href="<?= BASE_URL ?>/relatorios/auditoria" class="btn btn-outline" style="width: 100%;">Visualizar Trilha de Auditoria</a>
        </div>
    </div>
</div>
