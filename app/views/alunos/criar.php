<div class="page-header">
    <div>
        <h1 class="page-header-title">➕ Cadastrar Novo Aluno</h1>
        <p class="breadcrumb-info">Cadastre um estudante fictício e vincule à turma</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/alunos" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card" style="max-width: 700px;">
    <div class="card-header">
        <h3>Dados do Estudante</h3>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/alunos/criar" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="nome" class="form-label required">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Nome completo do aluno" value="<?= old('nome') ?>" required autofocus>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="matricula_codigo" class="form-label required">Código de Matrícula (Único)</label>
                    <input type="text" id="matricula_codigo" name="matricula_codigo" class="form-control" placeholder="Ex: 2026031" value="<?= old('matricula_codigo') ?>" required>
                </div>

                <div class="form-group">
                    <label for="data_nascimento" class="form-label required">Data de Nascimento</label>
                    <input type="date" id="data_nascimento" name="data_nascimento" class="form-control" value="<?= old('data_nascimento') ?>" required>
                </div>
            </div>

            <div class="form-group">
                <label for="turma_id" class="form-label required">Turma de Matrícula</label>
                <select id="turma_id" name="turma_id" class="form-control" required>
                    <option value="">Selecione a turma...</option>
                    <?php foreach ($turmas as $t): ?>
                        <option value="<?= $t->id ?>">Turma <?= sanitize($t->nome) ?> (<?= ucfirst(sanitize($t->turno)) ?> - <?= sanitize($t->serie) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="orientacao_operacional" class="form-label">
                    Restrição Alimentar (Orientação Operacional)
                </label>
                <textarea id="orientacao_operacional" name="orientacao_operacional" class="form-control" rows="2" 
                          placeholder="Ex: Sem glúten - substituir pão por fruta ou cuscuz. Sem lactose."></textarea>
                <div class="form-help">
                    🛡️ <strong>Regra de Privacidade:</strong> Insira APENAS a orientação operacional indispensável para o preparo dos alimentos pela Cozinha. Não registre laudos, diagnósticos médicos ou detalhes excessivos.
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Salvar Aluno</button>
                <a href="<?= BASE_URL ?>/alunos" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
