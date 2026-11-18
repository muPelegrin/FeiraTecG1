<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página Não Encontrada | <?= APP_NAME ?></title>
    <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/public/img/logo.jpg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh; background-color: var(--bg-app);">
    <div class="card" style="max-width: 480px; text-align: center; padding: 3rem 2.2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-lg);">
        <div style="width: 72px; height: 72px; margin: 0 auto 1.5rem; background: var(--primary-light); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary);">
            <svg viewBox="0 0 24 24" width="36" height="36" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </div>
        <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.5rem; color: var(--text-heading);">404 - Página Não Encontrada</h1>
        <p style="color: var(--text-muted); margin-bottom: 1.75rem; font-size: 0.95rem; line-height: 1.5;">
            O endereço que você tentou acessar não foi localizado ou foi remanejado.
        </p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary btn-lg" style="width: 100%;">Voltar ao Painel Principal</a>
    </div>
</body>
</html>
