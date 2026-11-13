<div class="page-header">
    <div>
        <h1 class="page-header-title">➕ Cadastrar Novo Usuário</h1>
        <p class="breadcrumb-info">Crie um operador e defina seu perfil de acesso</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card" style="max-width: 600px;">
    <div class="card-header">
        <h3>Dados de Acesso</h3>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/usuarios/criar" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="nome" class="form-label required">Nome Completo</label>
                <input type="text" id="nome" name="nome" class="form-control" value="<?= old('nome') ?>" required autofocus>
            </div>

            <div class="form-group">
                <label for="email" class="form-label required">E-mail Institucional</label>
                <input type="email" id="email" name="email" class="form-control" value="<?= old('email') ?>" required>
            </div>

            <div class="form-group">
                <label for="senha" class="form-label required">Senha Provisória</label>
                <input type="password" id="senha" name="senha" class="form-control" required placeholder="Mínimo 6 caracteres">
            </div>

            <div class="form-group">
                <label for="perfil" class="form-label required">Perfil de Acesso</label>
                <select id="perfil" name="perfil" class="form-control" required>
                    <option value="">Selecione o perfil...</option>
                    <option value="inspetor">Inspetor (Abre e registra chamadas diárias)</option>
                    <option value="cozinha">Cozinha / Nutrição (Consulta previsão e cardápios)</option>
                    <option value="gestor">Gestor / Direção (Relatórios, correções e supervisão)</option>
                    <option value="admin">Administrador (Acesso total)</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary">Cadastrar Usuário</button>
                <a href="<?= BASE_URL ?>/usuarios" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
