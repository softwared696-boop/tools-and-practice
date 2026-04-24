<?php
/**
 * Header Include File
 * Contains HTML head, meta tags, and CSS includes
 */

// Prevent direct access
if (!defined('APP_NAME')) {
    require_once __DIR__ . '/../config/config.php';
}

// Initialize app if not already done
if (!function_exists('initSession')) {
    require_once __DIR__ . '/../config/session.php';
}

initApp();

$pageTitle = $pageTitle ?? APP_NAME;
$currentPage = $currentPage ?? '';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="University Gate Control System - Secure campus access management">
    <meta name="robots" content="noindex, nofollow">
    
    <title><?php echo escape($pageTitle); ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="<?php echo IMAGES_URL; ?>/logo/favicon.ico" type="image/x-icon">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/style.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/dashboard.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/forms.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/tables.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/cards.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/buttons.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/modals.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/animations.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/dark-theme.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/responsive.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/print.css" media="print">
    
    <?php if (isset($additionalCSS) && is_array($additionalCSS)): ?>
        <?php foreach ($additionalCSS as $css): ?>
            <link rel="stylesheet" href="<?php echo escape($css); ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body class="<?php echo $bodyClass ?? ''; ?>">
    <!-- Preloader -->
    <div id="preloader" class="preloader">
        <div class="spinner"></div>
    </div>
    
    <!-- Theme Toggle Button (Mobile) -->
    <button class="theme-toggle-mobile" id="themeToggleMobile" aria-label="Toggle theme">
        <svg class="icon icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="5"/>
            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
        </svg>
        <svg class="icon icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
        </svg>
    </button>
