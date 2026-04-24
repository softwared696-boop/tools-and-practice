<?php
/**
 * Global Constants
 */

// Define if not already defined
if (!defined('APP_NAME')) {
    define('APP_NAME', 'University Gate Control System');
}

// User Types
define('USER_TYPE_STUDENT', 'student');
define('USER_TYPE_STAFF', 'staff');
define('USER_TYPE_VISITOR', 'visitor');
define('USER_TYPE_GUARD', 'guard');
define('USER_TYPE_ADMIN', 'admin');
define('USER_TYPE_MAIN_ADMIN', 'main_admin');

// Access Decision
define('ACCESS_ALLOWED', 'allowed');
define('ACCESS_DENIED', 'denied');
define('ACCESS_PENDING', 'pending');

// Access Types
define('ACCESS_ENTRY', 'entry');
define('ACCESS_EXIT', 'exit');

// Material Categories
define('MATERIAL_LAPTOP', 'laptop');
define('MATERIAL_COMPUTER', 'computer');
define('MATERIAL_TABLET', 'tablet');
define('MATERIAL_EQUIPMENT', 'equipment');
define('MATERIAL_PERSONAL', 'personal');
define('MATERIAL_UNIVERSITY_ASSET', 'university_asset');

// Incident Severity
define('SEVERITY_LOW', 'low');
define('SEVERITY_MEDIUM', 'medium');
define('SEVERITY_HIGH', 'high');
define('SEVERITY_CRITICAL', 'critical');

// Incident Status
define('INCIDENT_REPORTED', 'reported');
define('INCIDENT_UNDER_REVIEW', 'under_review');
define('INCIDENT_ESCALATED', 'escalated');
define('INCIDENT_RESOLVED', 'resolved');
define('INCIDENT_CLOSED', 'closed');

// Visitor Status
define('VISITOR_PENDING', 'pending');
define('VISITOR_APPROVED', 'approved');
define('VISITOR_REJECTED', 'rejected');
define('VISITOR_CHECKED_IN', 'checked_in');
define('VISITOR_CHECKED_OUT', 'checked_out');

// Notification Types
define('NOTIF_INFO', 'info');
define('NOTIF_WARNING', 'warning');
define('NOTIF_ERROR', 'error');
define('NOTIF_SUCCESS', 'success');
define('NOTIF_ALERT', 'alert');

// Gate Status
define('GATE_ACTIVE', 'active');
define('GATE_INACTIVE', 'inactive');
define('GATE_MAINTENANCE', 'maintenance');

// Date Formats
define('DATE_FORMAT_DISPLAY', 'M d, Y');
define('TIME_FORMAT_DISPLAY', 'h:i A');
define('DATETIME_FORMAT_DISPLAY', 'M d, Y h:i A');
define('DATE_FORMAT_DB', 'Y-m-d');
define('DATETIME_FORMAT_DB', 'Y-m-d H:i:s');

// Status Colors (for UI)
$statusColors = [
    'active' => '#10b981',
    'inactive' => '#6b7280',
    'suspended' => '#ef4444',
    'allowed' => '#10b981',
    'denied' => '#ef4444',
    'pending' => '#f59e0b',
    'approved' => '#10b981',
    'rejected' => '#ef4444',
    'low' => '#10b981',
    'medium' => '#f59e0b',
    'high' => '#ef4444',
    'critical' => '#dc2626'
];

function getStatusColor($status) {
    global $statusColors;
    return $statusColors[$status] ?? '#6b7280';
}

// Gender Options
$genderOptions = ['male', 'female', 'other'];

// Employment Types
$employmentTypes = ['full_time', 'part_time', 'contract', 'temporary'];

// ID Types
$idTypes = ['national_id', 'passport', 'drivers_license', 'other'];

// Permission Types
$permissionTypes = ['one_time', 'recurring', 'permanent'];
?>
