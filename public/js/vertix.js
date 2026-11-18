/**
 * VERTIX UI — Modern Minimalist SaaS Dashboard
 * Front-end Interaction & Animation Engine
 * 
 * Palette:
 *   - Laranja principal: #F58220
 *   - Laranja claro:     #FFB568
 *   - Branco:            #FFFFFF
 *   - Grafite:           #333333
 */

(function () {
  'use strict';

  // --------------------------------------------------------------------------
  // 1. Theme Engine (Light / Dark Mode with localStorage)
  // --------------------------------------------------------------------------
  const STORAGE_KEY = 'vertix_theme';
  const root = document.documentElement;

  function initTheme() {
    const savedTheme = localStorage.getItem(STORAGE_KEY) || 'light';
    setTheme(savedTheme, false);

    const toggleBtn = document.getElementById('vx-theme-toggle');
    if (toggleBtn) {
      toggleBtn.addEventListener('click', () => {
        const currentTheme = root.getAttribute('data-theme') === 'dark' ? 'dark' : 'light';
        const nextTheme = currentTheme === 'dark' ? 'light' : 'dark';
        setTheme(nextTheme, true);
      });
    }
  }

  function setTheme(theme, animate) {
    if (theme === 'dark') {
      root.setAttribute('data-theme', 'dark');
      updateThemeIcon(true);
    } else {
      root.removeAttribute('data-theme');
      updateThemeIcon(false);
    }
    localStorage.setItem(STORAGE_KEY, theme);
  }

  function updateThemeIcon(isDark) {
    const toggleBtn = document.getElementById('vx-theme-toggle');
    if (!toggleBtn) return;
    if (isDark) {
      // Moon / Sun icon toggle
      toggleBtn.innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="5"></circle>
          <line x1="12" y1="1" x2="12" y2="3"></line>
          <line x1="12" y1="21" x2="12" y2="23"></line>
          <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
          <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
          <line x1="1" y1="12" x2="3" y2="12"></line>
          <line x1="21" y1="12" x2="23" y2="12"></line>
          <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
          <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
        </svg>`;
      toggleBtn.setAttribute('title', 'Alternar para Modo Claro');
      toggleBtn.setAttribute('aria-label', 'Modo Claro');
    } else {
      toggleBtn.innerHTML = `
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
        </svg>`;
      toggleBtn.setAttribute('title', 'Alternar para Modo Escuro');
      toggleBtn.setAttribute('aria-label', 'Modo Escuro');
    }
  }

  // --------------------------------------------------------------------------
  // 2. Animated Number Counter Component (Section 5)
  //    - requestAnimationFrame
  //    - easeOutExpo easing: t === 1 ? 1 : 1 - Math.pow(2, -10 * t)
  //    - 1000ms - 1500ms duration
  //    - Currency & thousands formatting
  //    - Prevents concurrent animations on the same element
  // --------------------------------------------------------------------------
  function easeOutExpo(t) {
    return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
  }

  function formatNumber(value, prefix = '', suffix = '', decimals = 0) {
    const fixed = value.toFixed(decimals);
    const parts = fixed.split('.');
    const integerPart = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    const decimalPart = parts[1] !== undefined ? '.' + parts[1] : '';
    return `${prefix}${integerPart}${decimalPart}${suffix}`;
  }

  function animateCounter(el, startVal, endVal, duration = 1200, prefix = '', suffix = '', decimals = 0) {
    if (!el) return;

    // Cancel any existing animation on this element
    if (el._vxRafId) {
      cancelAnimationFrame(el._vxRafId);
      el._vxRafId = null;
    }

    const startTime = performance.now();
    const diff = endVal - startVal;

    function step(now) {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const easedProgress = easeOutExpo(progress);
      const current = startVal + diff * easedProgress;

      el.textContent = formatNumber(current, prefix, suffix, decimals);

      if (progress < 1) {
        el._vxRafId = requestAnimationFrame(step);
      } else {
        el.textContent = formatNumber(endVal, prefix, suffix, decimals);
        el._vxRafId = null;
      }
    }

    el._vxRafId = requestAnimationFrame(step);
  }

  function triggerCountersInContainer(container) {
    if (!container) return;
    const counters = container.querySelectorAll('[data-counter-end]');
    counters.forEach(counter => {
      const end = parseFloat(counter.getAttribute('data-counter-end')) || 0;
      const start = parseFloat(counter.getAttribute('data-counter-start')) || 0;
      const duration = parseInt(counter.getAttribute('data-counter-duration'), 10) || 1200;
      const prefix = counter.getAttribute('data-counter-prefix') || '';
      const suffix = counter.getAttribute('data-counter-suffix') || '';
      const decimals = parseInt(counter.getAttribute('data-counter-decimals'), 10) || 0;

      animateCounter(counter, start, end, duration, prefix, suffix, decimals);
    });
  }

  // --------------------------------------------------------------------------
  // 3. Progress Bars & Chart Entrance Microinteractions (Section 6)
  // --------------------------------------------------------------------------
  function triggerProgressBars(container) {
    if (!container) return;
    const bars = container.querySelectorAll('.vx-progress-bar-fill, .vx-rewards-progress-fill');
    bars.forEach(bar => {
      const targetWidth = bar.getAttribute('data-progress-width') || '0%';
      bar.style.width = '0%';
      // Trigger reflow then set width to animate smoothly
      requestAnimationFrame(() => {
        setTimeout(() => {
          bar.style.width = targetWidth;
        }, 50);
      });
    });
  }

  function triggerCharts(container) {
    if (!container) return;
    const chartBars = container.querySelectorAll('.vx-bar[data-height]');
    chartBars.forEach((bar, index) => {
      const targetHeight = bar.getAttribute('data-height') || '0%';
      bar.style.height = '0%';
      setTimeout(() => {
        bar.style.height = targetHeight;
      }, 60 + index * 30);
    });
  }

  // --------------------------------------------------------------------------
  // 4. Tab Transition Engine (Section 4 Priority)
  //    - No page reload
  //    - Active pill indicator update
  //    - Outgoing tab fades & slides up (vxTabSlideOut: 180ms)
  //    - Incoming tab fades & slides up (vxTabSlideIn: 340ms, cubic-bezier(0.22, 1, 0.36, 1))
  //    - Staggered card entrance via CSS classes .vx-stagger-1..4
  // --------------------------------------------------------------------------
  let isTransitioning = false;

  function switchTab(tabId) {
    if (isTransitioning) return;

    const currentActivePane = document.querySelector('.vx-tab-pane.active');
    const targetPane = document.getElementById(`tab-${tabId}`);
    if (!targetPane || currentActivePane === targetPane) return;

    isTransitioning = true;

    // 1. Update Header Tabs
    document.querySelectorAll('.vx-tab-btn').forEach(btn => {
      if (btn.getAttribute('data-tab') === tabId) {
        btn.classList.add('active');
        btn.setAttribute('aria-selected', 'true');
      } else {
        btn.classList.remove('active');
        btn.setAttribute('aria-selected', 'false');
      }
    });

    // 2. Update Sidebar navigation buttons
    document.querySelectorAll('.vx-nav-btn[data-tab]').forEach(btn => {
      if (btn.getAttribute('data-tab') === tabId) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    });

    // 3. Outgoing tab exit animation
    if (currentActivePane) {
      currentActivePane.classList.add('exiting');

      setTimeout(() => {
        currentActivePane.classList.remove('active', 'exiting');

        // 4. Incoming tab entrance animation
        targetPane.classList.add('active');

        // 5. Trigger counters, progress bars and charts
        triggerCountersInContainer(targetPane);
        triggerProgressBars(targetPane);
        triggerCharts(targetPane);

        setTimeout(() => {
          isTransitioning = false;
        }, 340);
      }, 160);
    } else {
      targetPane.classList.add('active');
      triggerCountersInContainer(targetPane);
      triggerProgressBars(targetPane);
      triggerCharts(targetPane);
      isTransitioning = false;
    }

    // Update browser URL hash quietly without jumping
    if (history.replaceState) {
      history.replaceState(null, null, `#${tabId}`);
    }
  }

  function initTabs() {
    // Header horizontal pill buttons
    document.querySelectorAll('.vx-tab-btn').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const tabId = btn.getAttribute('data-tab');
        switchTab(tabId);
      });
    });

    // Sidebar navigation buttons
    document.querySelectorAll('.vx-nav-btn[data-tab]').forEach(btn => {
      btn.addEventListener('click', (e) => {
        e.preventDefault();
        const tabId = btn.getAttribute('data-tab');
        switchTab(tabId);
      });
    });

    // Check URL hash or default to overview
    const hash = window.location.hash.replace('#', '');
    const initialTab = ['overview', 'activity', 'manage', 'program', 'account', 'reports'].includes(hash)
      ? hash
      : 'overview';

    // First load without exit animation
    const targetPane = document.getElementById(`tab-${initialTab}`);
    if (targetPane) {
      targetPane.classList.add('active');
      // Highlight initial buttons
      document.querySelectorAll(`[data-tab="${initialTab}"]`).forEach(b => b.classList.add('active'));
      // Trigger initial animations
      setTimeout(() => {
        triggerCountersInContainer(targetPane);
        triggerProgressBars(targetPane);
        triggerCharts(targetPane);
      }, 100);
    }
  }

  // --------------------------------------------------------------------------
  // 5. Activity Tab Filtering Microinteraction
  // --------------------------------------------------------------------------
  function initActivityFilters() {
    const filterButtons = document.querySelectorAll('.vx-activity-filter-btn');
    const rows = document.querySelectorAll('.vx-activity-row');

    if (!filterButtons.length) return;

    filterButtons.forEach(btn => {
      btn.addEventListener('click', () => {
        filterButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.getAttribute('data-filter'); // all, income, expense
        rows.forEach(row => {
          const type = row.getAttribute('data-tx-type');
          if (filter === 'all' || type === filter) {
            row.style.display = '';
            row.style.animation = 'vxCardStagger 300ms ease forwards';
          } else {
            row.style.display = 'none';
          }
        });
      });
    });
  }

  // --------------------------------------------------------------------------
  // 6. Interactive Card Toggles & Toast Notifications (Manage & Account)
  // --------------------------------------------------------------------------
  function initToast() {
    let toastContainer = document.getElementById('vx-toast-container');
    if (!toastContainer) {
      toastContainer = document.createElement('div');
      toastContainer.id = 'vx-toast-container';
      toastContainer.style.position = 'fixed';
      toastContainer.style.bottom = '24px';
      toastContainer.style.right = '24px';
      toastContainer.style.zIndex = '9999';
      toastContainer.style.display = 'flex';
      toastContainer.style.flexDirection = 'column';
      toastContainer.style.gap = '8px';
      toastContainer.style.pointerEvents = 'none';
      document.body.appendChild(toastContainer);
    }
  }

  function showToast(message) {
    initToast();
    const container = document.getElementById('vx-toast-container');
    const toast = document.createElement('div');
    toast.textContent = message;
    toast.style.backgroundColor = 'var(--vx-graphite)';
    toast.style.color = '#FFFFFF';
    toast.style.padding = '10px 18px';
    toast.style.borderRadius = '10px';
    toast.style.fontSize = '0.84rem';
    toast.style.fontWeight = '500';
    toast.style.boxShadow = '0 8px 24px rgba(0,0,0,0.2)';
    toast.style.border = '1px solid rgba(255, 255, 255, 0.12)';
    toast.style.opacity = '0';
    toast.style.transform = 'translateY(12px)';
    toast.style.transition = 'opacity 250ms ease, transform 250ms ease';
    toast.style.pointerEvents = 'auto';

    container.appendChild(toast);

    requestAnimationFrame(() => {
      toast.style.opacity = '1';
      toast.style.transform = 'translateY(0)';
    });

    setTimeout(() => {
      toast.style.opacity = '0';
      toast.style.transform = 'translateY(8px)';
      setTimeout(() => toast.remove(), 260);
    }, 2800);
  }

  function initInteractions() {
    // Card freeze / limit toggles feedback
    document.querySelectorAll('.vx-switch input').forEach(input => {
      input.addEventListener('change', () => {
        const label = input.closest('.vx-toggle-wrap')?.querySelector('.vx-input-label')?.textContent || 'Configuração';
        const state = input.checked ? 'ativada' : 'desativada';
        showToast(`${label} ${state} com sucesso`);
      });
    });

    // Account save form simulation
    const accountForm = document.getElementById('vx-account-form');
    if (accountForm) {
      accountForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const btn = accountForm.querySelector('button[type="submit"]');
        if (btn) {
          const original = btn.innerHTML;
          btn.innerHTML = `
            <svg style="animation: spin 1s linear infinite; width:16px; height:16px;" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <circle cx="12" cy="12" r="10" stroke-opacity="0.25"></circle>
              <path d="M12 2a10 10 0 0 1 10 10" stroke="#FFFFFF"></path>
            </svg>
            Salvando...`;
          btn.disabled = true;
          setTimeout(() => {
            btn.innerHTML = original;
            btn.disabled = false;
            showToast('Preferências salvas com sucesso');
          }, 600);
        }
      });
    }

    // Reports export button simulation
    document.querySelectorAll('.vx-export-btn').forEach(btn => {
      btn.addEventListener('click', () => {
        const type = btn.getAttribute('data-export-type') || 'PDF';
        showToast(`Relatório exportado em formato ${type}`);
      });
    });
  }

  // --------------------------------------------------------------------------
  // 7. Initialization on DOMContentLoaded
  // --------------------------------------------------------------------------
  document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initTabs();
    initActivityFilters();
    initInteractions();
  });

})();
