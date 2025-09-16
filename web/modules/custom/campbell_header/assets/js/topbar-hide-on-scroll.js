// web/modules/custom/campbell_header/assets/js/topbar-hide-on-scroll.js

(function () {
  // --- Top bar hide-on-scroll (orange bar) ---
  const bar = document.getElementById('cm-topbar');
  if (bar) {
    let lastY = window.scrollY;
    let hidden = false;

    const onScroll = () => {
      const y = window.scrollY;
      // Hide when scrolling down past 24px; show when scrolling up or near top.
      if (y > 24 && y > lastY && !hidden) {
        hidden = true;
        bar.style.transform = 'translateY(-100%)';
      } else if ((y < lastY || y <= 24) && hidden) {
        hidden = false;
        bar.style.transform = 'translateY(0)';
      }
      lastY = y;
    };

    let ticking = false;
    window.addEventListener('scroll', () => {
      if (!ticking) {
        requestAnimationFrame(() => { onScroll(); ticking = false; });
        ticking = true;
      }
    }, { passive: true });
  }

  // --- Mobile nav toggle for sticky header ---
  const btn = document.getElementById('cm-nav-toggle');
  const panel = document.getElementById('cm-mobile-nav');

  if (btn && panel) {
    const closePanel = () => {
      btn.setAttribute('aria-expanded', 'false');
      panel.classList.add('hidden');
    };

    const openPanel = () => {
      btn.setAttribute('aria-expanded', 'true');
      panel.classList.remove('hidden');
    };

    btn.addEventListener('click', () => {
      const expanded = btn.getAttribute('aria-expanded') === 'true';
      expanded ? closePanel() : openPanel();
    });

    // ESC to close
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') closePanel();
    });

    // Click outside to close
    document.addEventListener('click', (e) => {
      const isOpen = btn.getAttribute('aria-expanded') === 'true';
      if (!isOpen) return;
      if (!panel.contains(e.target) && !btn.contains(e.target)) {
        closePanel();
      }
    }, true);
  }
})();
