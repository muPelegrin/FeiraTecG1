/**
 * Central de Frequência e Alimentação Escolar
 * JavaScript Puro (Vanilla JS) — Sem frameworks externos
 * Mostra de Tecnologia 2026 — Grupo 01
 *
 * Microinterações Neumorphism (Soft UI)
 */

document.addEventListener('DOMContentLoaded', () => {

    // =========================================================================
    // DETECÇÃO DE PREFERÊNCIA DE MOVIMENTO REDUZIDO
    // =========================================================================
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // =========================================================================
    // 1. TOGGLE DA BARRA LATERAL EM DISPOSITIVOS MÓVEIS
    // =========================================================================
    const sidebarToggleBtn = document.querySelector('.btn-sidebar-toggle');
    const sidebar = document.querySelector('.app-sidebar');

    if (sidebarToggleBtn && sidebar) {
        sidebarToggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });

        // Fecha ao clicar fora em telas pequenas
        document.addEventListener('click', (e) => {
            if (window.innerWidth <= 992) {
                if (
                    !sidebar.contains(e.target) &&
                    !sidebarToggleBtn.contains(e.target) &&
                    sidebar.classList.contains('show')
                ) {
                    sidebar.classList.remove('show');
                }
            }
        });
    }

    // =========================================================================
    // 2. FECHAMENTO AUTOMÁTICO DE ALERTAS COM ANIMAÇÃO DE ENTRADA E SAÍDA
    // =========================================================================
    const alertas = document.querySelectorAll('.alert');
    alertas.forEach((alerta) => {
        // Animação de entrada: slide-down (se movimento permitido)
        if (!prefersReducedMotion) {
            alerta.style.overflow = 'hidden';
            alerta.style.maxHeight = '0';
            alerta.style.opacity = '0';
            alerta.style.transition = 'max-height 0.4s ease, opacity 0.4s ease, margin 0.4s ease, padding 0.4s ease';

            // Forçar reflow antes de animar
            void alerta.offsetHeight;

            // Revelar com slide-down
            alerta.style.maxHeight = alerta.scrollHeight + 20 + 'px';
            alerta.style.opacity = '1';
        }

        // Auto-dismiss após 6 segundos
        setTimeout(() => {
            if (!prefersReducedMotion) {
                alerta.style.transition = 'max-height 0.5s ease, opacity 0.5s ease, margin 0.5s ease, padding 0.5s ease';
                alerta.style.maxHeight = '0';
                alerta.style.opacity = '0';
                alerta.style.marginTop = '0';
                alerta.style.marginBottom = '0';
                alerta.style.paddingTop = '0';
                alerta.style.paddingBottom = '0';
                setTimeout(() => alerta.remove(), 550);
            } else {
                alerta.style.transition = 'opacity 0.5s ease';
                alerta.style.opacity = '0';
                setTimeout(() => alerta.remove(), 500);
            }
        }, 6000);
    });

    // =========================================================================
    // 3. AÇÕES RÁPIDAS DE CHAMADA NOMINAL
    // =========================================================================
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

    // =========================================================================
    // 4. ADIÇÃO E REMOÇÃO DINÂMICA DE ITENS NO FORMULÁRIO DE CARDÁPIO
    // =========================================================================
    const btnAddItemCardapio = document.getElementById('btn-add-item-cardapio');
    const containerItens = document.getElementById('itens-cardapio-container');

    if (btnAddItemCardapio && containerItens) {
        btnAddItemCardapio.addEventListener('click', () => {
            const itemRow = document.createElement('div');
            itemRow.className = 'form-row item-cardapio-row';
            itemRow.style.marginBottom = '0.75rem';

            // Iniciar com opacidade 0 para animar entrada
            if (!prefersReducedMotion) {
                itemRow.style.opacity = '0';
                itemRow.style.transform = 'translateY(-10px)';
                itemRow.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
            }

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

            // Animação de entrada suave
            if (!prefersReducedMotion) {
                void itemRow.offsetHeight;
                itemRow.style.opacity = '1';
                itemRow.style.transform = 'translateY(0)';
            }

            itemRow.querySelector('.btn-remover-item').addEventListener('click', () => {
                if (!prefersReducedMotion) {
                    itemRow.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                    itemRow.style.opacity = '0';
                    itemRow.style.transform = 'translateY(-10px)';
                    setTimeout(() => itemRow.remove(), 260);
                } else {
                    itemRow.remove();
                }
            });
        });

        // Delegação de evento para remover itens existentes
        containerItens.addEventListener('click', (e) => {
            if (e.target && e.target.classList.contains('btn-remover-item')) {
                const row = e.target.closest('.item-cardapio-row');
                if (row) {
                    if (!prefersReducedMotion) {
                        row.style.transition = 'opacity 0.25s ease, transform 0.25s ease';
                        row.style.opacity = '0';
                        row.style.transform = 'translateY(-10px)';
                        setTimeout(() => row.remove(), 260);
                    } else {
                        row.remove();
                    }
                }
            }
        });
    }

    // =========================================================================
    // 5. PREENCHIMENTO RÁPIDO DE CONTAS DEMO NA TELA DE LOGIN
    // =========================================================================
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

    // =========================================================================
    // 6. CONTADORES ANIMADOS (ANIMATED NUMBER COUNTERS) — DASHBOARD
    // =========================================================================
    /**
     * Easing easeOutQuart: desaceleração natural
     * @param {number} t - progresso normalizado (0 a 1)
     * @returns {number}
     */
    function easeOutQuart(t) {
        return 1 - Math.pow(1 - t, 4);
    }

    /**
     * Anima um elemento numérico de 0 até o valor alvo.
     * @param {HTMLElement} el - elemento contendo o número
     */
    function animarContador(el) {
        const textoOriginal = el.textContent.trim();

        // Separar possível prefixo/sufixo (ex: "R$ 1.234" ou "95%")
        const match = textoOriginal.match(/^([^\d]*)([\d.,]+)([^\d]*)$/);
        if (!match) return;

        const prefixo = match[1];
        const sufixo = match[3];
        const numTexto = match[2];

        // Detectar separador decimal (último ponto ou vírgula)
        let valorAlvo;
        let casasDecimais = 0;
        let usaVirgula = false;

        if (numTexto.includes(',') && numTexto.indexOf(',') > numTexto.lastIndexOf('.')) {
            // Formato brasileiro: 1.234,56
            usaVirgula = true;
            const limpo = numTexto.replace(/\./g, '').replace(',', '.');
            valorAlvo = parseFloat(limpo);
            const partes = numTexto.split(',');
            casasDecimais = partes.length > 1 ? partes[1].length : 0;
        } else {
            // Formato padrão ou inteiro
            const limpo = numTexto.replace(/,/g, '');
            valorAlvo = parseFloat(limpo);
            const partes = limpo.split('.');
            casasDecimais = partes.length > 1 ? partes[1].length : 0;
        }

        if (isNaN(valorAlvo)) return;

        // Se o usuário prefere sem animação, manter o valor original
        if (prefersReducedMotion) return;

        const duracao = 1200; // 1.2 segundos
        const inicio = performance.now();

        /**
         * Formata o número parcial conforme o formato detectado
         * @param {number} num
         * @returns {string}
         */
        function formatarNumero(num) {
            const fixo = num.toFixed(casasDecimais);
            if (usaVirgula) {
                // Formato brasileiro com pontos de milhar e vírgula decimal
                const partes = fixo.split('.');
                partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, '.');
                return partes.join(',');
            }
            // Formato com vírgula de milhar (se originalmente tinha)
            if (numTexto.includes(',')) {
                const partes = fixo.split('.');
                partes[0] = partes[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                return partes.join('.');
            }
            // Formato com ponto de milhar (brasileiro sem decimal)
            if (numTexto.includes('.') && casasDecimais === 0) {
                return fixo.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }
            return fixo;
        }

        function atualizar(agora) {
            const progresso = Math.min((agora - inicio) / duracao, 1);
            const valorAtual = valorAlvo * easeOutQuart(progresso);
            el.textContent = prefixo + formatarNumero(valorAtual) + sufixo;

            if (progresso < 1) {
                requestAnimationFrame(atualizar);
            } else {
                // Garantir valor final exato
                el.textContent = textoOriginal;
            }
        }

        // Iniciar com zero
        el.textContent = prefixo + formatarNumero(0) + sufixo;
        requestAnimationFrame(atualizar);
    }

    // Usar IntersectionObserver para animar apenas quando visível
    const statNumbers = document.querySelectorAll('.stat-number');
    if (statNumbers.length > 0 && !prefersReducedMotion) {
        const contadorObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !entry.target.dataset.animado) {
                    entry.target.dataset.animado = 'true';
                    animarContador(entry.target);
                    contadorObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.3 });

        statNumbers.forEach(el => contadorObserver.observe(el));
    }

    // =========================================================================
    // 7. FADE-IN ESCALONADO PARA CARDS (STAGGERED FADE-IN)
    // =========================================================================
    if (!prefersReducedMotion) {
        const cards = document.querySelectorAll('.card, .stat-card');

        if (cards.length > 0) {
            // Preparar cards: invisíveis inicialmente
            cards.forEach(card => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';
            });

            const cardObserver = new IntersectionObserver((entries) => {
                // Coletar todos os cards que ficaram visíveis neste batch
                const visiveis = entries.filter(e => e.isIntersecting);

                visiveis.forEach((entry, indice) => {
                    const card = entry.target;
                    // Delay escalonado: 100ms entre cada card
                    setTimeout(() => {
                        card.classList.add('neu-visible');
                        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, indice * 100);

                    cardObserver.unobserve(card);
                });
            }, { threshold: 0.1 });

            cards.forEach(card => cardObserver.observe(card));
        }
    } else {
        // Sem animação: garantir que cards estejam visíveis
        document.querySelectorAll('.card, .stat-card').forEach(card => {
            card.classList.add('neu-visible');
        });
    }

    // =========================================================================
    // 8. EFEITO DE PROFUNDIDADE AO PRESSIONAR BOTÕES (BUTTON PRESS DEPTH)
    // =========================================================================
    document.addEventListener('mousedown', (e) => {
        const btn = e.target.closest('.btn');
        if (btn) btn.classList.add('neu-pressed');
    });

    document.addEventListener('mouseup', () => {
        document.querySelectorAll('.btn.neu-pressed').forEach(btn => {
            btn.classList.remove('neu-pressed');
        });
    });

    document.addEventListener('mouseleave', () => {
        document.querySelectorAll('.btn.neu-pressed').forEach(btn => {
            btn.classList.remove('neu-pressed');
        });
    }, true);

    // Suporte a toque para dispositivos móveis
    document.addEventListener('touchstart', (e) => {
        const btn = e.target.closest('.btn');
        if (btn) btn.classList.add('neu-pressed');
    }, { passive: true });

    document.addEventListener('touchend', () => {
        document.querySelectorAll('.btn.neu-pressed').forEach(btn => {
            btn.classList.remove('neu-pressed');
        });
    }, { passive: true });

    // =========================================================================
    // 9. EFEITO DE PROFUNDIDADE AO HOVER EM CARDS (CARD HOVER DEPTH)
    // =========================================================================
    const cardsHover = document.querySelectorAll('.card, .stat-card');
    cardsHover.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.classList.add('neu-hover');
        });
        card.addEventListener('mouseleave', () => {
            card.classList.remove('neu-hover');
        });
    });

    // =========================================================================
    // 10. SCROLL SUAVE PARA LINKS COM HASH (SMOOTH SCROLL)
    // =========================================================================
    document.querySelectorAll('a[href^="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const hash = link.getAttribute('href');
            if (hash && hash.length > 1) {
                const alvo = document.querySelector(hash);
                if (alvo) {
                    e.preventDefault();
                    alvo.scrollIntoView({
                        behavior: prefersReducedMotion ? 'auto' : 'smooth',
                        block: 'start'
                    });

                    // Atualizar URL sem recarregar
                    if (history.pushState) {
                        history.pushState(null, null, hash);
                    }
                }
            }
        });
    });

    // =========================================================================
    // 11. INDICADOR ATIVO NA SIDEBAR
    // =========================================================================
    // Garantir que o item ativo na sidebar tenha a classe correta
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.app-sidebar .nav-link');
    sidebarLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath.includes(href) && href !== '/') {
            link.classList.add('active');
            // Adicionar classe ao item pai se houver
            const navItem = link.closest('.nav-item');
            if (navItem) navItem.classList.add('active');
        }
    });

    // =========================================================================
    // 12. COMPORTAMENTO DO CAMPO DE BUSCA (SEARCH FIELD)
    // =========================================================================
    const campoBusca = document.querySelector('.topbar-search input, .search-input, input[type="search"]');
    if (campoBusca) {
        const larguraOriginal = campoBusca.offsetWidth;

        campoBusca.addEventListener('focus', () => {
            if (!prefersReducedMotion) {
                campoBusca.style.transition = 'width 0.3s ease, box-shadow 0.3s ease';
                campoBusca.style.width = (larguraOriginal * 1.25) + 'px';
            }
            campoBusca.classList.add('neu-search-focus');
        });

        campoBusca.addEventListener('blur', () => {
            if (campoBusca.value.trim() === '') {
                if (!prefersReducedMotion) {
                    campoBusca.style.width = '';
                }
                campoBusca.classList.remove('neu-search-focus');
            }
        });
    }

    // =========================================================================
    // 13. EFEITO DE RIPPLE SUTIL NOS ITENS DE MENU DA SIDEBAR
    // =========================================================================
    if (!prefersReducedMotion) {
        document.querySelectorAll('.app-sidebar .nav-link').forEach(link => {
            link.addEventListener('click', function (e) {
                // Criar elemento ripple
                const ripple = document.createElement('span');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.background = 'rgba(245, 130, 32, 0.15)';
                ripple.style.transform = 'scale(0)';
                ripple.style.animation = 'neuRipple 0.5s ease-out';
                ripple.style.pointerEvents = 'none';

                const rect = this.getBoundingClientRect();
                const tamanho = Math.max(rect.width, rect.height);
                ripple.style.width = tamanho + 'px';
                ripple.style.height = tamanho + 'px';
                ripple.style.left = (e.clientX - rect.left - tamanho / 2) + 'px';
                ripple.style.top = (e.clientY - rect.top - tamanho / 2) + 'px';

                this.style.position = 'relative';
                this.style.overflow = 'hidden';
                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 500);
            });
        });

        // Injetar keyframes do ripple se não existir
        if (!document.getElementById('neu-ripple-style')) {
            const style = document.createElement('style');
            style.id = 'neu-ripple-style';
            style.textContent = `
                @keyframes neuRipple {
                    to {
                        transform: scale(2.5);
                        opacity: 0;
                    }
                }
            `;
            document.head.appendChild(style);
        }
    }

}); // Fim do DOMContentLoaded
