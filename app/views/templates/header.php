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
    <link rel="icon" type="image/jpeg" href="<?= BASE_URL ?>/public/img/logo.jpg">
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    <meta name="csrf-token" content="<?= csrfToken() ?>">
</head>
<body>
<div class="app-layout">
    <!-- Barra Lateral (Sidebar) VIVALCRIS -->
    <aside class="app-sidebar">
        <div class="sidebar-header">
            <img src="<?= BASE_URL ?>/public/img/logo.jpg" alt="Vivalcris" class="sidebar-logo">
            <div>
                <div class="sidebar-title">VIVAL<span>CRIS</span></div>
                <div class="sidebar-subtitle">Frequência & Alimentação</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-nav-label">Visão Geral</div>
            <a href="<?= BASE_URL ?>/" class="sidebar-link <?= isActiveMenu('') ?>">
                <span class="sidebar-icon">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="3" width="7" height="7"></rect>
                        <rect x="14" y="3" width="7" height="7"></rect>
                        <rect x="14" y="14" width="7" height="7"></rect>
                        <rect x="3" y="14" width="7" height="7"></rect>
                    </svg>
                </span>
                <span>Painel Principal</span>
            </a>

            <?php if (in_array($perfilAtual, ['admin', 'gestor', 'inspetor'])): ?>
                <div class="sidebar-nav-label">Frequência & Inspetoria</div>
                <a href="<?= BASE_URL ?>/chamadas" class="sidebar-link <?= isActiveMenu('/chamadas') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                            <polyline points="9 13 11 15 15 11"></polyline>
                        </svg>
                    </span>
                    <span>Chamada Digital</span>
                </a>
                <a href="<?= BASE_URL ?>/inspetoria" class="sidebar-link <?= isActiveMenu('/inspetoria') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            <line x1="11" y1="8" x2="11" y2="14"></line>
                            <line x1="8" y1="11" x2="14" y2="11"></line>
                        </svg>
                    </span>
                    <span>Painel Inspetoria</span>
                </a>
                <a href="<?= BASE_URL ?>/turmas" class="sidebar-link <?= isActiveMenu('/turmas') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 21h18"></path>
                            <path d="M5 21V7l7-4 7 4v14"></path>
                            <path d="M9 10a2 2 0 1 0 0-4 2 2 0 0 0 0 4z"></path>
                            <path d="M9 21v-5a2 2 0 0 1 2-2h2a2 2 0 0 1 2 2v5"></path>
                        </svg>
                    </span>
                    <span>Turmas Escolares</span>
                </a>
                <a href="<?= BASE_URL ?>/alunos" class="sidebar-link <?= isActiveMenu('/alunos') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                            <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                        </svg>
                    </span>
                    <span>Alunos Matriculados</span>
                </a>
            <?php endif; ?>

            <?php if (in_array($perfilAtual, ['admin', 'gestor', 'cozinha'])): ?>
                <div class="sidebar-nav-label">Alimentação Escolar</div>
                <a href="<?= BASE_URL ?>/cozinha" class="sidebar-link <?= isActiveMenu('/cozinha') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                            <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                            <line x1="6" y1="1" x2="6" y2="4"></line>
                            <line x1="10" y1="1" x2="10" y2="4"></line>
                            <line x1="14" y1="1" x2="14" y2="4"></line>
                        </svg>
                    </span>
                    <span>Painel da Cozinha</span>
                </a>
                <a href="<?= BASE_URL ?>/cardapios" class="sidebar-link <?= isActiveMenu('/cardapios') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"></path>
                            <path d="M12 6v6l4 2"></path>
                        </svg>
                    </span>
                    <span>Cardápio Diário</span>
                </a>
            <?php endif; ?>

            <?php if (in_array($perfilAtual, ['admin', 'gestor'])): ?>
                <div class="sidebar-nav-label">Gestão & Auditoria</div>
                <a href="<?= BASE_URL ?>/relatorios" class="sidebar-link <?= isActiveMenu('/relatorios') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                            <line x1="2" y1="20" x2="22" y2="20"></line>
                        </svg>
                    </span>
                    <span>Relatórios</span>
                </a>
                <a href="<?= BASE_URL ?>/relatorios/auditoria" class="sidebar-link <?= isActiveMenu('/relatorios/auditoria') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                            <path d="M9 12l2 2 4-4"></path>
                        </svg>
                    </span>
                    <span>Trilha de Auditoria</span>
                </a>
            <?php endif; ?>

            <?php if ($perfilAtual === 'admin'): ?>
                <div class="sidebar-nav-label">Administração</div>
                <a href="<?= BASE_URL ?>/usuarios" class="sidebar-link <?= isActiveMenu('/usuarios') ?>">
                    <span class="sidebar-icon">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </span>
                    <span>Gestão de Usuários</span>
                </a>
            <?php endif; ?>
        </nav>
    </aside>

    <!-- Área Principal -->
    <div class="app-main">
        <!-- Barra Superior (Topbar) -->
        <header class="app-topbar">
            <div class="topbar-left">
                <button type="button" class="btn-sidebar-toggle" aria-label="Abrir menu">
                    <svg viewBox="0 0 24 24" width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </button>
                <div class="topbar-brand-tag">
                    <strong>VIVALCRIS</strong>
                    <span style="color: var(--border-dark);">|</span>
                    <span>Central de Frequência & Alimentação Escolar</span>
                </div>
            </div>

            <div class="topbar-right">
                <div class="user-badge">
                    <span><?= sanitize($usuarioAtual['nome'] ?? 'Operador') ?></span>
                    <span class="role-tag"><?= sanitize($perfilAtual) ?></span>
                </div>
                <a href="<?= BASE_URL ?>/auth/logout" class="btn btn-outline btn-sm">Encerrar Sessão</a>
            </div>
        </header>

        <!-- Conteúdo -->
        <main class="content-area">
            <!-- Mensagens Flash de Feedback -->
            <?= flash('mensagem') ?>
