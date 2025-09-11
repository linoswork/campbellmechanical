(function () {
  const bar = document.getElementById('cm-topbar');
  if (!bar) return;

  let lastY = window.scrollY;
  let hidden = false;

  const onScroll = () => {
    const y = window.scrollY;
    // Hide when scrolling down past 24px; show when scrolling up.
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
      window.requestAnimationFrame(() => { onScroll(); ticking = false; });
      ticking = true;
    }
  }, { passive: true });
})();
