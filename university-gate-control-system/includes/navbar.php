<?php
/**
 * Top Navigation Bar Include File
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    die('Direct access not permitted');
}

$userRole = getCurrentUserRole();
$currentUser = getCurrentUser();
$unreadNotifications = dbCount('notifications', 'user_id = :user_id AND is_read = 0', ['user_id' => getCurrentUserId()]);
?>

<header class="topbar" id="topbar">
    <div class="topbar-left">
        <button class="menu-toggle" id="menuToggle" aria-label="Toggle menu">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
        
        <div class="search-box">
            <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="11" cy="11" r="8"/>
                <path d="m21 21-4.35-4.35"/>
            </svg>
            <input type="text" class="search-input" placeholder="Search..." id="globalSearch">
        </div>
    </div>
    
    <div class="topbar-right">
        <!-- Theme Toggle -->
        <button class="topbar-btn theme-toggle" id="themeToggle" aria-label="Toggle theme">
            <svg class="icon icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="5"/>
                <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
            </svg>
            <svg class="icon icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
            </svg>
        </button>
        
        <!-- Notifications -->
        <div class="dropdown notification-dropdown">
            <button class="topbar-btn notification-btn" id="notificationBtn" aria-label="Notifications">
                <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
                <?php if ($unreadNotifications > 0): ?>
                <span class="badge"><?php echo $unreadNotifications > 99 ? '99+' : $unreadNotifications; ?></span>
                <?php endif; ?>
            </button>
            <div class="dropdown-menu" id="notificationDropdown">
                <div class="dropdown-header">
                    <h4>Notifications</h4>
                    <a href="<?php echo BASE_URL; ?>/modules/profile/notifications.php">Mark all as read</a>
                </div>
                <div class="dropdown-content" id="notificationList">
                    <!-- Notifications will be loaded here via AJAX -->
                    <div class="empty-state">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                            <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                        </svg>
                        <p>No new notifications</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- User Menu -->
        <div class="dropdown user-dropdown">
            <button class="user-btn" id="userBtn">
                <img src="<?php echo !empty($currentUser['profile_photo']) ? UPLOADS_URL . '/profile-photos/' . escape($currentUser['profile_photo']) : IMAGES_URL . '/avatars/default.png'; ?>" 
                     alt="Profile" class="user-avatar">
                <span class="user-name"><?php echo escape(explode(' ', $currentUser['full_name'])[0]); ?></span>
                <svg class="icon icon-chevron" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="6 9 12 15 18 9"/>
                </svg>
            </button>
            <div class="dropdown-menu">
                <div class="user-info">
                    <img src="<?php echo !empty($currentUser['profile_photo']) ? UPLOADS_URL . '/profile-photos/' . escape($currentUser['profile_photo']) : IMAGES_URL . '/avatars/default.png'; ?>" 
                         alt="Profile" class="user-avatar-large">
                    <div>
                        <p class="user-fullname"><?php echo escape($currentUser['full_name']); ?></p>
                        <p class="user-email"><?php echo escape($currentUser['email']); ?></p>
                        <span class="badge badge-primary"><?php echo ucwords(str_replace('_', ' ', $userRole)); ?></span>
                    </div>
                </div>
                <div class="dropdown-divider"></div>
                <a href="<?php echo BASE_URL; ?>/modules/profile/view.php" class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                        <circle cx="12" cy="7" r="4"/>
                    </svg>
                    My Profile
                </a>
                <a href="<?php echo BASE_URL; ?>/modules/profile/edit.php" class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                    Edit Profile
                </a>
                <a href="<?php echo BASE_URL; ?>/modules/profile/change-password.php" class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                        <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                    Change Password
                </a>
                <a href="<?php echo BASE_URL; ?>/modules/profile/security.php" class="dropdown-item">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    Security Settings
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?php echo BASE_URL; ?>/logout.php" class="dropdown-item text-danger">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                        <polyline points="16 17 21 12 16 7"/>
                        <line x1="21" y1="12" x2="9" y2="12"/>
                    </svg>
                    Logout
                </a>
            </div>
        </div>
    </div>
</header>

<script>
// Toggle notification dropdown
document.getElementById('notificationBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    var dropdown = document.getElementById('notificationDropdown');
    dropdown.classList.toggle('show');
    
    // Load notifications if empty
    if (document.getElementById('notificationList').children.length <= 1) {
        loadNotifications();
    }
});

// Toggle user dropdown
document.getElementById('userBtn').addEventListener('click', function(e) {
    e.stopPropagation();
    var dropdown = this.nextElementSibling;
    dropdown.classList.toggle('show');
});

// Close dropdowns when clicking outside
document.addEventListener('click', function() {
    document.querySelectorAll('.dropdown-menu.show').forEach(function(menu) {
        menu.classList.remove('show');
    });
});

// Prevent dropdown close when clicking inside
document.querySelectorAll('.dropdown-menu').forEach(function(menu) {
    menu.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});

// Load notifications via AJAX
function loadNotifications() {
    fetch('<?php echo BASE_URL; ?>/ajax/fetch-notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.success && data.notifications.length > 0) {
                var html = '';
                data.notifications.forEach(function(notif) {
                    html += '<a href="' + (notif.action_url || '#') + '" class="notification-item ' + (notif.is_read ? '' : 'unread') + '">' +
                        '<div class="notification-icon notification-' + notif.type + '">' +
                            getNotificationIcon(notif.type) +
                        '</div>' +
                        '<div class="notification-content">' +
                            '<p class="notification-title">' + escapeHtml(notif.title) + '</p>' +
                            '<p class="notification-message">' + escapeHtml(notif.message) + '</p>' +
                            '<span class="notification-time">' + timeAgo(notif.created_at) + '</span>' +
                        '</div>' +
                    '</a>';
                });
                document.getElementById('notificationList').innerHTML = html;
            }
        })
        .catch(error => console.error('Error loading notifications:', error));
}

function getNotificationIcon(type) {
    var icons = {
        'info': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="16" x2="12" y2="12"/><line x1="12" y1="8" x2="12.01" y2="8"/></svg>',
        'success': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>',
        'warning': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>',
        'error': '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/></svg>'
    };
    return icons[type] || icons['info'];
}

function escapeHtml(text) {
    var div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function timeAgo(dateString) {
    var date = new Date(dateString);
    var now = new Date();
    var seconds = Math.floor((now - date) / 1000);
    
    if (seconds < 60) return 'Just now';
    if (seconds < 3600) return Math.floor(seconds / 60) + ' min ago';
    if (seconds < 86400) return Math.floor(seconds / 3600) + ' hours ago';
    return Math.floor(seconds / 86400) + ' days ago';
}
</script>
