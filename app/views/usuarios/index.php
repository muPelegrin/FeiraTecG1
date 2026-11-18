<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                    <circle cx="9" cy="7" r="4"></circle>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                </svg>
            </span>
            <span>Gestão de Usuários e Perfis</span>
        </h1>
        <p class="breadcrumb-info">Controle de operadores e permissões de acesso ao sistema</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/usuarios/criar" class="btn btn-primary">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            <span>Novo Usuário</span>
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Operadores Cadastrados</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome Completo</th>
                        <th>E-mail de Login</th>
                        <th>Perfil de Acesso</th>
                        <th>Status</th>
                        <th>Cadastrado Em</th>
                        <th style="text-align: right;">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <tr>
                            <td><strong><?= sanitize($u->nome) ?></strong></td>
                            <td><?= sanitize($u->email) ?></td>
                            <td>
                                <?php
                                $badgeClass = match($u->perfil) {
                                    'admin'    => 'badge-danger',
                                    'inspetor' => 'badge-info',
                                    'cozinha'  => 'badge-warning',
                                    'gestor'   => 'badge-success',
                                    default    => 'badge-secondary'
                                };
                                ?>
                                <span class="badge <?= $badgeClass ?>"><?= strtoupper(sanitize($u->perfil)) ?></span>
                            </td>
                            <td>
                                <?php if ($u->ativo): ?>
                                    <span class="badge badge-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td><small style="color: var(--text-muted);"><?= formatDateTime($u->created_at) ?></small></td>
                            <td style="text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <a href="<?= BASE_URL ?>/usuarios/editar/<?= $u->id ?>" class="btn btn-outline btn-sm">Editar</a>
                                    <?php if ($u->id != Middleware::currentUserId() && $u->ativo): ?>
                                        <form action="<?= BASE_URL ?>/usuarios/excluir/<?= $u->id ?>" method="POST" onsubmit="return confirm('Desativar o usuário <?= sanitize($u->nome) ?>?');">
                                            <button type="submit" class="btn btn-danger btn-sm">Desativar</button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
