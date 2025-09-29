// Alpine.js stores for theme and sidebar management
document.addEventListener('alpine:init', () => {
    // Theme store
    Alpine.store('theme', {
        dark: localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches),
        
        init() {
            // Set initial theme
            if (this.dark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        },
        
        toggle() {
            this.dark = !this.dark;
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
            document.documentElement.classList.toggle('dark', this.dark);
        }
    });
    
    // Sidebar store
    Alpine.store('sidebar', {
        collapsed: localStorage.getItem('sidebar-collapsed') === 'true',
        mobileOpen: false,
        
        init() {
            // Set initial collapsed state
            if (this.collapsed) {
                const sidebar = document.getElementById('sidebar');
                if (sidebar) {
                    sidebar.classList.add('collapsed');
                }
            }
        },
        
        toggle() {
            this.collapsed = !this.collapsed;
            localStorage.setItem('sidebar-collapsed', this.collapsed);
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('collapsed', this.collapsed);
            }
        },
        
        toggleMobile() {
            this.mobileOpen = !this.mobileOpen;
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.toggle('mobile-open', this.mobileOpen);
            }
        },
        
        closeMobile() {
            this.mobileOpen = false;
            const sidebar = document.getElementById('sidebar');
            if (sidebar) {
                sidebar.classList.remove('mobile-open');
            }
        }
    });
});
