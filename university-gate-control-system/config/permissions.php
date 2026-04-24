<?php
/**
 * Role Permission Matrix
 * Defines permissions for each role
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    die('Direct access not permitted');
}

// Permission definitions
define('PERMISSIONS', [
    'gate_scan' => 'Access gate scanning interface',
    'access_control' => 'Allow/deny entry and exit',
    'view_logs' => 'View access and system logs',
    'report_incidents' => 'Report security incidents',
    'manage_users' => 'Manage user accounts',
    'manage_settings' => 'Manage system settings',
    'manage_materials' => 'Manage material permissions',
    'approve_materials' => 'Approve material movements',
    'manage_visitors' => 'Register and manage visitors',
    'manage_gates' => 'Manage gate configurations',
    'manage_incidents' => 'Manage and resolve incidents',
    'view_reports' => 'View system reports',
    'export_reports' => 'Export reports to PDF/CSV',
    'manage_roles' => 'Assign and manage roles',
    'audit_access' => 'Access audit trails',
    'escalate_incidents' => 'Escalate incidents to higher authority'
]);

// Role permission mapping
$rolePermissions = [
    'guard' => [
        'gate_scan' => true,
        'access_control' => true,
        'view_logs' => true,
        'report_incidents' => true,
        'manage_users' => false,
        'manage_settings' => false,
        'manage_materials' => false,
        'approve_materials' => false,
        'manage_visitors' => false,
        'manage_gates' => false,
        'manage_incidents' => false,
        'view_reports' => false,
        'export_reports' => false,
        'manage_roles' => false,
        'audit_access' => false,
        'escalate_incidents' => false
    ],
    'admin' => [
        'gate_scan' => true,
        'access_control' => true,
        'view_logs' => true,
        'report_incidents' => true,
        'manage_users' => true,
        'manage_settings' => false,
        'manage_materials' => true,
        'approve_materials' => true,
        'manage_visitors' => true,
        'manage_gates' => true,
        'manage_incidents' => true,
        'view_reports' => true,
        'export_reports' => true,
        'manage_roles' => false,
        'audit_access' => false,
        'escalate_incidents' => true
    ],
    'main_admin' => [
        'gate_scan' => true,
        'access_control' => true,
        'view_logs' => true,
        'report_incidents' => true,
        'manage_users' => true,
        'manage_settings' => true,
        'manage_materials' => true,
        'approve_materials' => true,
        'manage_visitors' => true,
        'manage_gates' => true,
        'manage_incidents' => true,
        'view_reports' => true,
        'export_reports' => true,
        'manage_roles' => true,
        'audit_access' => true,
        'escalate_incidents' => true
    ],
    'student' => [
        'gate_scan' => false,
        'access_control' => false,
        'view_logs' => false,
        'report_incidents' => false,
        'manage_users' => false,
        'manage_settings' => false,
        'manage_materials' => false,
        'approve_materials' => false,
        'manage_visitors' => false,
        'manage_gates' => false,
        'manage_incidents' => false,
        'view_reports' => false,
        'export_reports' => false,
        'manage_roles' => false,
        'audit_access' => false,
        'escalate_incidents' => false
    ],
    'staff' => [
        'gate_scan' => false,
        'access_control' => false,
        'view_logs' => false,
        'report_incidents' => false,
        'manage_users' => false,
        'manage_settings' => false,
        'manage_materials' => false,
        'approve_materials' => false,
        'manage_visitors' => false,
        'manage_gates' => false,
        'manage_incidents' => false,
        'view_reports' => false,
        'export_reports' => false,
        'manage_roles' => false,
        'audit_access' => false,
        'escalate_incidents' => false
    ]
];

/**
 * Check if current user has specific permission
 */
function hasPermission($permission) {
    global $rolePermissions;
    
    $userRole = getCurrentUserRole();
    
    if (!$userRole || !isset($rolePermissions[$userRole])) {
        return false;
    }
    
    return isset($rolePermissions[$userRole][$permission]) && 
           $rolePermissions[$userRole][$permission] === true;
}

/**
 * Check multiple permissions
 */
function hasAnyPermission($permissions) {
    foreach ($permissions as $permission) {
        if (hasPermission($permission)) {
            return true;
        }
    }
    return false;
}

/**
 * Require specific permission to access page
 */
function requirePermission($permission) {
    requireLogin();
    
    if (!hasPermission($permission)) {
        $_SESSION['error_message'] = 'You do not have permission to access this page.';
        header('Location: ' . getDashboardUrl());
        exit;
    }
}

/**
 * Get all permissions for current user's role
 */
function getUserPermissions() {
    global $rolePermissions;
    
    $userRole = getCurrentUserRole();
    
    if (!$userRole || !isset($rolePermissions[$userRole])) {
        return [];
    }
    
    $permissions = [];
    foreach ($rolePermissions[$userRole] as $perm => $allowed) {
        if ($allowed) {
            $permissions[] = $perm;
        }
    }
    
    return $permissions;
}

/**
 * Get permission description
 */
function getPermissionDescription($permission) {
    return PERMISSIONS[$permission] ?? 'No description available';
}

/**
 * Check if user can access module
 */
function canAccessModule($module) {
    $modulePermissions = [
        'gate' => ['gate_scan', 'access_control'],
        'users' => ['manage_users'],
        'materials' => ['manage_materials', 'approve_materials'],
        'visitors' => ['manage_visitors'],
        'incidents' => ['report_incidents', 'manage_incidents'],
        'reports' => ['view_reports'],
        'logs' => ['view_logs'],
        'settings' => ['manage_settings'],
        'profile' => [] // All users can access their profile
    ];
    
    if (!isset($modulePermissions[$module])) {
        return false;
    }
    
    $requiredPerms = $modulePermissions[$module];
    
    // If no specific permissions required, allow access
    if (empty($requiredPerms)) {
        return true;
    }
    
    return hasAnyPermission($requiredPerms);
}
