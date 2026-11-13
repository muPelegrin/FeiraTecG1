<div class="page-header">
    <div>
        <h1 class="page-header-title">🍽️ Planejamento de Cardápios</h1>
        <p class="breadcrumb-info">Cardápios diários, tipos de refeição e previsão de porções</p>
    </div>
    <?php if (Middleware::hasRole('admin') || Middleware::hasRole('cozinha')): ?>
        <div class="page-header-actions">
            <a href="<?= BASE_URL ?>/cardapios/criar" class="btn btn-primary">+ Novo Cardápio</a>
        </div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">
        <form method="GET" action="<?= BASE_URL ?>/cardapios" style="display: flex; gap: 0.75rem; align-items: center; width: 100%;">
            <label for="data" style="font-weight: 600; font-size: 0.9rem;">Filtrar por Data:</label>
            <input type="date" id="data" name="data" class="form-control" style="width: auto;" value="<?= $dataFiltro ?>">
            <button type="submit" class="btn btn-outline btn-sm">Buscar</button>
            <a href="<?= BASE_URL ?>/cardapios?data=<?= date('Y-m-d') ?>" class="btn btn-outline btn-sm">Hoje</a>
        </form>
    </div>
    <div class="card-body">
        <?php if (empty($cardapios)): ?>
            <div class="empty-state">
                <div class="empty-state-icon">🍽️</div>
                <div class="empty-state-title">Nenhum cardápio cadastrado para <?= formatDate($dataFiltro) ?></div>
                <p>Cadastre o cardápio das refeições para planejar a quantidade necessária.</p>
            </div>
        <?php else: ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th style="width: 140px;">Refeição</th>
                            <th>Prato Principal / Composição</th>
                            <th style="width: 150px;">Previsão Quantidade</th>
                            <th>Ingredientes / Itens</th>
                            <?php if (Middleware::hasRole('admin') || Middleware::hasRole('cozinha')): ?>
                                <th style="width: 120px;">Ações</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cardapios as $c): ?>
                            <tr>
                                <td>
                                    <strong>
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
                                        <ul style="padding-left: 1rem; margin: 0; font-size: 0.85rem;">
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
                                    <td>
                                        <div style="display: flex; gap: 0.5rem;">
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
