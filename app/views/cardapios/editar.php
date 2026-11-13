<div class="page-header">
    <div>
        <h1 class="page-header-title">✏️ Editar Cardápio</h1>
        <p class="breadcrumb-info">
            Data: <?= formatDate($cardapio->data) ?> • Refeição: 
            <?php
            echo match($cardapio->tipo_refeicao) {
                'cafe_manha'   => 'Café da Manhã',
                'almoco'       => 'Almoço',
                'lanche_tarde' => 'Lanche da Tarde',
                'jantar'       => 'Jantar',
                default        => $cardapio->tipo_refeicao
            };
            ?>
        </p>
    </div>
    <div class="page-header-actions">
        <a href="<?= BASE_URL ?>/cardapios?data=<?= $cardapio->data ?>" class="btn btn-outline">Voltar</a>
    </div>
</div>

<div class="card" style="max-width: 800px;">
    <div class="card-header">
        <h3>Atualizar Informações e Itens</h3>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>/cardapios/editar/<?= $cardapio->id ?>" method="POST">
            <input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Data</label>
                    <input type="text" class="form-control" value="<?= formatDate($cardapio->data) ?>" readonly disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Tipo de Refeição</label>
                    <input type="text" class="form-control" value="<?= ucfirst($cardapio->tipo_refeicao) ?>" readonly disabled>
                </div>

                <div class="form-group">
                    <label for="previsao_quantidade" class="form-label">Previsão de Porções</label>
                    <input type="number" id="previsao_quantidade" name="previsao_quantidade" class="form-control" 
                           value="<?= $cardapio->previsao_quantidade ?>" min="1">
                </div>
            </div>

            <div class="form-group">
                <label for="descricao" class="form-label required">Descrição do Cardápio</label>
                <textarea id="descricao" name="descricao" class="form-control" rows="3" required><?= sanitize($cardapio->descricao) ?></textarea>
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border); margin: 1.5rem 0;">

            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                <h4 style="font-size: 1rem; color: var(--text-main);">Itens e Ingredientes</h4>
                <button type="button" id="btn-add-item-cardapio" class="btn btn-outline btn-sm">+ Adicionar Ingrediente/Item</button>
            </div>

            <div id="itens-cardapio-container">
                <?php if (empty($itens)): ?>
                    <div class="form-row item-cardapio-row" style="margin-bottom: 0.75rem;">
                        <div style="flex: 2;">
                            <input type="text" name="item_nome[]" class="form-control" placeholder="Nome do item" required>
                        </div>
                        <div style="flex: 1;">
                            <input type="number" name="item_quantidade[]" class="form-control" placeholder="Qtd" min="0">
                        </div>
                        <div style="flex: 2;">
                            <input type="text" name="item_observacao[]" class="form-control" placeholder="Unidade/Obs">
                        </div>
                        <div style="display: flex; align-items: center;">
                            <button type="button" class="btn btn-danger btn-sm btn-remover-item">&times;</button>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($itens as $it): ?>
                        <div class="form-row item-cardapio-row" style="margin-bottom: 0.75rem;">
                            <div style="flex: 2;">
                                <input type="text" name="item_nome[]" class="form-control" value="<?= sanitize($it->nome_item) ?>" required>
                            </div>
                            <div style="flex: 1;">
                                <input type="number" name="item_quantidade[]" class="form-control" value="<?= $it->quantidade_prevista ?>" min="0">
                            </div>
                            <div style="flex: 2;">
                                <input type="text" name="item_observacao[]" class="form-control" value="<?= sanitize($it->observacao ?? '') ?>">
                            </div>
                            <div style="display: flex; align-items: center;">
                                <button type="button" class="btn btn-danger btn-sm btn-remover-item">&times;</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div style="display: flex; gap: 0.75rem; margin-top: 2rem;">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="<?= BASE_URL ?>/cardapios?data=<?= $cardapio->data ?>" class="btn btn-outline">Cancelar</a>
            </div>
        </form>
    </div>
</div>
