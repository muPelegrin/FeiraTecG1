<div class="page-header">
    <div>
        <h1 class="page-header-title">
            <span class="header-icon">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                    <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                </svg>
            </span>
            <span>Planejamento de Cardápios</span>
        </h1>
        <p class="breadcrumb-info">Cardápios diários, tipos de refeição e previsão de porções</p>
    </div>
    <?php if (Middleware::hasRole('admin') || Middleware::hasRole('cozinha')): ?>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/cardapios/criar" class="btn btn-primary">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>
                </svg>
                <span>Novo Cardápio</span>
            </a>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="<?= BASE_URL ?>/cardapios" style="display: flex; gap: 0.85rem; align-items: center; width: 100%; flex-wrap: wrap;">
            <label for="data" style="font-weight: 700; font-size: 0.9rem; color: var(--text-heading);">Filtrar por Data:</label>
            <input type="date" id="data" name="data" class="form-control" style="width: auto;" value="<?= $dataFiltro ?>">
            <button type="submit" class="btn btn-outline btn-sm">Buscar</button>
            <a href="<?= BASE_URL ?>/cardapios?data=<?= date('Y-m-d') ?>" class="btn btn-outline btn-sm">Hoje</a>
        </form>
    </div>
    <div class="card-body" style="padding: 0;">
        <?php if (empty($cardapios)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg viewBox="0 0 24 24" width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                    </svg>
                </div>
                <div class="empty-state-title">Nenhum cardápio cadastrado para <?= formatDate($dataFiltro) ?></div>
                <p>Cadastre o cardápio das refeições para planejar a quantidade necessária.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 150px;">Refeição</th>
                            <th>Prato Principal / Composição</th>
                            <th style="width: 160px;">Previsão Quantidade</th>
                            <th>Ingredientes / Itens</th>
                            <?php if (Middleware::hasRole('admin') || Middleware::hasRole('cozinha')): ?>
                                <th style="width: 130px; text-align: right;">Ações</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cardapios as $c): ?>
                            <tr>
                                <td>
                                    <strong style="color: var(--primary);">
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
                                </td>
                                <td><?= sanitize($c->descricao) ?></td>
                                <td>
                                    <span class="badge badge-info"><?= $c->previsao_quantidade ?? 'Automática' ?> porções</span>
                                </td>
                                <td>
                                    <?php 
                                    $itens = $itensMap[$c->id] ?? [];
                                    if (empty($itens)): 
                                    ?>
                                        <span style="color: var(--text-light); font-size: 0.8rem;">Sem itens detalhados</span>
                                    <?php else: ?>
                                        <ul style="padding-left: 1.1rem; margin: 0; font-size: 0.85rem; line-height: 1.5;">
                                            <?php foreach ($itens as $it): ?>
                                                <li>
                                                    <strong><?= sanitize($it->nome_item) ?></strong>
                                                    <?php if (!empty($it->quantidade_prevista)): ?>
                                                        - <?= $it->quantidade_prevista ?> <?= sanitize($it->observacao ?? '') ?>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    <?php endif; ?>
                                </td>
                                <?php if (Middleware::hasRole('admin') || Middleware::hasRole('cozinha')): ?>
                                    <td style="text-align: right;">
                                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                            <a href="<?= BASE_URL ?>/cardapios/editar/<?= $c->id ?>" class="btn btn-outline btn-sm">Editar</a>
                                            <?php if (Middleware::hasRole('admin')): ?>
                                                <form action="<?= BASE_URL ?>/cardapios/excluir/<?= $c->id ?>" method="POST" onsubmit="return confirm('Excluir este cardápio?');">
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
