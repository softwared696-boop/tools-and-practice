<?php
/**
 * Sidebar Navigation Include File
 * Dynamic sidebar menu based on user role
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    die('Direct access not permitted');
}

require_once __DIR__ . '/../config/permissions.php';

$userRole = getCurrentUserRole();
$currentUser = getCurrentUser();
?>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span class="logo-text">Gate Control</span>
        </div>
        <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="12" x2="21" y2="12"/>
                <line x1="3" y1="6" x2="21" y2="6"/>
                <line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </div>
    
    <nav class="sidebar-nav">
        <ul class="nav-menu">
            <?php if (hasPermission($userRole, 'dashboard')): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/roles/<?php echo $userRole; ?>/dashboard.php" class="nav-link <?php echo $currentPage === 'dashboard' ? 'active' : ''; ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="7" height="7"/>
                        <rect x="14" y="3" width="7" height="7"/>
                        <rect x="14" y="14" width="7" height="7"/>
                        <rect x="3" y="14" width="7" height="7"/>
                    </svg>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (hasPermission($userRole, 'gate_scan')): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/modules/gate/scan.php" class="nav-link <?php echo $currentPage === 'gate_scan' ? 'active' : ''; ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <line x1="9" y1="9" x2="9.01" y2="9"/>
                        <line x1="15" y1="9" x2="15.01" y2="9"/>
                        <line x1="9" y1="15" x2="15" y2="15"/>
                    </svg>
                    <span class="nav-text">Gate Scan</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (hasPermission($userRole, 'users') || hasPermission($userRole, 'students') || hasPermission($userRole, 'staff')): ?>
            <li class="nav-item has-submenu">
                <a href="#" class="nav-link" onclick="toggleSubmenu(event, 'usersSubmenu')">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                    <span class="nav-text">Users</span>
                    <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </a>
                <ul class="submenu" id="usersSubmenu">
                    <?php if (hasPermission($userRole, 'users')): ?>
                    <li><a href="<?php echo BASE_URL; ?>/modules/users/students.php">Students</a></li>
                    <li><a href="<?php echo BASE_URL; ?>/modules/users/staff.php">Staff</a></li>
                    <?php endif; ?>
                    <?php if (hasPermission($userRole, 'visitors')): ?>
                    <li><a href="<?php echo BASE_URL; ?>/modules/visitors/visitor-list.php">Visitors</a></li>
                    <?php endif; ?>
                </ul>
            </li>
            <?php endif; ?>
            
            <?php if (hasPermission($userRole, 'materials')): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/modules/materials/materials.php" class="nav-link <?php echo $currentPage === 'materials' ? 'active' : ''; ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                        <line x1="12" y1="22.08" x2="12" y2="12"/>
                    </svg>
                    <span class="nav-text">Materials</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (hasPermission($userRole, 'access_logs') || hasPermission($userRole, 'logs')): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/modules/logs/access-logs.php" class="nav-link <?php echo $currentPage === 'logs' ? 'active' : ''; ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    <span class="nav-text">Logs</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (hasPermission($userRole, 'reports')): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/modules/reports/analytics.php" class="nav-link <?php echo $currentPage === 'reports' ? 'active' : ''; ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="18" y1="20" x2="18" y2="10"/>
                        <line x1="12" y1="20" x2="12" y2="4"/>
                        <line x1="6" y1="20" x2="6" y2="14"/>
                    </svg>
                    <span class="nav-text">Reports</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (hasPermission($userRole, 'incidents')): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/modules/incidents/incidents.php" class="nav-link <?php echo $currentPage === 'incidents' ? 'active' : ''; ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                        <line x1="12" y1="9" x2="12" y2="13"/>
                        <line x1="12" y1="17" x2="12.01" y2="17"/>
                    </svg>
                    <span class="nav-text">Incidents</span>
                </a>
            </li>
            <?php endif; ?>
            
            <?php if (hasPermission($userRole, 'settings')): ?>
            <li class="nav-item">
                <a href="<?php echo BASE_URL; ?>/modules/settings/general.php" class="nav-link <?php echo $currentPage === 'settings' ? 'active' : ''; ?>">
                    <svg class="nav-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="3"/>
                        <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                    </svg>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </nav>
    
    <div class="sidebar-footer">
        <a href="<?php echo BASE_URL; ?>/modules/profile/view.php" class="profile-link">
            <img src="<?php echo !empty($currentUser['profile_photo']) ? UPLOADS_URL . '/profile-photos/' . escape($currentUser['profile_photo']) : IMAGES_URL . '/avatars/default.png'; ?>" 
                 alt="Profile" class="profile-avatar">
            <div class="profile-info">
                <span class="profile-name"><?php echo escape($currentUser['full_name']); ?></span>
                <span class="profile-role"><?php echo ucwords(str_replace('_', ' ', $userRole)); ?></span>
            </div>
        </a>
    </div>
</aside>

<script>
function toggleSubmenu(event, submenuId) {
    event.preventDefault();
    var submenu = document.getElementById(submenuId);
    var parent = event.currentTarget.parentElement;
    
    if (submenu.classList.contains('show')) {
        submenu.classList.remove('show');
        parent.classList.remove('open');
    } else {
        // Close other submenus
        document.querySelectorAll('.submenu.show').forEach(function(sm) {
            sm.classList.remove('show');
            sm.parentElement.classList.remove('open');
        });
        
        submenu.classList.add('show');
        parent.classList.add('open');
    }
}
</script>
