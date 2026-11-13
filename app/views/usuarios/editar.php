<div class="page-header">
    <div>
        <h1 class="page-header-title">✏️ Editar Usuário</h1>
        <p class="breadcrumb-info">Atualização cadastral de <?= sanitize($usuario->nome) ?></p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h3>Atualizar Dados</h3>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/usuarios/editar/<?= $usuario->id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="nome" class="form-label required">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= sanitize($usuario->nome) ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="email" class="form-label required">E-mail Institucional</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= sanitize($usuario->email) ?>" required>
            </div>

            <div class="form-group">
                <label for="senha" class="form-label">Nova Senha (deixe em branco para manter a atual)</label>
                <input type="password" id="senha" name="senha" class="form-control" placeholder="••••••••">
            </div>

            <div class="form-group">
                <label for="perfil" class="form-label required">Perfil de Acesso</label>
                <select id="perfil" name="perfil" class="form-control" required>
                    <option value="inspetor" <?= ($usuario->perfil === 'inspetor') ? 'selected' : '' ?>>Inspetor</option>
                    <option value="cozinha" <?= ($usuario->perfil === 'cozinha') ? 'selected' : '' ?>>Cozinha / Nutrição</option>
                    <option value="gestor" <?= ($usuario->perfil === 'gestor') ? 'selected' : '' ?>>Gestor / Direção</option>
                    <option value="admin" <?= ($usuario->perfil === 'admin') ? 'selected' : '' ?>>Administrador</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
