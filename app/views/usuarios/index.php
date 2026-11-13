<div class="page-header">
    <div>
        <h1 class="page-header-title">👤 Gestão de Usuários e Perfis</h1>
        <p class="breadcrumb-info">Controle de operadores e permissões de acesso ao sistema</p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/usuarios/criar" class="btn btn-primary">+ Novo Usuário</a>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Operadores Cadastrados</h3>
    </div>
    <div class="card-body">
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nome Completo</th>
                        <th>E-mail de Login</th>
                        <th>Perfil de Acesso</th>
                        <th>Status</th>
                        <th>Cadastrado Em</th>
                        <th>Ações</th>
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
                            <td><small><?= formatDateTime($u->created_at) ?></small></td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
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
