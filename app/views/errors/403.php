<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Acesso Negado | <?= APP_NAME ?></title>
    <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/public/img/logo.jpg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: var(--bg-app);">
    <div class="card" style="max-width: 480px; text-align: center; padding: 3rem 2.2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        <div style="width: 72px; height: 72px; margin: 0 auto 1.5rem; background: var(--danger-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--danger);">
            <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="4.93" y1="4.93" x2="19.07" y2="19.07"></line>
            </svg>
        </div>
        <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--danger-dark);">403 - Acesso Não Autorizado</h1>
        <p style="color: var(--text-muted); margin-bottom: 1.75rem; font-size: 0.95rem; line-height: 1.5;">
            Seu perfil de usuário não possui as permissões necessárias para acessar este recurso restrito.
        </p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-lg" style="width: 100%;">Voltar ao Painel Principal</a>
    </div>
</body>
</html>
