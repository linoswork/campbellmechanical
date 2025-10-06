(function (Drupal) {
  Drupal.behaviors.cmHeroSwiper = {
    attach: function (context) {
      const root = context.querySelector('.cm-hero-slider.swiper-container');
      if (!root) return;

      const swiper = new Swiper('.cm-hero-slider', {
        slidesPerView: 1,
        spaceBetween: 0,
        effect: 'fade',
        fadeEffect: { crossFade: true },
        autoHeight: false,
        loop: true,
        speed: 600,
        autoplay: { delay: 6000, disableOnInteraction: false },
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
      });


      // Respect motion/save-data & lazy play
      const saveData = navigator.connection && navigator.connection.saveData;
      const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

      root.querySelectorAll('.cm-hero-video').forEach(v => {
        if (saveData || prefersReducedMotion) {
          v.removeAttribute('autoplay'); v.pause(); return;
        }
        const io = new IntersectionObserver(es => {
          es.forEach(e => { if (e.isIntersecting) { v.play().catch(()=>{}); io.disconnect(); } });
        }, { rootMargin: '200px 0px' });
        io.observe(v);
      });
    }
  }
})(Drupal);
