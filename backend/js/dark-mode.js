// Global Dark Mode System
class DarkModeManager {
    constructor() {
        this.isDarkMode = this.getStoredMode();
        this.init();
    }

    init() {
        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', () => {
                this.initializeDarkMode();
            });
        } else {
            this.initializeDarkMode();
        }
    }

    initializeDarkMode() {
        // Apply dark mode on page load
        this.applyDarkMode();
        
        // Set up event listeners for all dark mode toggles
        this.setupToggleListeners();
    }

    getStoredMode() {
        // Check localStorage first, then system preference
        const stored = localStorage.getItem('darkMode');
        if (stored !== null) {
            return stored === 'true';
        }
        
        // Fallback to system preference
        return window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    setDarkMode(isDark) {
        this.isDarkMode = isDark;
        localStorage.setItem('darkMode', isDark.toString());
        this.applyDarkMode();
    }

    toggleDarkMode() {
        this.setDarkMode(!this.isDarkMode);
    }

    applyDarkMode() {
        const body = document.body;
        
        if (this.isDarkMode) {
            body.classList.add('dark-mode');
            console.log('Dark mode applied');
        } else {
            body.classList.remove('dark-mode');
            console.log('Light mode applied');
        }

        // Update all dark mode toggle icons
        this.updateToggleIcons();
    }

    updateToggleIcons() {
        const toggleButtons = document.querySelectorAll('.dark-mode-toggle i');
        toggleButtons.forEach(icon => {
            if (this.isDarkMode) {
                icon.className = 'fas fa-sun';
            } else {
                icon.className = 'fas fa-moon';
            }
        });
    }

    setupToggleListeners() {
        // Listen for clicks on any dark mode toggle
        document.addEventListener('click', (event) => {
            if (event.target.closest('.dark-mode-toggle')) {
                event.preventDefault();
                console.log('Dark mode toggle clicked');
                this.toggleDarkMode();
            }
        });

        // Listen for system theme changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
            // Only update if user hasn't manually set a preference
            if (localStorage.getItem('darkMode') === null) {
                this.setDarkMode(e.matches);
            }
        });
    }

    // Public method to get current state
    getCurrentMode() {
        return this.isDarkMode;
    }
}

// Initialize global dark mode manager
window.darkModeManager = new DarkModeManager();

// Legacy function for backward compatibility
function toggleDarkMode() {
    window.darkModeManager.toggleDarkMode();
}

// Export for module systems if needed
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DarkModeManager;
}