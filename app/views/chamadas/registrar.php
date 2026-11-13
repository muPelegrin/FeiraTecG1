<div class="page-header">
    <div>
        <h1 class="page-header-title">📋 Registro de Chamada: Turma <?= sanitize($chamada->turma_nome) ?></h1>
        <p class="breadcrumb-info">
            <?= sanitize($chamada->serie) ?> • Turno <?= ucfirst(sanitize($chamada->turno)) ?> • Data: <?= formatDate($chamada->data) ?>
        </p>
    </div>
    <div class="page-header-actions">
        <button type="button" id="btn-marcar-todos-presentes" class="btn btn-success btn-sm">Marcar Todos Presentes</button>
        <button type="button" id="btn-marcar-todos-ausentes" class="btn btn-outline btn-sm">Marcar Todos Ausentes</button>
        <a href="<?= BASE_URL ?>/chamadas" class="btn btn-outline btn-sm">Voltar</a>
    </div>
</div>

<form action="<?= BASE_URL ?>/chamadas/registrar/<?= $chamada->id ?>" method="POST">
    <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

    <div class="card">
        <div class="card-header">
            <h3>Lista Nominal de Estudantes (<?= count($alunos) ?> matriculados)</h3>
            <span style="font-size: 0.85rem; color: var(--text-muted);">
                Abertura: <?= formatDateTime($chamada->aberta_em) ?> por <?= sanitize($chamada->usuario_nome) ?>
            </span>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 80px;">Nº / Matr.</th>
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
                                           style="padding: 0.35rem 0.65rem; font-size: 0.85rem;" 
                                           placeholder="Ex: Atestado médico, saída antecipada..." 
                                           value="<?= sanitize($obsAtual ?? '') ?>">
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer" style="padding: 1.25rem; background-color: #fafafa; border-top: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; flex-wrap: gap; gap: 1rem;">
            <div>
                <span style="font-size: 0.85rem; color: var(--text-muted);">
                    ⚠️ Ao <strong>Concluir Chamada</strong>, a previsão consolidada da Cozinha é atualizada com os números oficiais.
                </span>
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="submit" name="acao" value="salvar" class="btn btn-outline">Salvar como Rascunho</button>
                <button type="submit" name="acao" value="concluir" class="btn btn-primary btn-lg" 
                        onclick="return confirm('Deseja realmente CONCLUIR esta chamada? A informação de presença será finalizada e sincronizada com a Cozinha.');">
                    ✅ Concluir Chamada
                </button>
            </div>
        </div>
    </div>
</form>
