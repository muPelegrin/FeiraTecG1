<div class="page-header">
    <div>
        <h1 class="page-header-title">📊 Painel Principal</h1>
        <p class="breadcrumb-info">Visão consolidada do dia: <?= formatDate(date('Y-m-d')) ?></p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/chamadas" class="btn btn-primary">Fazer Chamada</a>
        <a href="<?= BASE_URL ?>/cozinha" class="btn btn-outline">Ver Painel da Cozinha</a>
    </div>
</div>

<!-- Indicadores Rápidos (Stats Grid) -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon primary">👨‍🎓</div>
        <div class="stat-data">
            <div class="stat-number"><?= $totalAlunos ?? 0 ?></div>
            <div class="stat-label">Alunos Ativos</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon success">✅</div>
        <div class="stat-data">
            <div class="stat-number"><?= $presentesHoje ?? 0 ?></div>
            <div class="stat-label">Presentes Confirmados Hoje</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">❌</div>
        <div class="stat-data">
            <div class="stat-number"><?= $ausentesHoje ?? 0 ?></div>
            <div class="stat-label">Ausentes / Justificados</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon <?= ($chamadasPendentes > 0) ? 'warning' : 'info' ?>">
            <?= ($chamadasPendentes > 0) ? '⚠️' : '🎉' ?>
        </div>
        <div class="stat-data">
            <div class="stat-number"><?= $chamadasPendentes ?? 0 ?></div>
            <div class="stat-label">Turmas Pendentes de Chamada</div>
        </div>
    </div>
</div>

<!-- Alerta de Turmas Pendentes (se houver) -->
<?php if (!empty($turmasPendentes)): ?>
    <div class="alert-pending-banner">
        <div class="banner-icon">⚠️</div>
        <div class="banner-text">
            <h4>Atenção: Informação de Presença Incompleta para a Cozinha!</h4>
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
            <h3>📋 Chamadas de Hoje</h3>
            <a href="<?= BASE_URL ?>/chamadas" class="btn btn-outline btn-sm">Ver Todas</a>
        </div>
        <div class="card-body">
            <?php if (empty($chamadasHoje)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📋</div>
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
                                <th>Ações</th>
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
                                            <span class="badge badge-warning">Aberta</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
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
            <h3>🍽️ Cardápio e Previsão de Hoje</h3>
            <a href="<?= BASE_URL ?>/cardapios" class="btn btn-outline btn-sm">Ver Cardápios</a>
        </div>
        <div class="card-body">
            <?php if (empty($cardapiosHoje)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🍽️</div>
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
                                <th>Previsão Planejada</th>
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
                                    <td>
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
