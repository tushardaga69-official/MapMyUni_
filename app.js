(() => {
  const icon = (path) => `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${path}</svg>`;
  const moon = '<path d="M20.9 13A8.9 8.9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z"/>';
  const sun = '<circle cx="12" cy="12" r="4"/><path d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42"/>';
  const themeButton = document.querySelector('[data-theme-toggle]');

  function setTheme(dark, persist = true) {
    document.body.classList.toggle('dark', dark);
    if (themeButton) {
      themeButton.innerHTML = icon(dark ? sun : moon);
      themeButton.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
      themeButton.setAttribute('aria-pressed', String(dark));
    }
    if (persist) {
      try { localStorage.setItem('mapmyuni-theme', dark ? 'dark' : 'light'); } catch (_) {}
    }
  }

  let savedTheme = 'light';
  try { savedTheme = localStorage.getItem('mapmyuni-theme') || 'light'; } catch (_) {}
  setTheme(savedTheme === 'dark', false);
  themeButton?.addEventListener('click', () => setTheme(!document.body.classList.contains('dark')));

  const menuButton = document.querySelector('[data-menu-toggle]');
  const nav = document.getElementById('primaryNav');
  if (menuButton && nav) {
    const setMenu = (open) => {
      nav.classList.toggle('open', open);
      menuButton.setAttribute('aria-expanded', String(open));
      menuButton.setAttribute('aria-label', open ? 'Close navigation menu' : 'Open navigation menu');
      menuButton.innerHTML = open
        ? icon('<path d="m18 6-12 12M6 6l12 12"/>')
        : icon('<path d="M4 7h16M4 12h16M4 17h16"/>');
    };
    menuButton.addEventListener('click', () => setMenu(menuButton.getAttribute('aria-expanded') !== 'true'));
    nav.querySelectorAll('a').forEach((link) => link.addEventListener('click', () => setMenu(false)));
    document.addEventListener('keydown', (event) => { if (event.key === 'Escape') setMenu(false); });
  }

  const lightbox = document.getElementById('galleryLightbox');
  if (lightbox) {
    const image = lightbox.querySelector('[data-lightbox-image]');
    const caption = lightbox.querySelector('[data-lightbox-caption]');
    document.querySelectorAll('[data-lightbox-src]').forEach((tile) => {
      tile.addEventListener('click', () => {
        image.src = tile.dataset.lightboxSrc;
        image.alt = tile.dataset.lightboxAlt || 'Campus photograph';
        caption.textContent = image.alt;
        lightbox.showModal();
      });
    });
    lightbox.querySelector('[data-lightbox-close]')?.addEventListener('click', () => lightbox.close());
    lightbox.addEventListener('click', (event) => { if (event.target === lightbox) lightbox.close(); });
    lightbox.addEventListener('close', () => { image.removeAttribute('src'); });
  }
})();
