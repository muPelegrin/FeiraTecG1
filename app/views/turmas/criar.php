<div class="page-header">
    <div>
        <h1 class="page-header-title">➕ Cadastrar Nova Turma</h1>
        <p class="breadcrumb-info">Cadastre uma nova classe escolar</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/turmas" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h3>Dados da Turma</h3>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/turmas/criar" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="nome" class="form-label required">Nome da Turma (Identificador)</label>
                <input type="text" id="nome" name="nome" class="form-control" placeholder="Ex: 6A, 7B, 1º EM A" required autofocus>
            </div>

            <div class="form-group">
                <label for="serie" class="form-label required">Série / Grau Escolar</label>
                <input type="text" id="serie" name="serie" class="form-control" placeholder="Ex: 6º Ano Fundamental" required>
            </div>

            <div class="form-group">
                <label for="turno" class="form-label required">Turno</label>
                <select id="turno" name="turno" class="form-control" required>
                    <option value="">Selecione o turno...</option>
                    <option value="matutino">Matutino (Manhã)</option>
                    <option value="vespertino">Vespertino (Tarde)</option>
                    <option value="noturno">Noturno (Noite)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Ano Letivo Vinculado</label>
                <input type="text" class="form-control" value="<?= sanitize($anoLetivo->ano ?? date('Y')) ?> - <?= sanitize($anoLetivo->descricao ?? 'Ano Corrente') ?>" readonly disabled>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Salvar Turma</button>
                <a href="<?= BASE_URL ?>/turmas" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
