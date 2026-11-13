<?php
$usuarioAtual = Middleware::currentUser();
$perfilAtual = $usuarioAtual['perfil'] ?? '';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($titulo) ? sanitize($titulo) . ' | ' : '' ?><?= APP_NAME ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= BASE_URL ?>/public/img/logo.svg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <meta name="csrf-token" content="<?= csrfToken() ?>">
</head>
<body>
<div class="app-layout">
    <!-- Barra Lateral (Sidebar) -->
    <aside class="app-sidebar">
        <div class="sidebar-header">
            <img src="<?= BASE_URL ?>/public/img/logo.svg" alt="Logo" class="sidebar-logo">
            <div>
                <div class="sidebar-title">Central Escolar</div>
                <div class="sidebar-subtitle">Frequência & Refeições</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-nav-label">Visão Geral</div>
            <a href="<?= BASE_URL ?>/" class="sidebar-link <?= isActiveMenu('') ?>">
                <span class="icon">📊</span>
                <span>Painel Principal</span>
            </a>

            <?php if (in_array($perfilAtual, ['admin', 'gestor', 'inspetor'])): ?>
                <div class="sidebar-nav-label">Frequência & Inspetoria</div>
                <a href="<?= BASE_URL ?>/chamadas" class="sidebar-link <?= isActiveMenu('/chamadas') ?>">
                    <span class="icon">📋</span>
                    <span>Chamada Digital</span>
                </a>
                <a href="<?= BASE_URL ?>/inspetoria" class="sidebar-link <?= isActiveMenu('/inspetoria') ?>">
                    <span class="icon">🔍</span>
                    <span>Painel Inspetoria</span>
                </a>
                <a href="<?= BASE_URL ?>/turmas" class="sidebar-link <?= isActiveMenu('/turmas') ?>">
                    <span class="icon">🏫</span>
                    <span>Turmas</span>
                </a>
                <a href="<?= BASE_URL ?>/alunos" class="sidebar-link <?= isActiveMenu('/alunos') ?>">
                    <span class="icon">👨‍🎓</span>
                    <span>Alunos</span>
                </a>
            <?php endif; ?>

            <?php if (in_array($perfilAtual, ['admin', 'gestor', 'cozinha'])): ?>
                <div class="sidebar-nav-label">Alimentação Escolar</div>
                <a href="<?= BASE_URL ?>/cozinha" class="sidebar-link <?= isActiveMenu('/cozinha') ?>">
                    <span class="icon">👨‍🍳</span>
                    <span>Painel da Cozinha</span>
                </a>
                <a href="<?= BASE_URL ?>/cardapios" class="sidebar-link <?= isActiveMenu('/cardapios') ?>">
                    <span class="icon">🍽️</span>
                    <span>Cardápio Diário</span>
                </a>
            <?php endif; ?>

            <?php if (in_array($perfilAtual, ['admin', 'gestor'])): ?>
                <div class="sidebar-nav-label">Gestão & Auditoria</div>
                <a href="<?= BASE_URL ?>/relatorios" class="sidebar-link <?= isActiveMenu('/relatorios') ?>">
                    <span class="icon">📈</span>
                    <span>Relatórios</span>
                </a>
                <a href="<?= BASE_URL ?>/relatorios/auditoria" class="sidebar-link <?= isActiveMenu('/relatorios/auditoria') ?>">
                    <span class="icon">🛡️</span>
                    <span>Auditoria</span>
                </a>
            <?php endif; ?>

            <?php if ($perfilAtual === 'admin'): ?>
                <div class="sidebar-nav-label">Administração</div>
                <a href="<?= BASE_URL ?>/usuarios" class="sidebar-link <?= isActiveMenu('/usuarios') ?>">
                    <span class="icon">👤</span>
                    <span>Usuários</span>
                </a>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- Área Principal -->
    <div class="app-main">
        <!-- Barra Superior (Topbar) -->
        <header class="app-topbar">
            <div class="topbar-left">
                <button type="button" class="btn-sidebar-toggle" aria-label="Abrir menu">☰</button>
                <div class="breadcrumb-info">
                    <strong><?= APP_NAME ?></strong> • Mostra de Tecnologia 2026
                </div>
            </div>

            <div class="topbar-right">
                <div class="user-badge">
                    <span>👤 <strong><?= sanitize($usuarioAtual['nome'] ?? 'Usuário') ?></strong></span>
                    <span class="role-tag"><?= sanitize($perfilAtual) ?></span>
                </div>
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-outline btn-sm">Sair</a>
            </div>
        </header>

        <!-- Conteúdo -->
        <main class="content-area">
            <!-- Mensagens Flash de Feedback -->
            <?= flash('mensagem') ?>
