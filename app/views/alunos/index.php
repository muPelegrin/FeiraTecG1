<div class="page-header">
    <div>
        <h1 class="page-header-title">👨‍🎓 Alunos Cadastrados</h1>
        <p class="breadcrumb-info">Lista nominal de estudantes, código de matrícula e turma vinculada</p>
    </div>
    <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/alunos/criar" class="btn btn-primary">+ Novo Aluno</a>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Estudantes Registrados</h3>
    </div>
    <div class="card-body">
        <?php if (empty($alunos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">👨‍🎓</div>
                <div class="empty-state-title">Nenhum aluno cadastrado</div>
                <p>Cadastre os estudantes fictícios para realizar as chamadas e o cálculo de refeições.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Matrícula</th>
                            <th>Nome do Aluno</th>
                            <th>Data de Nascimento</th>
                            <th>Turma Atual</th>
                            <th>Turno</th>
                            <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                                <th>Ações</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($alunos as $a): ?>
                            <tr>
                                <td><code><?= sanitize($a->matricula_codigo) ?></code></td>
                                <td><strong><?= sanitize($a->nome) ?></strong></td>
                                <td><?= formatDate($a->data_nascimento) ?></td>
                                <td>
                                    <?php if (!empty($a->turma_nome)): ?>
                                        <span class="badge badge-info">Turma <?= sanitize($a->turma_nome) ?></span>
                                    <?php else: ?>
                                        <span class="badge badge-secondary">Sem turma</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= !empty($a->turno) ? ucfirst(sanitize($a->turno)) : '-' ?></td>
                                <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
                                            <a href="<?= BASE_URL ?>/alunos/editar/<?= $a->id ?>" class="btn btn-outline btn-sm">Editar</a>
                                            <?php if (Middleware::hasRole('admin')): ?>
                                                <form action="<?= BASE_URL ?>/alunos/excluir/<?= $a->id ?>" method="POST" onsubmit="return confirm('Deseja desativar o cadastro do aluno <?= sanitize($a->nome) ?>?');">
                                                    <button type="submit" class="btn btn-danger btn-sm">Desativar</button>
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
