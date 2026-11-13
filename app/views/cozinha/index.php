<div class="page-header">
    <div>
        <h1 class="page-header-title">👨‍🍳 Painel Consolidado da Cozinha</h1>
        <p class="breadcrumb-info">
            Sincronização em tempo real entre chamada digital e previsão operacional de refeições
        </p>
    </div>
    <div class="page-header-actions">
        <span style="font-size: 0.85rem; color: var(--text-muted); margin-right: 0.5rem;">
            Última atualização: <strong><?= $horarioAtualizacao ?></strong>
        </span>
        <button type="button" onclick="window.location.reload();" class="btn btn-primary btn-sm">🔄 Atualizar Painel</button>
        <button type="button" onclick="window.print();" class="btn btn-outline btn-sm">🖨️ Imprimir Mapa</button>
    </div>
</div>

<!-- Alerta de Turmas Pendentes (Regra Central da Missão) -->
<?php if (!$todasChamadasConcluidas): ?>
    <div class="alert-pending-banner">
        <div class="banner-icon">⚠️</div>
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
                A estimativa total de refeições inclui a projeção provisória destas turmas.
            </p>
        </div>
    </div>
<?php else: ?>
    <div class="alert alert-success" style="margin-bottom: 1.5rem;">
        <div>
            🎉 <strong>Fechamento Completo:</strong> Todas as chamadas escolares do dia foram concluídas pela Inspetoria! Os números de refeições abaixo representam a demanda real confirmada.
        </div>
    </div>
<?php endif; ?>

<!-- Indicadores de Refeições -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon success">🍽️</div>
        <div class="stat-data">
            <div class="stat-number"><?= $presentesConfirmados ?></div>
            <div class="stat-label">Presentes Confirmados (Chamadas Fechadas)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon warning">⏳</div>
        <div class="stat-data">
            <div class="stat-number"><?= $estimativaPendentes ?></div>
            <div class="stat-label">Projeção Provisória (Turmas Pendentes)</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon primary">🎯</div>
        <div class="stat-data">
            <div class="stat-number"><?= $previsaoTotalEstimada ?></div>
            <div class="stat-label">Estimativa Final Total de Refeições</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon danger">❌</div>
        <div class="stat-data">
            <div class="stat-number"><?= $ausentesConfirmados ?></div>
            <div class="stat-label">Faltas Confirmadas (Economia de Refeições)</div>
        </div>
    </div>
</div>

<div class="grid-2">
    <!-- Cardápio do Dia na Cozinha -->
    <div class="card">
        <div class="card-header">
            <h3>🍽️ Cardápio Programado para Hoje</h3>
            <a href="<?= BASE_URL ?>/cardapios" class="btn btn-outline btn-sm">Gerenciar</a>
        </div>
        <div class="card-body">
            <?php if (empty($cardapiosHoje)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🍽️</div>
                    <div class="empty-state-title">Sem cardápio cadastrado para hoje</div>
                    <p>Cadastre o cardápio para conferir ingredientes e orientações.</p>
                </div>
            <?php else: ?>
                <?php foreach ($cardapiosHoje as $c): ?>
                    <div style="border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1rem;">
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
            <h3>🏫 Status de Presença por Turma</h3>
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
                            <th>Status Chamada</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($chamadasHoje as $ch): ?>
                            <tr>
                                <td><strong><?= sanitize($ch->turma_nome) ?></strong></td>
                                <td><?= ucfirst(sanitize($ch->turno)) ?></td>
                                <td><span class="badge badge-success"><?= $ch->total_presentes ?> presentes</span></td>
                                <td><span class="badge badge-danger"><?= $ch->total_ausentes ?> ausentes</span></td>
                                <td>
                                    <?php if ($ch->status === 'concluida'): ?>
                                        <span class="badge badge-success">Confirmada</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Em Aberto</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                        <?php foreach ($turmasPendentes as $tp): ?>
                            <tr style="background-color: #fffbeb;">
                                <td><strong><?= sanitize($tp->nome) ?></strong></td>
                                <td><?= ucfirst(sanitize($tp->turno)) ?></td>
                                <td colspan="2" style="color: #92400e; font-style: italic;">
                                    Projeção: <?= $tp->total_alunos ?> alunos matriculados
                                </td>
                                <td><span class="badge badge-warning">Pendente</span></td>
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
    <div class="card-header" style="background-color: #eff6ff;">
        <h3>🥗 Orientações Operacionais de Dietas Especiais (Acesso Operacional Restrito)</h3>
        <span class="badge badge-info">Sem Diagnósticos Médicos</span>
    </div>
    <div class="card-body">
        <div style="font-size: 0.825rem; color: var(--text-muted); margin-bottom: 1rem;">
            🛡️ <strong>Privacidade do Estudante:</strong> A cozinha recebe estritamente as instruções operacionais de substituição e preparo culinário necessárias para a segurança alimentar dos estudantes, resguardando o sigilo de dados de saúde.
        </div>

        <?php if (empty($restricoes)): ?>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Nenhuma restrição operacional cadastrada no momento.</p>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 180px;">Aluno</th>
                            <th style="width: 120px;">Matrícula</th>
                            <th>Diretriz Operacional para o Preparo da Refeição</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($restricoes as $r): ?>
                            <tr>
                                <td><strong><?= sanitize($r->aluno_nome) ?></strong></td>
                                <td><code><?= sanitize($r->matricula_codigo) ?></code></td>
                                <td>
                                    <div style="background-color: #f0fdf4; border-left: 3px solid #16a34a; padding: 0.5rem 0.75rem; border-radius: 4px; font-weight: 500;">
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
