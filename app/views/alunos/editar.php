<div class="page-header">
    <div>
        <h1 class="page-header-title">✏️ Editar Aluno</h1>
        <p class="breadcrumb-info">Atualização cadastral do aluno <?= sanitize($aluno->nome) ?></p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/alunos" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3>Atualizar Informações</h3>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/alunos/editar/<?= $aluno->id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="nome" class="form-label required">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= sanitize($aluno->nome) ?>" required autofocus>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="matricula_codigo" class="form-label required">Código de Matrícula</label>
                    <input type="text" id="matricula_codigo" name="matricula_codigo" class="form-control" value="<?= sanitize($aluno->matricula_codigo) ?>" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento" class="form-label required">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="<?= $aluno->data_nascimento ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="turma_id" class="form-label required">Turma Atual</label>
                <select id="turma_id" name="turma_id" class="form-control" required>
                    <option value="">Selecione a turma...</option>
                    <?php foreach ($turmas as $t): ?>
                        <option value="<?= $t->id ?>" <?= ($t->id == $turmaAtualId) ? 'selected' : '' ?>>
                            Turma <?= sanitize($t->nome) ?> (<?= ucfirst(sanitize($t->turno)) ?> - <?= sanitize($t->serie) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="orientacao_operacional" class="form-label">
                    Restrição Alimentar (Orientação Operacional)
                </label>
                <textarea id="orientacao_operacional" name="orientacao_operacional" class="form-control" rows="2" 
                          placeholder="Ex: Sem glúten, sem lactose..."><?= sanitize($restricaoOperacional ?? '') ?></textarea>
                <div class="form-help">
                    🛡️ <strong>Regra de Privacidade:</strong> Apenas orientações indispensáveis para a cozinha. Não inclua diagnósticos médicos.
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="<?= BASE_URL ?>/alunos" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
