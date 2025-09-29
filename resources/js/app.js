// resources/js/app.js
// If you already import other stuff here, keep it.
// We are NOT using Alpine for sidebar. Pure JS.

function qs(sel, ctx = document) { return ctx.querySelector(sel); }
function qsa(sel, ctx = document) { return Array.from(ctx.querySelectorAll(sel)); }

const Sidebar = {
  el: null,
  backdrop: null,
  main: null,
  collapsed: false,
  mobileOpen: false,

  init() {
    this.el = qs('#app-sidebar');
    this.backdrop = qs('#app-sidebar-backdrop');
    this.main = qs('#app-main');

    this.collapsed = localStorage.getItem('sidebar-collapsed') === '1';
    this.applyDesktopWidth();
    this.applyMobilePosition();

    qsa('[data-action="toggle-sidebar"]').forEach(btn =>
      btn.addEventListener('click', () => this.toggleMobile()));
    qsa('[data-action="close-sidebar"]').forEach(btn =>
      btn.addEventListener('click', () => this.closeMobile()));
    qsa('[data-action="collapse-sidebar"]').forEach(btn =>
      btn.addEventListener('click', () => this.toggleCollapsed()));

    this.backdrop?.addEventListener('click', () => this.closeMobile());
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') this.closeMobile(); });

    this.syncAria();
  },

  // MOBILE
  openMobile() {
    this.mobileOpen = true;
    this.applyMobilePosition();
    document.documentElement.classList.add('overflow-hidden'); // lock scroll
  },
  closeMobile() {
    this.mobileOpen = false;
    this.applyMobilePosition();
    document.documentElement.classList.remove('overflow-hidden');
  },
  toggleMobile() { this.mobileOpen ? this.closeMobile() : this.openMobile(); },
  applyMobilePosition() {
    if (!this.el) return;
    // Only translate on < lg
    if (this.mobileOpen) {
      this.el.classList.remove('-translate-x-full');
      this.el.classList.add('translate-x-0');
      this.backdrop?.classList.remove('hidden');
    } else {
      this.el.classList.add('-translate-x-full');
      this.el.classList.remove('translate-x-0');
      this.backdrop?.classList.add('hidden');
    }
    this.syncAria();
  },

  // DESKTOP
  toggleCollapsed() {
    this.collapsed = !this.collapsed;
    localStorage.setItem('sidebar-collapsed', this.collapsed ? '1' : '0');
    this.applyDesktopWidth();
  },
  applyDesktopWidth() {
    if (!this.el) return;

    if (this.collapsed) {
      this.el.classList.add('lg:w-20'); this.el.classList.remove('lg:w-64');
      this.main?.classList.remove('lg:pl-64'); this.main?.classList.add('lg:pl-20');
      qsa('[data-role="brand-text"]').forEach(el => el.classList.add('lg:hidden'));
      qsa('[data-role="center-when-collapsed"]').forEach(el => el.classList.add('lg:justify-center'));
    } else {
      this.el.classList.remove('lg:w-20'); this.el.classList.add('lg:w-64');
      this.main?.classList.remove('lg:pl-20'); this.main?.classList.add('lg:pl-64');
      qsa('[data-role="brand-text"]').forEach(el => el.classList.remove('lg:hidden'));
      qsa('[data-role="center-when-collapsed"]').forEach(el => el.classList.remove('lg:justify-center'));
    }
    this.syncAria();
  },

  syncAria() {
    qsa('[data-action="toggle-sidebar"]').forEach(btn =>
      btn.setAttribute('aria-expanded', this.mobileOpen ? 'true' : 'false'));
    qsa('[data-action="collapse-sidebar"]').forEach(btn =>
      btn.setAttribute('aria-pressed', this.collapsed ? 'true' : 'false'));
  }
};

// Account dropdown (vanilla JS)
const AccountMenu = {
  btn: null,
  menu: null,
  open: false,

  init() {
    this.btn = document.querySelector('[data-action="toggle-account-menu"]');
    this.menu = document.getElementById('account-menu');
    if (!this.btn || !this.menu) return;

    this.btn.addEventListener('click', (e) => {
      e.stopPropagation();
      this.toggle();
    });

    // Close on outside click
    document.addEventListener('click', (e) => {
      if (!this.open) return;
      if (!this.menu.contains(e.target) && e.target !== this.btn) this.close();
    });

    // Close on Esc
    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') this.close();
    });
  },

  openMenu() {
    this.open = true;
    this.menu.classList.remove('hidden');
    this.btn.setAttribute('aria-expanded', 'true');
  },
  close() {
    this.open = false;
    this.menu.classList.add('hidden');
    this.btn.setAttribute('aria-expanded', 'false');
  },
  toggle() { this.open ? this.close() : this.openMenu(); }
};

window.addEventListener('DOMContentLoaded', () => {
  Sidebar.init();
  AccountMenu.init();
});
