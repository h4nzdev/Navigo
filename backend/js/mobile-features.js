// NaviGo Mobile Features
// PWA install prompt, pull-to-refresh, touch gestures, and mobile optimizations

class MobileFeatures {
    constructor() {
        this.deferredPrompt = null;
        this.isInstalled = false;
        this.touchStartY = 0;
        this.touchStartX = 0;
        this.pullToRefreshThreshold = 80;
        this.swipeThreshold = 50;
        this.currentCardIndex = 0;
        this.cards = [];
        
        this.init();
    }

    init() {
        this.detectPWAInstallation();
        this.setupInstallPrompt();
        this.setupPullToRefresh();
        this.setupSwipeGestures();
        this.setupMobileOptimizations();
        this.setupSmoothScrolling();
        this.setupTouchFeedback();
        
        console.log('Mobile Features initialized');
    }

    // PWA Installation Detection and Prompt
    detectPWAInstallation() {
        // Check if running as PWA
        if (window.matchMedia('(display-mode: standalone)').matches || 
            window.navigator.standalone === true) {
            this.isInstalled = true;
            document.body.classList.add('pwa-installed');
            console.log('Running as installed PWA');
        }
    }

    setupInstallPrompt() {
        // Listen for beforeinstallprompt event
        window.addEventListener('beforeinstallprompt', (e) => {
            console.log('PWA install prompt available');
            e.preventDefault();
            this.deferredPrompt = e;
            this.showInstallButton();
        });

        // Listen for appinstalled event
        window.addEventListener('appinstalled', () => {
            console.log('PWA was installed');
            this.isInstalled = true;
            this.hideInstallButton();
            this.showNotification('NaviGo installed successfully!', 'success');
        });
    }

    showInstallButton() {
        // Create install button if it doesn't exist
        if (!document.getElementById('pwa-install-btn')) {
            const installBtn = document.createElement('button');
            installBtn.id = 'pwa-install-btn';
            installBtn.className = 'pwa-install-button';
            installBtn.innerHTML = `
                <span class="install-icon">📱</span>
                <span class="install-text">Install NaviGo</span>
            `;
            
            installBtn.addEventListener('click', () => this.installPWA());
            
            // Add to page
            document.querySelector('.signin-section').appendChild(installBtn);
        }
    }

    hideInstallButton() {
        const installBtn = document.getElementById('pwa-install-btn');
        if (installBtn) {
            installBtn.remove();
        }
    }

    async installPWA() {
        if (!this.deferredPrompt) {
            this.showNotification('Install prompt not available', 'error');
            return;
        }

        try {
            this.deferredPrompt.prompt();
            const { outcome } = await this.deferredPrompt.userChoice;
            
            if (outcome === 'accepted') {
                console.log('User accepted PWA install');
            } else {
                console.log('User dismissed PWA install');
            }
            
            this.deferredPrompt = null;
        } catch (error) {
            console.error('Error during PWA installation:', error);
            this.showNotification('Installation failed', 'error');
        }
    }

    // Pull-to-Refresh Implementation
    setupPullToRefresh() {
        let startY = 0;
        let currentY = 0;
        let isPulling = false;
        let pullIndicator = null;

        // Create pull indicator
        this.createPullIndicator();

        document.addEventListener('touchstart', (e) => {
            if (window.scrollY === 0) {
                startY = e.touches[0].clientY;
                isPulling = true;
            }
        }, { passive: true });

        document.addEventListener('touchmove', (e) => {
            if (!isPulling || window.scrollY > 0) return;

            currentY = e.touches[0].clientY;
            const pullDistance = currentY - startY;

            if (pullDistance > 0) {
                e.preventDefault();
                this.updatePullIndicator(pullDistance);
            }
        }, { passive: false });

        document.addEventListener('touchend', () => {
            if (isPulling) {
                const pullDistance = currentY - startY;
                
                if (pullDistance > this.pullToRefreshThreshold) {
                    this.triggerRefresh();
                } else {
                    this.resetPullIndicator();
                }
                
                isPulling = false;
            }
        });
    }

    createPullIndicator() {
        pullIndicator = document.createElement('div');
        pullIndicator.id = 'pull-refresh-indicator';
        pullIndicator.className = 'pull-refresh-indicator';
        pullIndicator.innerHTML = `
            <div class="pull-icon"><i class="fas fa-arrow-down"></i></div>
            <div class="pull-text">Pull to refresh</div>
        `;
        document.body.appendChild(pullIndicator);
    }

    updatePullIndicator(distance) {
        if (!pullIndicator) return;

        const progress = Math.min(distance / this.pullToRefreshThreshold, 1);
        const rotation = progress * 180;
        
        pullIndicator.style.transform = `translateY(${distance}px) rotate(${rotation}deg)`;
        pullIndicator.style.opacity = progress;
        
        if (progress >= 1) {
            pullIndicator.querySelector('.pull-text').textContent = 'Release to refresh';
        } else {
            pullIndicator.querySelector('.pull-text').textContent = 'Pull to refresh';
        }
    }

    resetPullIndicator() {
        if (!pullIndicator) return;
        
        pullIndicator.style.transform = 'translateY(-100px)';
        pullIndicator.style.opacity = '0';
    }

    triggerRefresh() {
        this.showNotification('Refreshing...', 'info');
        this.resetPullIndicator();
        
        // Simulate refresh
        setTimeout(() => {
            window.location.reload();
        }, 500);
    }

    // Swipe Gestures for Cards
    setupSwipeGestures() {
        this.cards = document.querySelectorAll('.card');
        if (this.cards.length === 0) return;

        let startX = 0;
        let startY = 0;
        let currentX = 0;
        let currentY = 0;
        let isSwipeActive = false;

        this.cards.forEach((card, index) => {
            card.addEventListener('touchstart', (e) => {
                startX = e.touches[0].clientX;
                startY = e.touches[0].clientY;
                isSwipeActive = true;
                card.style.transition = 'none';
            }, { passive: true });

            card.addEventListener('touchmove', (e) => {
                if (!isSwipeActive) return;

                currentX = e.touches[0].clientX;
                currentY = e.touches[0].clientY;
                
                const deltaX = currentX - startX;
                const deltaY = currentY - startY;
                
                // Only handle horizontal swipes
                if (Math.abs(deltaX) > Math.abs(deltaY)) {
                    e.preventDefault();
                    card.style.transform = `translateX(${deltaX * 0.3}px)`;
                }
            }, { passive: false });

            card.addEventListener('touchend', () => {
                if (!isSwipeActive) return;

                const deltaX = currentX - startX;
                card.style.transition = 'transform 0.3s ease';
                
                if (Math.abs(deltaX) > this.swipeThreshold) {
                    if (deltaX > 0) {
                        // Swipe right - previous card
                        this.showPreviousCard();
                    } else {
                        // Swipe left - next card
                        this.showNextCard();
                    }
                } else {
                    // Return to original position
                    card.style.transform = 'translateX(0)';
                }
                
                isSwipeActive = false;
            });
        });
    }

    showNextCard() {
        if (this.currentCardIndex < this.cards.length - 1) {
            this.currentCardIndex++;
            this.animateCardTransition('next');
        }
    }

    showPreviousCard() {
        if (this.currentCardIndex > 0) {
            this.currentCardIndex--;
            this.animateCardTransition('previous');
        }
    }

    animateCardTransition(direction) {
        this.cards.forEach((card, index) => {
            if (index === this.currentCardIndex) {
                card.style.transform = 'translateX(0) scale(1)';
                card.style.opacity = '1';
                card.style.zIndex = '2';
            } else {
                const offset = (index - this.currentCardIndex) * 100;
                card.style.transform = `translateX(${offset}px) scale(0.9)`;
                card.style.opacity = '0.7';
                card.style.zIndex = '1';
            }
        });
    }

    // Mobile Optimizations
    setupMobileOptimizations() {
        // Add mobile class to body
        if (this.isMobile()) {
            document.body.classList.add('mobile-device');
        }

        // Optimize viewport for mobile
        this.optimizeViewport();
        
        // Add safe area support for notched devices
        this.addSafeAreaSupport();
        
        // Optimize touch targets
        this.optimizeTouchTargets();
    }

    isMobile() {
        return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ||
               window.innerWidth <= 768;
    }

    optimizeViewport() {
        const viewport = document.querySelector('meta[name="viewport"]');
        if (viewport) {
            viewport.setAttribute('content', 
                'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover'
            );
        }
    }

    addSafeAreaSupport() {
        // Add CSS custom properties for safe areas
        const root = document.documentElement;
        root.style.setProperty('--safe-area-inset-top', 'env(safe-area-inset-top, 0px)');
        root.style.setProperty('--safe-area-inset-bottom', 'env(safe-area-inset-bottom, 0px)');
        root.style.setProperty('--safe-area-inset-left', 'env(safe-area-inset-left, 0px)');
        root.style.setProperty('--safe-area-inset-right', 'env(safe-area-inset-right, 0px)');
    }

    optimizeTouchTargets() {
        // Ensure all interactive elements meet minimum touch target size
        const interactiveElements = document.querySelectorAll('button, a, input, select, textarea');
        
        interactiveElements.forEach(element => {
            const rect = element.getBoundingClientRect();
            if (rect.width < 44 || rect.height < 44) {
                element.style.minWidth = '44px';
                element.style.minHeight = '44px';
                element.style.padding = '12px';
            }
        });
    }

    // Smooth Scrolling
    setupSmoothScrolling() {
        // Add smooth scrolling to all internal links
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a[href^="#"]');
            if (link) {
                e.preventDefault();
                const targetId = link.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    }

    // Touch Feedback
    setupTouchFeedback() {
        // Add touch feedback to interactive elements
        const interactiveElements = document.querySelectorAll('button, .card, .card-button');
        
        interactiveElements.forEach(element => {
            element.addEventListener('touchstart', () => {
                element.classList.add('touch-active');
            }, { passive: true });
            
            element.addEventListener('touchend', () => {
                setTimeout(() => {
                    element.classList.remove('touch-active');
                }, 150);
            }, { passive: true });
            
            element.addEventListener('touchcancel', () => {
                element.classList.remove('touch-active');
            }, { passive: true });
        });
    }

    // Notification System
    showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `mobile-notification notification-${type}`;
        notification.innerHTML = `
            <div class="notification-content">
                <span class="notification-icon">${this.getNotificationIcon(type)}</span>
                <span class="notification-message">${message}</span>
            </div>
        `;

        // Add styles
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            right: 20px;
            background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 12px;
            animation: slideInDown 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        `;

        document.body.appendChild(notification);

        // Auto remove after 3 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.style.animation = 'slideOutUp 0.3s ease';
                setTimeout(() => notification.remove(), 300);
            }
        }, 3000);
    }

    getNotificationIcon(type) {
        const icons = {
            success: 'check-circle',
            error: 'exclamation-circle',
            info: 'info-circle',
            warning: 'exclamation-triangle'
        };
        return icons[type] || icons.info;
    }
}

// Initialize mobile features when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    new MobileFeatures();
});

// Also initialize immediately if DOM is already loaded
if (document.readyState === 'loading') {
    // Still loading, wait for DOMContentLoaded
} else {
    // DOM already loaded, initialize immediately
    new MobileFeatures();
}
