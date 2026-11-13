<div class="page-header">
    <div>
        <h1 class="page-header-title">🏫 Turmas Escolares</h1>
        <p class="breadcrumb-info">Gestão das turmas, turnos e séries vinculadas ao ano letivo</p>
    </div>
    <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/turmas/criar" class="btn btn-primary">+ Nova Turma</a>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Lista de Turmas Cadastradas</h3>
    </div>
    <div class="card-body">
        <?php if (empty($turmas)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🏫</div>
                <div class="empty-state-title">Nenhuma turma cadastrada</div>
                <p>Cadastre as turmas do ano letivo para organizar os alunos e as chamadas.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nome da Turma</th>
                            <th>Série</th>
                            <th>Turno</th>
                            <th>Ano Letivo</th>
                            <th>Alunos Matriculados</th>
                            <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                                <th>Ações</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($turmas as $t): ?>
                            <tr>
                                <td><strong><?= sanitize($t->nome) ?></strong></td>
                                <td><?= sanitize($t->serie) ?></td>
                                <td><span class="badge badge-info"><?= ucfirst(sanitize($t->turno)) ?></span></td>
                                <td><?= sanitize($t->ano) ?></td>
                                <td><strong><?= $t->total_alunos ?></strong> alunos ativos</td>
                                <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="<?= BASE_URL ?>/turmas/editar/<?= $t->id ?>" class="btn btn-outline btn-sm">Editar</a>
                                            <?php if (Middleware::hasRole('admin')): ?>
                                                <form action="<?= BASE_URL ?>/turmas/excluir/<?= $t->id ?>" method="POST" onsubmit="return confirm('Deseja realmente excluir a turma <?= sanitize($t->nome) ?>?');">
                                                    <button type="submit" class="btn btn-danger btn-sm">Excluir</button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
