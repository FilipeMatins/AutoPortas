/**
 * Auto Portas - Sidebar
 * Controle do menu lateral
 */

const Sidebar = {
    elements: {
        sidebar: null,
        overlay: null,
        mobileToggle: null,
        sidebarToggle: null
    },
    
    init() {
        this.elements.sidebar = document.getElementById('sidebar');
        this.elements.overlay = document.getElementById('sidebarOverlay');
        this.elements.mobileToggle = document.getElementById('mobileToggle');
        this.elements.sidebarToggle = document.getElementById('sidebarToggle');
        
        this.bindEvents();
    },
    
    bindEvents() {
        if (this.elements.mobileToggle) {
            this.elements.mobileToggle.addEventListener('click', () => this.toggle());
        }
        
        if (this.elements.sidebarToggle) {
            this.elements.sidebarToggle.addEventListener('click', () => this.toggle());
        }
        
        if (this.elements.overlay) {
            this.elements.overlay.addEventListener('click', () => this.close());
        }
    },
    
    toggle() {
        if (!this.elements.sidebar) return;
        
        this.elements.sidebar.classList.toggle('active');
        
        if (this.elements.overlay) {
            this.elements.overlay.classList.toggle('active');
        }
        
        document.body.style.overflow = this.elements.sidebar.classList.contains('active') ? 'hidden' : '';
    },
    
    close() {
        if (!this.elements.sidebar) return;
        
        this.elements.sidebar.classList.remove('active');
        
        if (this.elements.overlay) {
            this.elements.overlay.classList.remove('active');
        }
        
        document.body.style.overflow = '';
    }
};

// Exporta para uso global
window.Sidebar = Sidebar;

