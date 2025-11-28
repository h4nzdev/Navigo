<?php
/**
 * Reusable Warning Panel Component for Navigo Project
 * Displays popup warnings for login errors and other notifications
 */
?>

<!-- Warning Panel Overlay -->
<div class="warning-panel-overlay" id="warningPanelOverlay" style="display: none;">
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
</div>
