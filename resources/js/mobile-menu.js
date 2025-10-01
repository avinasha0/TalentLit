(function () {
  const drawer  = document.querySelector('[data-mobile-drawer]');
  const overlay = document.querySelector('[data-mobile-overlay]');
  const openers = document.querySelectorAll('[data-mobile-toggle]');
  const closer  = document.querySelector('[data-mobile-close]');
  const logout  = document.querySelector('[data-mobile-logout]');
  const form    = document.getElementById('logout-form-mobile');

  if (!drawer) return;

  const CLOSED = '-translate-x-full';
  const OPEN   = 'translate-x-0';

  let open = false;

  function lockScroll(flag) {
    document.documentElement.classList.toggle('overflow-hidden', !!flag);
  }
  function showOverlay(flag) {
    if (!overlay) return;
    overlay.classList.toggle('hidden', !flag);
    overlay.classList.toggle('opacity-0', !flag);
    overlay.classList.toggle('opacity-100', !!flag);
    overlay.style.pointerEvents = flag ? 'auto' : 'none';
  }
  function openMenu() {
    open = true;
    drawer.classList.remove(CLOSED); drawer.classList.add(OPEN);
    drawer.setAttribute('aria-hidden', 'false'); drawer.removeAttribute('inert');
    showOverlay(true); lockScroll(true);
  }
  function closeMenu() {
    open = false;
    drawer.classList.remove(OPEN); drawer.classList.add(CLOSED);
    drawer.setAttribute('aria-hidden', 'true'); drawer.setAttribute('inert', 'true');
    showOverlay(false); lockScroll(false);
  }

  openers.forEach(b => b.addEventListener('click', (e) => { e.preventDefault(); open ? closeMenu() : openMenu(); }));
  overlay && overlay.addEventListener('click', closeMenu);
  closer  && closer.addEventListener('click', (e) => { e.preventDefault(); closeMenu(); });
  document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && open) closeMenu(); });

  if (logout && form) {
    logout.addEventListener('click', (e) => { e.preventDefault(); form.submit(); });
  }
})();
