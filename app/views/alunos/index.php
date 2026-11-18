<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                    <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                </svg>
            </span>
            <span>Alunos Matriculados</span>
        </h1>
        <p class="breadcrumb-info">Lista nominal de estudantes, código de matrícula e turma vinculada</p>
    </div>
    <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/alunos/criar" class="btn btn-primary">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Novo Aluno</span>
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <h3>Estudantes Registrados</h3>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($alunos)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                        <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
                    </svg>
                </div>
                <div class="empty-state-title">Nenhum aluno cadastrado</div>
                <p>Cadastre os estudantes fictícios para realizar as chamadas e o cálculo de refeições.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 120px;">Matrícula</th>
                            <th>Nome do Aluno</th>
                            <th>Data de Nascimento</th>
                            <th>Turma Atual</th>
                            <th>Turno</th>
                            <?php if (Middleware::hasRole('admin') || Middleware::hasRole('gestor')): ?>
                                <th style="text-align: right;">Ações</th>
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
                                    <td style="text-align: right;">
                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
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
