<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                </svg>
            </span>
            <span>Painel Consolidado da Cozinha</span>
        </h1>
        <p class="breadcrumb-info">
            Sincronização em tempo real entre chamada digital e previsão de refeições
        </p>
    </div>
    <div class="page-header-actions">
        <span style="font-size: 0.85rem; color: var(--text-muted); margin-right: 0.5rem;">
            Última atualização: <strong><?= $horarioAtualizacao ?></strong>
        </span>
        <button type="button" onclick="window.location.reload();" class="btn btn-primary btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="23 4 23 10 17 10"></polyline>
                <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
            </svg>
            <span>Atualizar Painel</span>
        </button>
        <button type="button" onclick="window.print();" class="btn btn-outline btn-sm">
            <svg viewBox="0 0 24 24" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            <span>Imprimir Mapa</span>
        </button>
    </div>
</div>

<!-- Alerta de Turmas Pendentes (Regra Central da Missão) -->
<?php if (!$todasChamadasConcluidas): ?>
    <div class="alert-pending-banner">
        <div class="banner-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <div class="banner-text">
            <h4>Alerta Operacional: Fechamento de Previsão Incompleto!</h4>
            <p>
                Existem <strong><?= count($turmasPendentes) ?> turma(s) pendente(s)</strong> de registro de chamada hoje:
                <strong>
                    <?php 
                    $nomesP = array_map(function($t) { return $t->nome . ' (' . ucfirst($t->turno) . ' - ' . $t->total_alunos . ' alunos)'; }, $turmasPendentes);
                    echo implode(', ', $nomesP);
                    ?>
                </strong>.
                A estimativa total de refeições inclui a projeção provisória destas turmas até o fechamento oficial.
            </p>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 0.65rem;">
            <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
            <span><strong>Fechamento Completo:</strong> Todas as chamadas escolares do dia foram concluídas pela Inspetoria! Os números de refeições abaixo representam a demanda real confirmada.</span>
        </div>
    </div>
<?php endif; ?>

<!-- Indicadores de Refeições -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $presentesConfirmados ?></div>
            <div class="stat-label">Presentes Confirmados (Chamadas Fechadas)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <polyline points="12 6 12 12 16 14"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $estimativaPendentes ?></div>
            <div class="stat-label">Projeção Provisória (Turmas Pendentes)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <circle cx="12" cy="12" r="6"></circle>
                <circle cx="12" cy="12" r="2"></circle>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $previsaoTotalEstimada ?></div>
            <div class="stat-label">Estimativa Final Total de Refeições</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="15" y1="9" x2="9" y2="15"></line>
                <line x1="9" y1="9" x2="15" y2="15"></line>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $ausentesConfirmados ?></div>
            <div class="stat-label">Faltas Confirmadas (Economia de Insumos)</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Cardápio do Dia na Cozinha -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                </svg>
                <span>Cardápio Programado para Hoje</span>
            </h3>
            <a href="<?= BASE_URL ?>/cardapios" class="btn btn-outline btn-sm">Gerenciar</a>
        </div>
        <div class="card-body">
            <?php if (empty($cardapiosHoje)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"></path>
                        </svg>
                    </div>
                    <div class="empty-state-title">Sem cardápio cadastrado para hoje</div>
                    <p>Cadastre o cardápio para conferir ingredientes e orientações.</p>
                </div>
            <?php else: ?>
                <?php foreach ($cardapiosHoje as $c): ?>
                    <div style="border-bottom: 1px solid var(--border); padding-bottom: 1.1rem; margin-bottom: 1.1rem;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.35rem;">
                            <strong style="color: var(--primary); font-size: 1.05rem;">
                                <?php
                                echo match($c->tipo_refeicao) {
                                    'cafe_manha'   => 'Café da Manhã',
                                    'almoco'       => 'Almoço',
                                    'lanche_tarde' => 'Lanche da Tarde',
                                    'jantar'       => 'Jantar',
                                    default        => $c->tipo_refeicao
                                };
                                ?>
                            </strong>
                            <span class="badge badge-info">Meta: <?= $c->previsao_quantidade ?? $previsaoTotalEstimada ?> porções</span>
                        </div>
                        <p style="font-size: 0.95rem; color: var(--text-main);"><?= sanitize($c->descricao) ?></p>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- Situação das Turmas para Alimentação -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                    <path d="M3 21h18"></path>
                    <path d="M5 21V7l7-4 7 4v14"></path>
                </svg>
                <span>Status de Presença por Turma</span>
            </h3>
        </div>
        <div class="card-body" style="padding: 0;">
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Turma</th>
                            <th>Turno</th>
                            <th>Presentes</th>
                            <th>Ausentes</th>
                            <th style="text-align: right;">Status Chamada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamadasHoje as $ch): ?>
                            <tr>
                                <td><strong><?= sanitize($ch->turma_nome) ?></strong></td>
                                <td><?= ucfirst(sanitize($ch->turno)) ?></td>
                                <td><span class="badge badge-success"><?= $ch->total_presentes ?> presentes</span></td>
                                <td><span class="badge badge-danger"><?= $ch->total_ausentes ?> ausentes</span></td>
                                <td style="text-align: right;">
                                    <?php if ($ch->status === 'concluida'): ?>
                                        <span class="badge badge-success">Confirmada</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Em Aberto</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($turmasPendentes as $tp): ?>
                            <tr style="background-color: #FFFDF9;">
                                <td><strong><?= sanitize($tp->nome) ?></strong></td>
                                <td><?= ucfirst(sanitize($tp->turno)) ?></td>
                                <td colspan="2" style="color: #9A3412; font-style: italic;">
                                    Projeção: <?= $tp->total_alunos ?> alunos matriculados
                                </td>
                                <td style="text-align: right;"><span class="badge badge-warning">Pendente</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Orientações Operacionais de Restrição Alimentar (Acesso Restrito & Protegido) -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header" style="background-color: #FFFDF9;">
        <h3>
            <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            </svg>
            <span>Orientações Operacionais de Dietas Especiais (Acesso Operacional Restrito)</span>
        </h3>
        <span class="badge badge-info">Sem Diagnósticos Médicos</span>
    </div>
    <div class="card-body">
        <div style="font-size: 0.84rem; color: var(--text-muted); margin-bottom: 1.1rem; line-height: 1.45;">
            <strong>Privacidade do Estudante:</strong> A cozinha recebe estritamente as instruções operacionais de substituição e preparo culinário necessárias para a segurança alimentar dos estudantes, resguardando o sigilo de dados de saúde.
        </div>

        <?php if (empty($restricoes)): ?>
            <p style="color: var(--text-muted); font-size: 0.92rem;">Nenhuma restrição operacional cadastrada no momento.</p>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 200px;">Aluno</th>
                            <th style="width: 140px;">Matrícula</th>
                            <th>Diretriz Operacional para o Preparo da Refeição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($restricoes as $r): ?>
                            <tr>
                                <td><strong><?= sanitize($r->aluno_nome) ?></strong></td>
                                <td><code><?= sanitize($r->matricula_codigo) ?></code></td>
                                <td>
                                    <div style="background-color: #F0FDF4; border-left: 3.5px solid var(--success); padding: 0.6rem 0.85rem; border-radius: 4px; font-weight: 500; color: #166534;">
                                        <?= sanitize($r->orientacao_operacional) ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
