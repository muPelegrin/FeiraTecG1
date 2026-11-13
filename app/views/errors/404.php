<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Página Não Encontrada | <?= APP_NAME ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
</head>
<body style="display: flex; align-items: center; justify-content: center; min-height: 100vh;">
    <div class="card" style="max-width: 500px; text-align: center; padding: 2.5rem 2rem;">
        <div style="font-size: 3.5rem; margin-bottom: 0.5rem;">🔍</div>
        <h1 style="font-size: 1.75rem; margin-bottom: 0.5rem; color: var(--text-main);">404 - Página Não Encontrada</h1>
        <p style="color: var(--text-muted); margin-bottom: 1.5rem;">
            O endereço que você tentou acessar não existe ou foi movido.
        </p>
        <a href="<?= BASE_URL ?>/" class="btn btn-primary">Voltar ao Início</a>
    </div>
</body>
</html>
