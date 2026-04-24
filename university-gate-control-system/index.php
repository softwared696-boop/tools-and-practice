<?php
/**
 * Landing Page - University Gate Control System
 * Professional, modern, and engaging homepage
 */

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/session.php';

// Redirect to dashboard if already logged in
if (isLoggedIn()) {
    $role = getCurrentUserRole();
    redirectTo(BASE_URL . '/roles/' . $role . '/dashboard.php');
}

$pageTitle = 'University Gate Control System - Secure Campus Access Management';
$currentPage = 'home';
?>
<!DOCTYPE html>
<html lang="en" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="description" content="Professional university gate security management platform with access control, visitor management, and material tracking.">
    <meta name="robots" content="index, follow">
    
    <title><?php echo escape($pageTitle); ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Stylesheets -->
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/style.css">
    <link rel="stylesheet" href="<?php echo CSS_URL; ?>/landing.css">
</head>
<body class="landing-page">
    <!-- Header/Navigation -->
    <header class="landing-header" id="landingHeader">
        <div class="container">
            <nav class="landing-nav">
                <div class="logo">
                    <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                        <polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                    <span class="logo-text">Gate Control</span>
                </div>
                
                <ul class="nav-links" id="navLinks">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
                
                <div class="nav-actions">
                    <button class="theme-toggle" id="headerThemeToggle" aria-label="Toggle theme">
                        <svg class="icon icon-sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42"/>
                        </svg>
                        <svg class="icon icon-moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none;">
                            <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                        </svg>
                    </button>
                    <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-primary">Login</a>
                </div>
                
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="3" y1="12" x2="21" y2="12"/>
                        <line x1="3" y1="6" x2="21" y2="6"/>
                        <line x1="3" y1="18" x2="21" y2="18"/>
                    </svg>
                </button>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="container">
            <div class="hero-content">
                <div class="hero-badge">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                    <span>Secure & Reliable</span>
                </div>
                <h1 class="hero-title">
                    Advanced <span class="text-gradient">Gate Control</span> for Modern Universities
                </h1>
                <p class="hero-description">
                    Comprehensive security management platform for campus access control, 
                    visitor management, and material tracking. Built for safety, designed for efficiency.
                </p>
                <div class="hero-actions">
                    <a href="<?php echo BASE_URL; ?>/login.php" class="btn btn-primary btn-lg">
                        Get Started
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"/>
                            <polyline points="12 5 19 12 12 19"/>
                        </svg>
                    </a>
                    <a href="#features" class="btn btn-outline btn-lg">Learn More</a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <span class="stat-number" data-count="50000">0</span>
                        <span class="stat-label">Daily Access Events</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="99">0</span>%
                        <span class="stat-label">Uptime Guarantee</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number" data-count="100">0</span>+
                        <span class="stat-label">Universities Trust Us</span>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="hero-image">
                    <div class="floating-card card-1">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                        <span>Student Entry</span>
                        <span class="stat-value">+2,450</span>
                    </div>
                    <div class="floating-card card-2">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <line x1="9" y1="9" x2="9.01" y2="9"/>
                            <line x1="15" y1="9" x2="15.01" y2="9"/>
                            <line x1="9" y1="15" x2="15" y2="15"/>
                        </svg>
                        <span>ID Verified</span>
                        <span class="stat-value">✓ Approved</span>
                    </div>
                    <div class="floating-card card-3">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                        <span>Security Active</span>
                        <span class="stat-value">24/7</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Features</span>
                <h2 class="section-title">Everything You Need for Campus Security</h2>
                <p class="section-description">
                    Comprehensive tools and features designed to streamline gate operations 
                    and enhance campus safety.
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon bg-primary">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="3" width="18" height="18" rx="2"/>
                            <line x1="9" y1="9" x2="9.01" y2="9"/>
                            <line x1="15" y1="9" x2="15.01" y2="9"/>
                            <line x1="9" y1="15" x2="15" y2="15"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">ID Card Scanning</h3>
                    <p class="feature-description">
                        Fast and accurate ID card scanning for students and staff with instant verification.
                    </p>
                    <ul class="feature-list">
                        <li>QR Code Support</li>
                        <li>Barcode Reading</li>
                        <li>Manual Entry Option</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon bg-success">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Visitor Management</h3>
                    <p class="feature-description">
                        Complete visitor registration system with temporary passes and QR codes.
                    </p>
                    <ul class="feature-list">
                        <li>Quick Registration</li>
                        <li>Digital Passes</li>
                        <li>Host Notification</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon bg-info">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/>
                            <polyline points="3.27 6.96 12 12.01 20.73 6.96"/>
                            <line x1="12" y1="22.08" x2="12" y2="12"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Material Tracking</h3>
                    <p class="feature-description">
                        Track laptops, equipment, and university assets entering or leaving campus.
                    </p>
                    <ul class="feature-list">
                        <li>Asset Registration</li>
                        <li>Permission Workflow</li>
                        <li>Movement History</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon bg-warning">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Incident Reporting</h3>
                    <p class="feature-description">
                        Document and track security incidents with escalation workflows.
                    </p>
                    <ul class="feature-list">
                        <li>Severity Levels</li>
                        <li>Status Tracking</li>
                        <li>Investigation Notes</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon bg-danger">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                            <polyline points="14 2 14 8 20 8"/>
                            <line x1="16" y1="13" x2="8" y2="13"/>
                            <line x1="16" y1="17" x2="8" y2="17"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Detailed Logs</h3>
                    <p class="feature-description">
                        Comprehensive audit trails for all access events and system activities.
                    </p>
                    <ul class="feature-list">
                        <li>Entry/Exit Records</li>
                        <li>User Activity Logs</li>
                        <li>Export Capabilities</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon bg-secondary">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Analytics & Reports</h3>
                    <p class="feature-description">
                        Gain insights with powerful analytics and customizable reports.
                    </p>
                    <ul class="feature-list">
                        <li>Real-time Dashboards</li>
                        <li>Trend Analysis</li>
                        <li>Scheduled Reports</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="about" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <span class="section-badge">About Us</span>
                    <h2 class="section-title">Built for Security, Designed for Simplicity</h2>
                    <p class="about-text">
                        Our University Gate Control System is a comprehensive security management platform 
                        designed specifically for educational institutions. We understand the unique challenges 
                        of managing campus access while maintaining a welcoming environment.
                    </p>
                    <p class="about-text">
                        With real-time monitoring, detailed reporting, and intuitive interfaces for all user roles, 
                        we help universities maintain secure campuses without compromising on convenience.
                    </p>
                    
                    <div class="about-features">
                        <div class="about-feature">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Enterprise-grade Security</span>
                        </div>
                        <div class="about-feature">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Role-based Access Control</span>
                        </div>
                        <div class="about-feature">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>Mobile Responsive Design</span>
                        </div>
                        <div class="about-feature">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <polyline points="20 6 9 17 4 12"/>
                            </svg>
                            <span>24/7 System Monitoring</span>
                        </div>
                    </div>
                </div>
                
                <div class="about-visual">
                    <div class="about-image">
                        <div class="image-placeholder">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                                <rect x="3" y="3" width="18" height="18" rx="2"/>
                                <circle cx="8.5" cy="8.5" r="1.5"/>
                                <polyline points="21 15 16 10 5 21"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="container">
            <div class="section-header">
                <span class="section-badge">Contact</span>
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-description">
                    Have questions? We're here to help and answer any inquiries you may have.
                </p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <h4>Address</h4>
                            <p>University Administration Building<br>Campus Drive, City 12345</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <div>
                            <h4>Phone</h4>
                            <p>+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p>security@university.edu<br>support@university.edu</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-wrapper">
                    <form class="contact-form" id="contactForm">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" required>
                            </div>
                            <div class="form-group">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" required>
                        </div>
                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            Send Message
                            <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <line x1="22" y1="2" x2="11" y2="13"/>
                                <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <div class="logo">
                        <svg class="logo-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                            <polyline points="9 22 9 12 15 12 15 22"/>
                        </svg>
                        <span class="logo-text">Gate Control</span>
                    </div>
                    <p>Secure, reliable, and efficient campus access management for modern universities.</p>
                </div>
                
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">User Guide</a></li>
                        <li><a href="#">FAQs</a></li>
                        <li><a href="#">Support</a></li>
                    </ul>
                </div>
                
                <div class="footer-links">
                    <h4>Legal</h4>
                    <ul>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> University Gate Control System. All rights reserved.</p>
                <p>Version <?php echo APP_VERSION; ?></p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="<?php echo JS_URL; ?>/app.js"></script>
    <script src="<?php echo JS_URL; ?>/theme.js"></script>
    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuToggle').addEventListener('click', function() {
            document.getElementById('navLinks').classList.toggle('show');
        });
        
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    document.getElementById('navLinks').classList.remove('show');
                }
            });
        });
        
        // Animate stats on scroll
        function animateStats() {
            const statNumbers = document.querySelectorAll('.stat-number[data-count]');
            statNumbers.forEach(stat => {
                const target = parseInt(stat.getAttribute('data-count'));
                const duration = 2000;
                const step = target / (duration / 16);
                let current = 0;
                
                const timer = setInterval(() => {
                    current += step;
                    if (current >= target) {
                        stat.textContent = target.toLocaleString();
                        clearInterval(timer);
                    } else {
                        stat.textContent = Math.floor(current).toLocaleString();
                    }
                }, 16);
            });
        }
        
        // Trigger animation when stats are visible
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    animateStats();
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.5 });
        
        observer.observe(document.querySelector('.hero-stats'));
    </script>
</body>
</html>
