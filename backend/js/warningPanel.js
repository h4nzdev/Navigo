/**
 * Warning Panel JavaScript Component for Navigo Project
 * Handles popup warning display and interaction
 */

class WarningPanel {
    constructor() {
        this.overlay = null;
        this.panel = null;
        this.messageElement = null;
        this.closeButton = null;
        this.isVisible = false;
        
        this.init();
    }
    
    /**
     * Initialize the warning panel
     */
    init() {
        // Create the warning panel if it doesn't exist
        this.createWarningPanel();
        
        // Bind events
        this.bindEvents();
        
        // Add to page
        if (!document.getElementById('warningPanelOverlay')) {
            document.body.appendChild(this.overlay);
        }
    }
    
    /**
     * Create the warning panel HTML structure
     */
    createWarningPanel() {
        this.overlay = document.createElement('div');
        this.overlay.className = 'warning-panel-overlay';
        this.overlay.id = 'warningPanelOverlay';
        this.overlay.style.display = 'none';
        
        this.overlay.innerHTML = `
            <div class="warning-panel">
                <div class="warning-panel-content">
                    <div class="warning-header">
                        <div class="warning-icon-small">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <h2 class="warning-title">Oops!</h2>
                    </div>
                    <p class="warning-message" id="warningMessage">Account does not exist!</p>
                    <button class="warning-close-btn" id="warningCloseBtn">
                        <i class="fas fa-check"></i>
                        Got it
                    </button>
                </div>
            </div>
        `;
        
        this.panel = this.overlay.querySelector('.warning-panel');
        this.messageElement = this.overlay.querySelector('#warningMessage');
        this.closeButton = this.overlay.querySelector('#warningCloseBtn');
    }
    
    /**
     * Bind event listeners
     */
    bindEvents() {
        // Close button click
        if (this.closeButton) {
            this.closeButton.addEventListener('click', () => {
                this.hide();
            });
        }
        
        // Overlay click to close
        if (this.overlay) {
            this.overlay.addEventListener('click', (e) => {
                if (e.target === this.overlay) {
                    this.hide();
                }
            });
        }
        
        // Escape key to close
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && this.isVisible) {
                this.hide();
            }
        });
    }
    
    /**
     * Show the warning panel with custom message
     * @param {string} message - The warning message to display
     * @param {string} type - The type of warning (warning, error, success, info)
     * @param {string} title - Optional custom title
     */
    show(message = 'Account does not exist!', type = 'warning', title = 'Oops!') {
        if (!this.overlay) {
            this.init();
        }
        
        // Update content
        this.messageElement.textContent = message;
        this.overlay.querySelector('.warning-title').textContent = title;
        
        // Update panel type
        this.panel.className = `warning-panel ${type}`;
        
        // Update icon based on type
        const iconElement = this.overlay.querySelector('.warning-icon-small i');
        const icons = {
            warning: 'fa-exclamation-triangle',
            error: 'fa-times-circle',
            success: 'fa-check-circle',
            info: 'fa-info-circle'
        };
        
        if (iconElement) {
            iconElement.className = `fas ${icons[type] || icons.warning}`;
        }
        
        // Show the panel
        this.overlay.style.display = 'flex';
        this.isVisible = true;
        
        // Trigger animation
        requestAnimationFrame(() => {
            this.overlay.classList.add('visible');
        });
        
        // Focus the close button for accessibility
        setTimeout(() => {
            if (this.closeButton) {
                this.closeButton.focus();
            }
        }, 100);
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    }
    
    /**
     * Hide the warning panel
     */
    hide() {
        if (!this.isVisible || !this.overlay) return;
        
        // Trigger fade out animation
        this.overlay.classList.remove('visible');
        this.overlay.classList.add('fade-out');
        
        // Hide after animation
        setTimeout(() => {
            this.overlay.style.display = 'none';
            this.overlay.classList.remove('fade-out');
            this.isVisible = false;
            
            // Restore body scroll
            document.body.style.overflow = '';
        }, 300);
    }
    
    /**
     * Check if the warning panel is currently visible
     * @returns {boolean}
     */
    isPanelVisible() {
        return this.isVisible;
    }
    
    /**
     * Update the warning message without showing the panel
     * @param {string} message - The new message
     */
    updateMessage(message) {
        if (this.messageElement) {
            this.messageElement.textContent = message;
        }
    }
    
    /**
     * Update the warning title without showing the panel
     * @param {string} title - The new title
     */
    updateTitle(title) {
        const titleElement = this.overlay.querySelector('.warning-title');
        if (titleElement) {
            titleElement.textContent = title;
        }
    }
}

// Global warning panel instance
let warningPanelInstance = null;

/**
 * Initialize the warning panel
 */
function initWarningPanel() {
    if (!warningPanelInstance) {
        warningPanelInstance = new WarningPanel();
    }
    return warningPanelInstance;
}

/**
 * Show warning with custom message
 * @param {string} message - The warning message
 * @param {string} type - The type of warning (warning, error, success, info)
 * @param {string} title - Optional custom title
 */
function showWarning(message, type = 'warning', title = 'Oops!') {
    const panel = initWarningPanel();
    panel.show(message, type, title);
}

/**
 * Hide the warning panel
 */
function hideWarning() {
    if (warningPanelInstance) {
        warningPanelInstance.hide();
    }
}

/**
 * Check if warning panel is visible
 * @returns {boolean}
 */
function isWarningVisible() {
    return warningPanelInstance ? warningPanelInstance.isPanelVisible() : false;
}

/**
 * Predefined warning messages for common scenarios
 */
const WarningMessages = {
    ACCOUNT_NOT_FOUND: 'Account does not exist! Please check your email or create a new account.',
    INVALID_CREDENTIALS: 'Incorrect email or password. Please check your credentials.',
    NETWORK_ERROR: 'Network error. Please check your connection and try again.',
    SERVER_ERROR: 'Server error. Please try again later.',
    VALIDATION_ERROR: 'Please fill in all required fields correctly.',
    SESSION_EXPIRED: 'Your session has expired. Please sign in again.',
    ACCESS_DENIED: 'Access denied. You do not have permission to perform this action.',
    MAINTENANCE_MODE: 'The system is currently under maintenance. Please try again later.'
};

/**
 * Show predefined warning messages
 * @param {string} messageKey - Key from WarningMessages object
 * @param {string} type - The type of warning
 * @param {string} title - Optional custom title
 */
function showPredefinedWarning(messageKey, type = 'warning', title = 'Oops!') {
    const message = WarningMessages[messageKey] || messageKey;
    showWarning(message, type, title);
}

// Auto-initialize when DOM is ready
document.addEventListener('DOMContentLoaded', function() {
    initWarningPanel();
});

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        WarningPanel,
        showWarning,
        hideWarning,
        isWarningVisible,
        showPredefinedWarning,
        WarningMessages
    };
}
