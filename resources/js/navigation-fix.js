// Navigation Fix - Prevents double-click issues
document.addEventListener('DOMContentLoaded', function() {
    console.log('Navigation fix loaded');
    
    // Fix for navigation links that require double-click
    const navigationLinks = document.querySelectorAll('a[href]');
    
    navigationLinks.forEach(link => {
        // Remove any existing event listeners that might be causing conflicts
        link.removeEventListener('click', handleLinkClick);
        
        // Add a single, clean event listener
        link.addEventListener('click', handleLinkClick, { once: false });
    });
    
    function handleLinkClick(e) {
        // Only handle if it's a navigation link (not external or special links)
        const href = e.target.getAttribute('href');
        
        if (href && !href.startsWith('http') && !href.startsWith('mailto:') && !href.startsWith('tel:')) {
            // Prevent default only if it's a hash link or if there are issues
            if (href.startsWith('#')) {
                e.preventDefault();
                const target = document.querySelector(href);
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            }
            // For regular navigation links, let the browser handle them normally
            // Don't prevent default to avoid double-click issues
        }
    }
    
    // Note: Alpine.js handles collapsible sections, so we don't interfere with @click handlers
    // This prevents conflicts with sidebar menu expansion and other Alpine.js interactions
    
    console.log('Navigation fix applied to', navigationLinks.length, 'links');
});
