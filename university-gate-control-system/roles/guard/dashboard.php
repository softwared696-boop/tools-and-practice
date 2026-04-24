<?php
/**
 * Gate Officer Dashboard
 * Primary interface for security guards at gates
 */

require_once '../../config/config.php';
require_once '../../config/session.php';
require_once '../../config/db.php';

// Require login and guard role
requireRole(['guard', 'admin', 'main_admin']);

$user = getUserData();

// Get today's statistics
$today = date('Y-m-d');

// Total entries today
$totalEntriesQuery = "SELECT COUNT(*) as count FROM access_logs 
                      WHERE DATE(scan_timestamp) = :today AND access_type = 'entry'";
$totalEntries = dbFetchOne($totalEntriesQuery, ['today' => $today])['count'];

// Total exits today
$totalExitsQuery = "SELECT COUNT(*) as count FROM access_logs 
                    WHERE DATE(scan_timestamp) = :today AND access_type = 'exit'";
$totalExits = dbFetchOne($totalExitsQuery, ['today' => $today])['count'];

// Denied entries today
$deniedQuery = "SELECT COUNT(*) as count FROM access_logs 
                WHERE DATE(scan_timestamp) = :today AND decision = 'denied'";
$deniedCount = dbFetchOne($deniedQuery, ['today' => $today])['count'];

// Visitors today
$visitorsQuery = "SELECT COUNT(*) as count FROM visitors 
                  WHERE DATE(created_at) = :today";
$visitorsCount = dbFetchOne($visitorsQuery, ['today' => $today])['count'];

// Recent activity
$recentActivityQuery = "SELECT al.*, u.first_name, u.last_name, u.user_type, g.gate_name
                        FROM access_logs al
                        LEFT JOIN users u ON al.user_id = u.id
                        LEFT JOIN gates g ON al.gate_id = g.id
                        ORDER BY al.scan_timestamp DESC
                        LIMIT 10";
$recentActivity = dbFetchAll($recentActivityQuery);

// Get active gates
$gatesQuery = "SELECT * FROM gates WHERE status = 'active' ORDER BY gate_name";
$gates = dbFetchAll($gatesQuery);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Officer Dashboard - University Gate Control</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    <link rel="stylesheet" href="../../assets/css/dashboard.css">
    <link rel="stylesheet" href="../../assets/css/forms.css">
</head>
<body>
    <div class="dashboard-wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <div style="width: 40px; height: 40px; background: var(--primary-color); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold;">UG</div>
                <h1 class="sidebar-title">Gate Control</h1>
            </div>
            
            <ul class="sidebar-menu">
                <li class="sidebar-menu-item">
                    <a href="dashboard.php" class="sidebar-menu-link active">
                        <span class="sidebar-menu-icon">📊</span>
                        <span class="sidebar-menu-text">Dashboard</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="../../modules/gate/scan.php" class="sidebar-menu-link">
                        <span class="sidebar-menu-icon">🔍</span>
                        <span class="sidebar-menu-text">Gate Scan</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="../../modules/gate/gate-logs.php" class="sidebar-menu-link">
                        <span class="sidebar-menu-icon">📋</span>
                        <span class="sidebar-menu-text">Access Logs</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="../../modules/visitors/register.php" class="sidebar-menu-link">
                        <span class="sidebar-menu-icon">👤</span>
                        <span class="sidebar-menu-text">Visitors</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="../../modules/materials/materials.php" class="sidebar-menu-link">
                        <span class="sidebar-menu-icon">📦</span>
                        <span class="sidebar-menu-text">Materials</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="../../modules/incidents/report-incident.php" class="sidebar-menu-link">
                        <span class="sidebar-menu-icon">⚠️</span>
                        <span class="sidebar-menu-text">Report Incident</span>
                    </a>
                </li>
                <li class="sidebar-menu-item">
                    <a href="../../modules/profile/view.php" class="sidebar-menu-link">
                        <span class="sidebar-menu-icon">👤</span>
                        <span class="sidebar-menu-text">Profile</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="topbar-left">
                    <button class="menu-toggle" onclick="toggleSidebar()">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    <div class="search-box">
                        <span class="search-icon">🔍</span>
                        <input type="text" class="search-input" placeholder="Search..." id="globalSearch">
                    </div>
                </div>
                
                <div class="topbar-right">
                    <button class="topbar-action" onclick="toggleTheme()" title="Toggle Theme">
                        <span id="theme-icon">🌙</span>
                    </button>
                    
                    <button class="topbar-action notification-btn" title="Notifications">
                        🔔
                        <span class="badge badge-danger" id="notification-badge" style="display: none;">0</span>
                    </button>
                    
                    <div class="user-menu" onclick="toggleUserDropdown()">
                        <img src="../../assets/images/avatars/default.png" alt="User" class="user-avatar" onerror="this.src='../../assets/images/avatars/default.png'">
                        <span class="user-name"><?php echo htmlspecialchars($user['name']); ?></span>
                        <span>▼</span>
                    </div>
                    
                    <div class="dropdown" id="userDropdown" style="display: none;">
                        <a href="../../modules/profile/view.php">Profile</a>
                        <a href="../../modules/profile/edit.php">Settings</a>
                        <a href="../../logout.php" style="color: var(--danger-color);">Logout</a>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <div class="page-content">
                <div class="page-header">
                    <h1 class="page-title">Gate Officer Dashboard</h1>
                    <p class="page-subtitle">Welcome back, <?php echo htmlspecialchars($user['name']); ?>! Here's what's happening today.</p>
                </div>

                <!-- Stats Grid -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon primary">🚶</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo number_format($totalEntries); ?></div>
                            <div class="stat-label">Total Entries Today</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon success">🚪</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo number_format($totalExits); ?></div>
                            <div class="stat-label">Total Exits Today</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon danger">❌</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo number_format($deniedCount); ?></div>
                            <div class="stat-label">Denied Access</div>
                        </div>
                    </div>
                    
                    <div class="stat-card">
                        <div class="stat-icon warning">👥</div>
                        <div class="stat-content">
                            <div class="stat-value"><?php echo number_format($visitorsCount); ?></div>
                            <div class="stat-label">Visitors Registered</div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions & Activity -->
                <div class="dashboard-grid">
                    <!-- Recent Activity -->
                    <div class="activity-feed">
                        <div class="activity-header">
                            <h3>Recent Activity</h3>
                            <a href="../../modules/gate/gate-logs.php" class="btn btn-outline btn-small">View All</a>
                        </div>
                        
                        <ul class="activity-list">
                            <?php if (empty($recentActivity)): ?>
                                <li class="activity-item">
                                    <div class="activity-content">
                                        <p class="activity-title">No recent activity</p>
                                    </div>
                                </li>
                            <?php else: ?>
                                <?php foreach ($recentActivity as $activity): ?>
                                    <li class="activity-item">
                                        <div class="activity-avatar">
                                            <?php echo strtoupper(substr($activity['first_name'] ?? 'U', 0, 1)); ?>
                                        </div>
                                        <div class="activity-content">
                                            <p class="activity-title">
                                                <?php echo htmlspecialchars(($activity['first_name'] ?? 'Unknown') . ' ' . ($activity['last_name'] ?? '')); ?>
                                                <span class="badge badge-<?php echo $activity['decision'] === 'allowed' ? 'success' : 'danger'; ?>">
                                                    <?php echo ucfirst($activity['decision']); ?>
                                                </span>
                                            </p>
                                            <p class="activity-time">
                                                <?php echo $activity['access_type'] === 'entry' ? 'Entered' : 'Exited'; ?> 
                                                via <?php echo htmlspecialchars($activity['gate_name'] ?? 'Gate'); ?> • 
                                                <?php echo date('M d, h:i A', strtotime($activity['scan_timestamp'])); ?>
                                            </p>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <!-- Quick Actions -->
                    <div class="quick-actions">
                        <h3>Quick Actions</h3>
                        <div class="quick-actions-grid">
                            <a href="../../modules/gate/scan.php" class="quick-action-btn">
                                <span class="quick-action-icon">🔍</span>
                                <span class="quick-action-label">Scan ID/QR</span>
                            </a>
                            
                            <a href="../../modules/visitors/register.php" class="quick-action-btn">
                                <span class="quick-action-icon">📝</span>
                                <span class="quick-action-label">Register Visitor</span>
                            </a>
                            
                            <a href="../../modules/materials/register-material.php" class="quick-action-btn">
                                <span class="quick-action-icon">📦</span>
                                <span class="quick-action-label">Log Material</span>
                            </a>
                            
                            <a href="../../modules/incidents/report-incident.php" class="quick-action-btn">
                                <span class="quick-action-icon">⚠️</span>
                                <span class="quick-action-label">Report Incident</span>
                            </a>
                            
                            <a href="../../modules/gate/inspection.php" class="quick-action-btn">
                                <span class="quick-action-icon">✓</span>
                                <span class="quick-action-label">Security Check</span>
                            </a>
                            
                            <a href="../../modules/reports/daily-report.php" class="quick-action-btn">
                                <span class="quick-action-icon">📊</span>
                                <span class="quick-action-label">Daily Report</span>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Active Gates -->
                <div class="card mt-2">
                    <div class="card-header">
                        <h3 class="card-title">Active Gates</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Gate Name</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Material Scanner</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($gates as $gate): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($gate['gate_name']); ?></td>
                                            <td><?php echo htmlspecialchars($gate['location']); ?></td>
                                            <td>
                                                <span class="badge badge-success"><?php echo ucfirst($gate['status']); ?></span>
                                            </td>
                                            <td>
                                                <?php if ($gate['has_material_scanner']): ?>
                                                    <span class="badge badge-primary">Yes</span>
                                                <?php else: ?>
                                                    <span class="badge badge-secondary">No</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="../../assets/js/app.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
        }
        
        function toggleUserDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.user-menu') && !e.target.closest('#userDropdown')) {
                document.getElementById('userDropdown').style.display = 'none';
            }
        });
    </script>
</body>
</html>
