<?php
/**
 * Role Permission Matrix
 * Defines what each role can access and do
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/config.php';
}

/**
 * Permission definitions
 */
$permissions = [
    // Gate Officer / Guard
    ROLE_GUARD => [
        'dashboard' => ['view'],
        'gate_scan' => ['view', 'scan_entry', 'scan_exit', 'allow', 'deny'],
        'inspection' => ['view', 'perform'],
        'access_logs' => ['view_own_shift'],
        'materials' => ['view', 'scan'],
        'incidents' => ['report', 'view_own'],
        'profile' => ['view', 'edit', 'change_password'],
        'notifications' => ['view'],
        'theme' => ['switch']
    ],
    
    // Admin
    ROLE_ADMIN => [
        'dashboard' => ['view'],
        'users' => ['view', 'create', 'edit', 'delete'],
        'students' => ['view', 'create', 'edit', 'delete'],
        'staff' => ['view', 'create', 'edit', 'delete'],
        'visitors' => ['view', 'register', 'approve'],
        'gates' => ['view', 'manage'],
        'gate_scan' => ['view', 'scan_entry', 'scan_exit', 'allow', 'deny'],
        'access_logs' => ['view_all'],
        'materials' => ['view', 'register', 'approve', 'reject'],
        'material_logs' => ['view_all'],
        'incidents' => ['view', 'review', 'resolve'],
        'reports' => ['view', 'generate', 'export'],
        'logs' => ['view_access', 'view_system'],
        'profile' => ['view', 'edit', 'change_password'],
        'notifications' => ['view', 'send'],
        'theme' => ['switch']
    ],
    
    // Main Admin
    ROLE_MAIN_ADMIN => [
        'dashboard' => ['view'],
        'analytics' => ['view_all'],
        'users' => ['view', 'create', 'edit', 'delete'],
        'students' => ['view', 'create', 'edit', 'delete'],
        'staff' => ['view', 'create', 'edit', 'delete'],
        'visitors' => ['view', 'register', 'approve', 'delete'],
        'gates' => ['view', 'create', 'edit', 'delete', 'configure'],
        'gate_scan' => ['view', 'scan_entry', 'scan_exit', 'allow', 'deny'],
        'access_logs' => ['view_all', 'export'],
        'materials' => ['view', 'register', 'approve', 'reject', 'delete'],
        'material_logs' => ['view_all', 'export'],
        'incidents' => ['view', 'review', 'resolve', 'escalate', 'delete'],
        'escalations' => ['view', 'manage'],
        'reports' => ['view', 'generate', 'export', 'delete'],
        'analytics' => ['view', 'export'],
        'logs' => ['view_all', 'export', 'audit'],
        'audit_trail' => ['view', 'export'],
        'settings' => ['view', 'edit_all'],
        'roles' => ['view', 'create', 'edit', 'delete', 'assign'],
        'backup' => ['create', 'download'],
        'system_config' => ['view', 'edit'],
        'admin_management' => ['view', 'create', 'edit', 'delete'],
        'profile' => ['view', 'edit', 'change_password'],
        'notifications' => ['view', 'send', 'broadcast'],
        'theme' => ['switch']
    ],
    
    // Student
    ROLE_STUDENT => [
        'dashboard' => ['view'],
        'my_access' => ['view'],
        'my_materials' => ['view', 'request'],
        'permissions' => ['view'],
        'profile' => ['view', 'edit', 'change_password', 'change_photo'],
        'notifications' => ['view'],
        'theme' => ['switch']
    ],
    
    // Staff
    ROLE_STAFF => [
        'dashboard' => ['view'],
        'my_access' => ['view'],
        'my_materials' => ['view', 'request', 'approve_own_requests'],
        'recurring_equipment' => ['view', 'request', 'manage'],
        'approvals' => ['view', 'approve_others'],
        'profile' => ['view', 'edit', 'change_password', 'change_photo'],
        'notifications' => ['view'],
        'theme' => ['switch']
    ],
    
    // Visitor Registration Officer
    ROLE_VISITOR_OFFICER => [
        'dashboard' => ['view'],
        'visitors' => ['view', 'register', 'edit', 'delete'],
        'visitor_passes' => ['view', 'generate', 'print', 'extend'],
        'visitor_logs' => ['view'],
        'verify_visitor' => ['view', 'verify'],
        'profile' => ['view', 'edit', 'change_password'],
        'notifications' => ['view'],
        'theme' => ['switch']
    ]
];

/**
 * Check if role has permission
 */
function hasPermission($role, $module, $action = null) {
    global $permissions;
    
    if (!isset($permissions[$role])) {
        return false;
    }
    
    if (!isset($permissions[$role][$module])) {
        return false;
    }
    
    if ($action === null) {
        return true;
    }
    
    return in_array($action, $permissions[$role][$module]);
}

/**
 * Check if current user has permission
 */
function currentUserHasPermission($module, $action = null) {
    if (!isLoggedIn()) {
        return false;
    }
    
    $role = getCurrentUserRole();
    return hasPermission($role, $module, $action);
}

/**
 * Require permission
 */
function requirePermission($module, $action = null) {
    if (!currentUserHasPermission($module, $action)) {
        http_response_code(403);
        if (isAjaxRequest()) {
            die(json_encode(['success' => false, 'message' => 'Access denied']));
        }
        setFlashMessage('error', 'You do not have permission to access this resource');
        redirectTo(BASE_URL . '/404.php');
    }
}

/**
 * Get all permissions for a role
 */
function getRolePermissions($role) {
    global $permissions;
    return $permissions[$role] ?? [];
}

/**
 * Get available modules for a role
 */
function getAvailableModules($role) {
    return array_keys(getRolePermissions($role));
}

/**
 * Check if request is AJAX
 */
function isAjaxRequest() {
    return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}
