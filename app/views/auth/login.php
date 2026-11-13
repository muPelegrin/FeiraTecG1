<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acesso ao Sistema | <?= APP_NAME ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/public/img/logo.svg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/login.css">
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="<?= BASE_URL ?>/public/img/logo.svg" alt="Logo" class="login-logo">
            <h1 class="login-title"><?= APP_NAME ?></h1>
            <p class="login-subtitle">Gestão Integrada de Frequência & Previsão de Alimentação</p>
        </div>

        <?= flash('mensagem') ?>

        <form action="<?= BASE_URL ?>/auth/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-group">
                <label for="email" class="form-label">E-mail Institucional</label>
                <input type="email" id="email" name="email" class="form-control" 
                       value="<?= old('email') ?>" required autofocus 
                       placeholder="seu.email@escola.com">
            </div>

            <div class="form-group">
                <label for="senha" class="form-label">Senha de Acesso</label>
                <input type="password" id="senha" name="senha" class="form-control" 
                       required placeholder="••••••••">
            </div>

            <button type="submit" class="btn-login">Entrar na Plataforma</button>
        </form>

        <div class="login-demo-accounts">
            <strong>Demonstração da Mostra (Clique para preencher):</strong>
            <div class="demo-list">
                <div class="demo-item" data-email="maria@escola.com">
                    <span class="demo-role">Maria (Inspetora)</span>
                    <span class="demo-email">maria@escola.com</span>
                </div>
                <div class="demo-item" data-email="joao@escola.com">
                    <span class="demo-role">João (Cozinha)</span>
                    <span class="demo-email">joao@escola.com</span>
                </div>
                <div class="demo-item" data-email="ana@escola.com">
                    <span class="demo-role">Ana (Gestora)</span>
                    <span class="demo-email">ana@escola.com</span>
                </div>
                <div class="demo-item" data-email="admin@escola.com">
                    <span class="demo-role">Admin Geral</span>
                    <span class="demo-email">admin@escola.com</span>
                </div>
            </div>
            <div style="margin-top: 0.5rem; text-align: center; font-size: 0.72rem; color: #64748b;">
                Senha padrão para todos: <code>senha123</code>
            </div>
        </div>

        <div class="login-footer">
            Mostra de Tecnologia 2026 • Grupo 01 • PHP 8+ MVC & MySQL
        </div>
    </div>

    <script src="<?= BASE_URL ?>/public/js/app.js"></script>
</body>
</html>
