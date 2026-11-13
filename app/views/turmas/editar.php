<div class="page-header">
    <div>
        <h1 class="page-header-title">✏️ Editar Turma</h1>
        <p class="breadcrumb-info">Atualização dos dados da turma <?= sanitize($turma->nome) ?></p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/turmas" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h3>Atualizar Informações</h3>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/turmas/editar/<?= $turma->id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="nome" class="form-label required">Nome da Turma</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= sanitize($turma->nome) ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="serie" class="form-label required">Série / Grau Escolar</label>
                <input type="text" id="serie" name="serie" class="form-control" value="<?= sanitize($turma->serie) ?>" required>
            </div>

            <div class="form-group">
                <label for="turno" class="form-label required">Turno</label>
                <select id="turno" name="turno" class="form-control" required>
                    <option value="matutino" <?= ($turma->turno === 'matutino') ? 'selected' : '' ?>>Matutino (Manhã)</option>
                    <option value="vespertino" <?= ($turma->turno === 'vespertino') ? 'selected' : '' ?>>Vespertino (Tarde)</option>
                    <option value="noturno" <?= ($turma->turno === 'noturno') ? 'selected' : '' ?>>Noturno (Noite)</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="<?= BASE_URL ?>/turmas" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
