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
            <span>Registro de Chamada: Turma <?= sanitize($chamada->turma_nome) ?></span>
        </h1>
        <p class="breadcrumb-info">
            <?= sanitize($chamada->serie) ?> • Turno <?= ucfirst(sanitize($chamada->turno)) ?> • Data: <strong><?= formatDate($chamada->data) ?></strong>
        </p>
    </div>
    <div class="page-header-actions">
        <button type="button" id="btn-marcar-todos-presentes" class="btn btn-success btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>Marcar Todos Presentes</span>
        </button>
        <button type="button" id="btn-marcar-todos-ausentes" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
            <span>Marcar Todos Ausentes</span>
        </button>
        <a href="<?= BASE_URL ?>/chamadas" class="btn btn-outline btn-sm">Voltar</a>
    </div>
</div>

<form action="<?= BASE_URL ?>/chamadas/registrar/<?= $chamada->id ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

    <div class="card">
        <div class="card-header">
            <h3>Lista Nominal de Estudantes (<?= count($alunos) ?> matriculados)</h3>
            <span style="font-size: 0.85rem; color: var(--text-muted);">
                Abertura: <strong><?= formatDateTime($chamada->aberta_em) ?></strong> por <?= sanitize($chamada->usuario_nome) ?>
            </span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 90px;">Nº / Matr.</th>
                            <th>Nome do Aluno</th>
                            <th style="width: 320px;">Situação de Presença</th>
                            <th>Observações / Justificativa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $i = 1;
                        foreach ($alunos as $aluno): 
                            $freq = $frequenciaMap[$aluno->id] ?? null;
                            $sitAtual = $freq ? $freq->situacao : 'presente';
                            $obsAtual = $freq ? $freq->observacao : '';
                        ?>
                            <tr>
                                <td>
                                    <span style="color: var(--text-muted); font-size: 0.8rem;"><?= $i++ ?>º</span><br>
                                    <code><?= sanitize($aluno->matricula_codigo) ?></code>
                                </td>
                                <td>
                                    <strong><?= sanitize($aluno->nome) ?></strong>
                                </td>
                                <td>
                                    <div class="radio-attendance-group">
                                        <label class="radio-attendance-label">
                                            <input type="radio" name="situacao[<?= $aluno->id ?>]" value="presente" 
                                                   <?= ($sitAtual === 'presente') ? 'checked' : '' ?>>
                                            <span class="radio-presente">Presente</span>
                                        </label>

                                        <label class="radio-attendance-label">
                                            <input type="radio" name="situacao[<?= $aluno->id ?>]" value="ausente" 
                                                   <?= ($sitAtual === 'ausente') ? 'checked' : '' ?>>
                                            <span class="radio-ausente">Ausente</span>
                                        </label>

                                        <label class="radio-attendance-label">
                                            <input type="radio" name="situacao[<?= $aluno->id ?>]" value="justificado" 
                                                   <?= ($sitAtual === 'justificado') ? 'checked' : '' ?>>
                                            <span class="radio-justificado">Justificado</span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <input type="text" name="observacao[<?= $aluno->id ?>]" class="form-control" 
                                           style="padding: 0.4rem 0.75rem; font-size: 0.86rem;" 
                                           placeholder="Ex: Atestado médico, consulta, saída..." 
                                           value="<?= sanitize($obsAtual ?? '') ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer" style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
            <div>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    Ao <strong>Concluir Chamada</strong>, a previsão oficial da Cozinha é sincronizada imediatamente.
                </span>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" name="acao" value="salvar" class="btn btn-outline">Salvar como Rascunho</button>
                <button type="submit" name="acao" value="concluir" class="btn btn-primary btn-lg" 
                        onclick="return confirm('Deseja CONCLUIR esta chamada? A informação de presença será finalizada e sincronizada com a Cozinha.');">
                    <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                    <span>Concluir Chamada</span>
                </button>
            </div>
        </div>
    </div>
</form>
