// Simple Mobile Menu JavaScript
console.log('Mobile menu script loaded');

const drawer = document.querySelector('[data-mobile-drawer]');
const overlay = document.querySelector('[data-mobile-overlay]');
const openers = document.querySelectorAll('[data-mobile-toggle]');
const closer = document.querySelector('[data-mobile-close]');
const logout = document.querySelector('[data-mobile-logout]');
const form = document.getElementById('logout-form-mobile');

if (!drawer) {
  console.log('Mobile drawer not found');
} else {

console.log('Mobile menu elements found:', {
  drawer: !!drawer,
  overlay: !!overlay,
  openers: openers.length,
  closer: !!closer,
  logout: !!logout,
  form: !!form
});

let isOpen = false;

function openMenu() {
  console.log('Opening mobile menu');
  isOpen = true;
  drawer.classList.remove('-translate-x-full');
  drawer.classList.add('translate-x-0');
  if (overlay) {
    overlay.classList.remove('hidden');
  }
  document.body.style.overflow = 'hidden';
}

function closeMenu() {
  console.log('Closing mobile menu');
  isOpen = false;
  drawer.classList.remove('translate-x-0');
  drawer.classList.add('-translate-x-full');
  if (overlay) {
    overlay.classList.add('hidden');
  }
  document.body.style.overflow = '';
}

// Toggle menu
openers.forEach(button => {
  button.addEventListener('click', (e) => {
    e.preventDefault();
    e.stopPropagation();
    console.log('Toggle button clicked');
    if (isOpen) {
      closeMenu();
    } else {
      openMenu();
    }
  });
});

// Close menu
if (overlay) {
  overlay.addEventListener('click', () => {
    console.log('Overlay clicked');
    closeMenu();
  });
}

if (closer) {
  closer.addEventListener('click', (e) => {
    e.preventDefault();
    console.log('Close button clicked');
    closeMenu();
  });
}

// Escape key
document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && isOpen) {
    console.log('Escape key pressed');
    closeMenu();
  }
});

// Logout
if (logout && form) {
  logout.addEventListener('click', (e) => {
    e.preventDefault();
    console.log('Logout clicked');
    form.submit();
  });
}

// Collapsible sections
function setupCollapsible(toggleSelector, contentSelector, arrowSelector) {
  const toggle = document.querySelector(toggleSelector);
  const content = document.querySelector(contentSelector);
  const arrow = document.querySelector(arrowSelector);
  
  if (toggle && content && arrow) {
    toggle.addEventListener('click', () => {
      console.log('Toggle clicked:', toggleSelector);
      const isHidden = content.classList.contains('hidden');
      
      if (isHidden) {
        content.classList.remove('hidden');
        arrow.style.transform = 'rotate(180deg)';
        console.log('Expanded:', contentSelector);
      } else {
        content.classList.add('hidden');
        arrow.style.transform = 'rotate(0deg)';
        console.log('Collapsed:', contentSelector);
      }
    });
  }
}

// Setup collapsible sections
setupCollapsible('[data-mobile-jobs-toggle]', '[data-mobile-jobs-content]', '[data-mobile-jobs-arrow]');
setupCollapsible('[data-mobile-candidates-toggle]', '[data-mobile-candidates-content]', '[data-mobile-candidates-arrow]');
setupCollapsible('[data-mobile-settings-toggle]', '[data-mobile-settings-content]', '[data-mobile-settings-arrow]');

console.log('Mobile menu initialized');
}
