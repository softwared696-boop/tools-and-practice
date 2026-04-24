<?php
/**
 * Role Permission Matrix
 * Define permissions for each role
 */

// Role permissions configuration
$rolePermissions = [
    'guard' => [
        'gate_scan' => true,
        'view_logs' => true,
        'view_own_logs' => true,
        'report_incident' => true,
        'manage_visitors' => true,
        'material_inspection' => true,
        'user_management' => false,
        'admin_access' => false,
        'system_settings' => false
    ],
    'admin' => [
        'gate_scan' => true,
        'view_logs' => true,
        'view_all_logs' => true,
        'generate_reports' => true,
        'manage_incidents' => true,
        'user_management' => true,
        'manage_users' => true,
        'manage_students' => true,
        'manage_staff' => true,
        'admin_access' => true,
        'system_settings' => false,
        'audit_access' => false
    ],
    'main_admin' => [
        'gate_scan' => true,
        'view_logs' => true,
        'view_all_logs' => true,
        'generate_reports' => true,
        'manage_incidents' => true,
        'user_management' => true,
        'manage_users' => true,
        'manage_students' => true,
        'manage_staff' => true,
        'admin_access' => true,
        'system_settings' => true,
        'admin_management' => true,
        'audit_access' => true,
        'full_access' => true
    ],
    'student' => [
        'view_own_logs' => true,
        'request_material_permission' => true,
        'view_profile' => true,
        'edit_profile' => true,
        'gate_scan' => false,
        'admin_access' => false
    ],
    'staff' => [
        'view_own_logs' => true,
        'request_material_permission' => true,
        'approve_materials' => true,
        'view_profile' => true,
        'edit_profile' => true,
        'gate_scan' => false,
        'admin_access' => false
    ]
];

// Check if user has permission
function hasPermission($permission) {
    global $rolePermissions;
    
    $userType = getCurrentUserType();
    
    if (!$userType || !isset($rolePermissions[$userType])) {
        return false;
    }
    
    return $rolePermissions[$userType][$permission] ?? false;
}

// Check multiple permissions (all must be true)
function hasAllPermissions($permissions) {
    foreach ($permissions as $permission) {
        if (!hasPermission($permission)) {
            return false;
        }
    }
    return true;
}

// Check if user has any of the permissions
function hasAnyPermission($permissions) {
    foreach ($permissions as $permission) {
        if (hasPermission($permission)) {
            return true;
        }
    }
    return false;
}

// Get all permissions for current user
function getUserPermissions() {
    global $rolePermissions;
    
    $userType = getCurrentUserType();
    
    if (!$userType || !isset($rolePermissions[$userType])) {
        return [];
    }
    
    return $rolePermissions[$userType];
}

// Check if user can access module
function canAccessModule($module) {
    $modulePermissions = [
        'gate' => ['gate_scan'],
        'users' => ['user_management'],
        'visitors' => ['manage_visitors'],
        'materials' => ['material_inspection', 'approve_materials'],
        'incidents' => ['report_incident', 'manage_incidents'],
        'reports' => ['generate_reports', 'view_all_logs'],
        'logs' => ['view_logs', 'view_all_logs'],
        'settings' => ['system_settings'],
        'profile' => ['view_profile']
    ];
    
    if (!isset($modulePermissions[$module])) {
        return false;
    }
    
    return hasAnyPermission($modulePermissions[$module]);
}
?>
