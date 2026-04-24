<?php
/**
 * Footer Include File
 * Contains closing tags, JavaScript includes
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}
?>

    <!-- Main App Scripts -->
    <script src="<?php echo JS_URL; ?>/app.js"></script>
    <script src="<?php echo JS_URL; ?>/theme.js"></script>
    <script src="<?php echo JS_URL; ?>/validation.js"></script>
    <script src="<?php echo JS_URL; ?>/ajax.js"></script>
    <script src="<?php echo JS_URL; ?>/notifications.js"></script>
    
    <?php if (isset($additionalJS) && is_array($additionalJS)): ?>
        <?php foreach ($additionalJS as $js): ?>
            <script src="<?php echo escape($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Flash Messages -->
    <?php
    $flashMessage = getFlashMessage();
    if ($flashMessage):
    ?>
    <div class="toast-container" id="toastContainer">
        <div class="toast toast-<?php echo escape($flashMessage['type']); ?> show">
            <div class="toast-icon">
                <?php if ($flashMessage['type'] === 'success'): ?>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                        <polyline points="22 4 12 14.01 9 11.01"/>
                    </svg>
                <?php elseif ($flashMessage['type'] === 'error'): ?>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="15" y1="9" x2="9" y2="15"/>
                        <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                <?php elseif ($flashMessage['type'] === 'warning'): ?>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                <?php else: ?>
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"/>
                        <line x1="12" y1="16" x2="12" y2="12"/>
                        <line x1="12" y1="8" x2="12.01" y2="8"/>
                    </svg>
                <?php endif; ?>
            </div>
            <div class="toast-content">
                <p class="toast-message"><?php echo escape($flashMessage['message']); ?></p>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Alert Modal -->
    <div class="modal" id="alertModal">
        <div class="modal-overlay"></div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="alertModalTitle">Alert</h3>
                    <button class="modal-close" onclick="closeModal('alertModal')">&times;</button>
                </div>
                <div class="modal-body" id="alertModalBody">
                    <!-- Content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" onclick="closeModal('alertModal')">OK</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Confirm Modal -->
    <div class="modal" id="confirmModal">
        <div class="modal-overlay"></div>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="confirmModalTitle">Confirm</h3>
                    <button class="modal-close" onclick="closeModal('confirmModal')">&times;</button>
                </div>
                <div class="modal-body" id="confirmModalBody">
                    <!-- Content will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeModal('confirmModal')">Cancel</button>
                    <button class="btn btn-danger" id="confirmModalAction">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Hide preloader when page loads
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('preloader').classList.add('hidden');
            }, 500);
        });
        
        // Auto-hide toasts after 5 seconds
        setTimeout(function() {
            var toasts = document.querySelectorAll('.toast.show');
            toasts.forEach(function(toast) {
                toast.classList.remove('show');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            });
        }, 5000);
    </script>
</body>
</html>
