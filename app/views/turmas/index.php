<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 21h18"></path>
                    <path d="M5 21V7l7-4 7 4v14"></path>
                </svg>
            </span>
            <span>Turmas Escolares</span>
        </h1>
        <p class="breadcrumb-info">Gestão das turmas, turnos e séries vinculadas ao ano letivo</p>
    </div>
    <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/turmas/criar" class="btn btn-primary">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Nova Turma</span>
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Lista de Turmas Cadastradas</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($turmas)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 21h18"></path>
                        <path d="M5 21V7l7-4 7 4v14"></path>
                    </svg>
                </div>
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
                                <th style="text-align: right;">Ações</th>
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
                                    <td style="text-align: right;">
                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
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
