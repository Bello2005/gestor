import "./bootstrap";

// ============================================================================
// QUANTUM - Alpine.js Setup
// ============================================================================
import Alpine from 'alpinejs';

// Global Alpine data
window.Alpine = Alpine;

// Quantum Store - Global State
Alpine.store('quantum', {
    sidebarOpen: window.innerWidth >= 768,
    darkMode: true, // Quantum is dark by default

    toggleSidebar() {
        this.sidebarOpen = !this.sidebarOpen;
    },

    toggleDarkMode() {
        this.darkMode = !this.darkMode;
        document.documentElement.classList.toggle('dark');
    }
});

// Start Alpine
Alpine.start();

// ============================================================================
// Legacy Sidebar Toggle (mantener compatibilidad)
// ============================================================================
document.addEventListener("DOMContentLoaded", function () {
    const sidebarToggle = document.getElementById("sidebarToggle");
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.getElementById("main-content");

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener("click", function () {
            sidebar.classList.toggle("collapsed");
            document.body.classList.toggle("sidebar-collapsed");
            if (mainContent) {
                mainContent.classList.toggle("sidebar-collapsed");
            }
        });
    }
});

// ============================================================================
// Quantum - Global Utilities
// ============================================================================

// Toast Notifications (simple implementation)
window.showToast = function(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-quantum shadow-quantum-lg animate-slide-down`;

    const colors = {
        success: 'bg-green-500/90 text-white',
        error: 'bg-red-500/90 text-white',
        warning: 'bg-yellow-500/90 text-white',
        info: 'bg-quantum-500/90 text-white'
    };

    toast.className += ` ${colors[type] || colors.info}`;
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
};

// Smooth scroll to element
window.scrollToElement = function(selector) {
    const element = document.querySelector(selector);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
};

// Copy to clipboard
window.copyToClipboard = function(text) {
    navigator.clipboard.writeText(text).then(() => {
        showToast('Copiado al portapapeles', 'success');
    });
};

console.log('🌌 Quantum Design System Initialized');
