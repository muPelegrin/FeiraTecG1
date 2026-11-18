<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="3" width="7" height="7"></rect>
                    <rect x="14" y="3" width="7" height="7"></rect>
                    <rect x="14" y="14" width="7" height="7"></rect>
                    <rect x="3" y="14" width="7" height="7"></rect>
                </svg>
            </span>
            <span>Painel de Controle</span>
        </h1>
        <p class="breadcrumb-info">Consolidação operacional do dia: <strong><?= formatDate(date('Y-m-d')) ?></strong></p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/chamadas" class="btn btn-primary">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                <polyline points="9 13 11 15 15 11"></polyline>
            </svg>
            <span>Realizar Chamada</span>
        </a>
        <a href="<?= BASE_URL ?>/cozinha" class="btn btn-outline">
            <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
            </svg>
            <span>Painel da Cozinha</span>
        </a>
    </div>
</div>

<!-- Indicadores Principais (Stats Grid) -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 10v6M2 10l10-5 10 5-10 5z"></path>
                <path d="M6 12v5c0 2 2 3 6 3s6-1 6-3v-5"></path>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalAlunos ?? 0 ?></div>
            <div class="stat-label">Alunos Ativos Matriculados</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                <polyline points="22 4 12 14.01 9 11.01"></polyline>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $presentesHoje ?? 0 ?></div>
            <div class="stat-label">Presentes Confirmados Hoje</div>
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
            <div class="stat-number"><?= $ausentesHoje ?? 0 ?></div>
            <div class="stat-label">Ausências / Faltas Registradas</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon <?= ($chamadasPendentes > 0) ? 'warning' : 'info' ?>">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"></circle>
                <line x1="12" y1="8" x2="12" y2="12"></line>
                <line x1="12" y1="16" x2="12.01" y2="16"></line>
            </svg>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamadasPendentes ?? 0 ?></div>
            <div class="stat-label">Turmas Pendentes de Fechamento</div>
        </div>
    </div>
</div>

<!-- Alerta de Turmas Pendentes (se houver) -->
<?php if (!empty($turmasPendentes)): ?>
    <div class="alert-pending-banner">
        <div class="banner-icon">
            <svg viewBox="0 0 24 24" width="26" height="26" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                <line x1="12" y1="9" x2="12" y2="13"></line>
                <line x1="12" y1="17" x2="12.01" y2="17"></line>
            </svg>
        </div>
        <div class="banner-text">
            <h4>Alerta Operacional: Informação de Presença Incompleta para a Cozinha</h4>
            <p>
                Existem <strong><?= count($turmasPendentes) ?> turma(s)</strong> ainda sem chamada concluída hoje:
                <?php 
                $nomesPendentes = array_map(function($t) { return $t->nome . ' (' . ucfirst($t->turno) . ')'; }, $turmasPendentes);
                echo implode(', ', $nomesPendentes);
                ?>.
                A previsão de refeições é calculada como estimativa para essas turmas até a conclusão das chamadas.
            </p>
        </div>
    </div>
<?php endif; ?>

<div class="grid-2">
    <!-- Card: Chamadas do Dia -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                </svg>
                <span>Chamadas de Hoje</span>
            </h3>
            <a href="<?= BASE_URL ?>/chamadas" class="btn btn-outline btn-sm">Ver Todas</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($chamadasHoje)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                        </svg>
                    </div>
                    <div class="empty-state-title">Nenhuma chamada iniciada hoje</div>
                    <p>Inicie a chamada digital para alimentar a previsão da cozinha.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Turma</th>
                                <th>Turno</th>
                                <th>Presentes</th>
                                <th>Status</th>
                                <th style="text-align: right;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($chamadasHoje as $ch): ?>
                                <tr>
                                    <td><strong><?= sanitize($ch->turma_nome) ?></strong></td>
                                    <td><?= ucfirst(sanitize($ch->turno)) ?></td>
                                    <td>
                                        <span class="badge badge-success"><?= $ch->total_presentes ?> presentes</span>
                                        <span class="badge badge-danger"><?= $ch->total_ausentes ?> ausentes</span>
                                    </td>
                                    <td>
                                        <?php if ($ch->status === 'concluida'): ?>
                                            <span class="badge badge-success">Concluída</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Em Aberto</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="text-align: right;">
                                        <?php if ($ch->status === 'concluida'): ?>
                                            <a href="<?= BASE_URL ?>/chamadas/visualizar/<?= $ch->id ?>" class="btn btn-outline btn-sm">Detalhes</a>
                                        <?php else: ?>
                                            <a href="<?= BASE_URL ?>/chamadas/registrar/<?= $ch->id ?>" class="btn btn-primary btn-sm">Continuar</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Card: Cardápios do Dia -->
    <div class="card">
        <div class="card-header">
            <h3>
                <svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--primary);">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                </svg>
                <span>Cardápio & Previsão do Dia</span>
            </h3>
            <a href="<?= BASE_URL ?>/cardapios" class="btn btn-outline btn-sm">Ver Cardápios</a>
        </div>
        <div class="card-body" style="padding: 0;">
            <?php if (empty($cardapiosHoje)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8 8 8 0 0 1-8 8z"></path>
                        </svg>
                    </div>
                    <div class="empty-state-title">Nenhum cardápio cadastrado para hoje</div>
                    <p>Cadastre os pratos e itens para o planejamento das refeições.</p>
                </div>
            <?php else: ?>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Refeição</th>
                                <th>Descrição do Cardápio</th>
                                <th style="text-align: right;">Previsão</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cardapiosHoje as $card): ?>
                                <tr>
                                    <td>
                                        <strong>
                                            <?php
                                            echo match($card->tipo_refeicao) {
                                                'cafe_manha'   => 'Café da Manhã',
                                                'almoco'       => 'Almoço',
                                                'lanche_tarde' => 'Lanche da Tarde',
                                                'jantar'       => 'Jantar',
                                                default        => $card->tipo_refeicao
                                            };
                                            ?>
                                        </strong>
                                    </td>
                                    <td><?= sanitize($card->descricao) ?></td>
                                    <td style="text-align: right;">
                                        <span class="badge badge-info"><?= $card->previsao_quantidade ?? 'Automática' ?> porções</span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
