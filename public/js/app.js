/**
 * Central de Frequência e Alimentação Escolar
 * JavaScript Puro (Vanilla JS) - Sem frameworks externos
 * Mostra de Tecnologia 2026 - Grupo 01
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Toggle da Barra Lateral em Dispositivos Móveis
    const sidebarToggleBtn = document.querySelector('.btn-sidebar-toggle');
    const sidebar = document.querySelector('.app-sidebar');

    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });

        // Fecha ao clicar fora em telas pequenas
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (!sidebar.contains(e.target) && !sidebarToggleBtn.contains(e.target) && sidebar.classList.contains('show')) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }

    // 2. Fechamento automático de alertas após 6 segundos
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 6000);
    });

    // 3. Ações Rápidas de Chamada Nominal (Marcar Todos Presentes / Ausentes)
    const btnTodosPresentes = document.getElementById('btn-marcar-todos-presentes');
    if (btnTodosPresentes) {
        btnTodosPresentes.addEventListener('click', () => {
            const radioPresentes = document.querySelectorAll('input[type="radio"][value="presente"]');
            radioPresentes.forEach(radio => radio.checked = true);
        });
    }

    const btnTodosAusentes = document.getElementById('btn-marcar-todos-ausentes');
    if (btnTodosAusentes) {
        btnTodosAusentes.addEventListener('click', () => {
            const radioAusentes = document.querySelectorAll('input[type="radio"][value="ausente"]');
            radioAusentes.forEach(radio => radio.checked = true);
        });
    }

    // 4. Adição e Remoção Dinâmica de Itens no Formulário de Cardápio
    const btnAddItemCardapio = document.getElementById('btn-add-item-cardapio');
    const containerItens = document.getElementById('itens-cardapio-container');

    if (btnAddItemCardapio && containerItens) {
        btnAddItemCardapio.addEventListener('click', () => {
            const itemRow = document.createElement('div');
            itemRow.className = 'form-row item-cardapio-row';
            itemRow.style.marginBottom = '0.75rem';
            itemRow.innerHTML = `
                <div style="flex: 2;">
                    <input type="text" name="item_nome[]" class="form-control" placeholder="Nome do item (ex: Arroz, Frango...)" required>
                </div>
                <div style="flex: 1;">
                    <input type="number" name="item_quantidade[]" class="form-control" placeholder="Qtd prevista" min="0">
                </div>
                <div style="flex: 2;">
                    <input type="text" name="item_observacao[]" class="form-control" placeholder="Obs (ex: Quilos, Litros...)">
                </div>
                <div style="display: flex; align-items: center;">
                    <button type="button" class="btn btn-danger btn-sm btn-remover-item" title="Remover item">&times;</button>
                </div>
            `;
            containerItens.appendChild(itemRow);

            itemRow.querySelector('.btn-remover-item').addEventListener('click', () => {
                itemRow.remove();
            });
        });

        // Delegação de evento para remover itens existentes
        containerItens.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('btn-remover-item')) {
                e.target.closest('.item-cardapio-row').remove();
            }
        });
    }

    // 5. Preenchimento Rápido de Contas de Demonstração na Tela de Login
    const demoItems = document.querySelectorAll('.demo-item');
    if (demoItems.length > 0) {
        const emailInput = document.getElementById('email');
        const senhaInput = document.getElementById('senha');

        demoItems.forEach(item => {
            item.addEventListener('click', () => {
                const email = item.getAttribute('data-email');
                if (emailInput) emailInput.value = email;
                if (senhaInput) senhaInput.value = 'senha123';
            });
        });
    }
});
