<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Gate Control System - Home</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/landing.css">
</head>
<body>
    <!-- Navigation Header -->
    <header class="landing-header">
        <nav class="navbar landing-navbar">
            <div class="navbar-brand">
                <svg class="logo-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect width="40" height="40" rx="8" fill="currentColor"/>
                    <path d="M20 10L28 14V26L20 30L12 26V14L20 10Z" fill="white" opacity="0.9"/>
                    <path d="M20 14L24 16V24L20 26L16 24V16L20 14Z" fill="var(--primary-color)"/>
                </svg>
                <span>UGCS</span>
            </div>
            
            <ul class="navbar-nav landing-nav">
                <li><a href="#home" class="nav-link active"><span>Home</span></a></li>
                <li><a href="#features" class="nav-link"><span>Features</span></a></li>
                <li><a href="#about" class="nav-link"><span>About</span></a></li>
                <li><a href="#contact" class="nav-link"><span>Contact</span></a></li>
                <li><a href="login.php" class="btn btn-primary">Login</a></li>
            </ul>
            
            <button class="mobile-menu-btn" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">
                    Secure Campus Access<br>
                    <span class="text-primary">Management System</span>
                </h1>
                <p class="hero-description">
                    Advanced gate control and security management platform for modern universities. 
                    Track entries, manage visitors, monitor materials, and ensure campus safety with real-time analytics.
                </p>
                <div class="hero-actions">
                    <a href="login.php" class="btn btn-primary btn-lg">Get Started</a>
                    <a href="#features" class="btn btn-outline btn-lg">Learn More</a>
                </div>
                
                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-number">50K+</div>
                        <div class="stat-label">Daily Entries</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">99.9%</div>
                        <div class="stat-label">Uptime</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">24/7</div>
                        <div class="stat-label">Monitoring</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">100+</div>
                        <div class="stat-label">Universities</div>
                    </div>
                </div>
            </div>
            
            <div class="hero-visual">
                <div class="hero-image-container">
                    <div class="hero-illustration">
                        <div class="gate-structure">
                            <div class="gate-tower left"></div>
                            <div class="gate-barrier"></div>
                            <div class="gate-tower right"></div>
                        </div>
                        <div class="access-card"></div>
                        <div class="scan-wave"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="hero-bg-shapes">
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Powerful Features</h2>
                <p class="section-description">
                    Everything you need to manage campus security efficiently
                </p>
            </div>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon primary">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Access Control</h3>
                    <p class="feature-description">
                        Manage student, staff, and visitor entry with QR codes, ID cards, and biometric verification.
                    </p>
                    <ul class="feature-list">
                        <li>✓ Real-time scanning</li>
                        <li>✓ Multi-gate support</li>
                        <li>✓ Entry/Exit logs</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon success">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Visitor Management</h3>
                    <p class="feature-description">
                        Register visitors, generate temporary passes, and track visitor movements across campus.
                    </p>
                    <ul class="feature-list">
                        <li>✓ Quick registration</li>
                        <li>✓ QR pass generation</li>
                        <li>✓ Visit history</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon warning">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Material Tracking</h3>
                    <p class="feature-description">
                        Monitor laptops, equipment, and university assets entering and exiting campus.
                    </p>
                    <ul class="feature-list">
                        <li>✓ Asset registration</li>
                        <li>✓ Movement approval</li>
                        <li>✓ Permission verification</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                            <line x1="12" y1="9" x2="12" y2="13"/>
                            <line x1="12" y1="17" x2="12.01" y2="17"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Incident Reporting</h3>
                    <p class="feature-description">
                        Report, track, and resolve security incidents with escalation workflows.
                    </p>
                    <ul class="feature-list">
                        <li>✓ Incident logging</li>
                        <li>✓ Severity levels</li>
                        <li>✓ Escalation system</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon info">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"/>
                            <line x1="12" y1="20" x2="12" y2="4"/>
                            <line x1="6" y1="20" x2="6" y2="14"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">Analytics & Reports</h3>
                    <p class="feature-description">
                        Generate comprehensive reports and gain insights into campus access patterns.
                    </p>
                    <ul class="feature-list">
                        <li>✓ Daily summaries</li>
                        <li>✓ Custom reports</li>
                        <li>✓ Export capabilities</li>
                    </ul>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon accent">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="3"/>
                            <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/>
                        </svg>
                    </div>
                    <h3 class="feature-title">System Settings</h3>
                    <p class="feature-description">
                        Configure gates, manage roles, customize permissions, and control system preferences.
                    </p>
                    <ul class="feature-list">
                        <li>✓ Role-based access</li>
                        <li>✓ Gate configuration</li>
                        <li>✓ Theme customization</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2 class="section-title">About the System</h2>
                    <p class="about-text">
                        The University Gate Control System (UGCS) is a comprehensive security management platform 
                        designed specifically for educational institutions. Our mission is to provide universities 
                        with state-of-the-art tools to manage campus access, enhance security protocols, and maintain 
                        detailed records of all campus movements.
                    </p>
                    <p class="about-text">
                        Built with modern web technologies and following industry best practices, UGCS offers 
                        reliability, scalability, and ease of use. Whether you're managing a single gate or 
                        multiple entry points across a large campus, our system adapts to your needs.
                    </p>
                    
                    <div class="about-features">
                        <div class="about-feature-item">
                            <div class="check-icon">✓</div>
                            <span>Enterprise-grade security</span>
                        </div>
                        <div class="about-feature-item">
                            <div class="check-icon">✓</div>
                            <span>Real-time monitoring</span>
                        </div>
                        <div class="about-feature-item">
                            <div class="check-icon">✓</div>
                            <span>Mobile-responsive design</span>
                        </div>
                        <div class="about-feature-item">
                            <div class="check-icon">✓</div>
                            <span>Comprehensive audit trails</span>
                        </div>
                    </div>
                </div>
                
                <div class="about-visual">
                    <div class="about-illustration">
                        <div class="dashboard-preview">
                            <div class="preview-header"></div>
                            <div class="preview-sidebar"></div>
                            <div class="preview-content">
                                <div class="preview-stat"></div>
                                <div class="preview-stat"></div>
                                <div class="preview-stat"></div>
                                <div class="preview-table"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header text-center">
                <h2 class="section-title">Get In Touch</h2>
                <p class="section-description">
                    Have questions? We'd love to hear from you.
                </p>
            </div>
            
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                                <circle cx="12" cy="10" r="3"/>
                            </svg>
                        </div>
                        <div>
                            <h4>Address</h4>
                            <p>University of Technology<br>Main Campus, Building A</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                                <polyline points="22,6 12,13 2,6"/>
                            </svg>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p>support@university.edu<br>info@university.edu</p>
                        </div>
                    </div>
                    
                    <div class="contact-item">
                        <div class="contact-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                            </svg>
                        </div>
                        <div>
                            <h4>Phone</h4>
                            <p>+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                        </div>
                    </div>
                </div>
                
                <div class="contact-form-container">
                    <form class="contact-form" action="#" method="POST">
                        <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="subject">Subject</label>
                            <input type="text" id="subject" name="subject" class="form-control" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="message">Message</label>
                            <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-block">Send Message</button>
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
                    <div class="navbar-brand">
                        <svg class="logo-icon" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect width="40" height="40" rx="8" fill="currentColor"/>
                            <path d="M20 10L28 14V26L20 30L12 26V14L20 10Z" fill="white" opacity="0.9"/>
                            <path d="M20 14L24 16V24L20 26L16 24V16L20 14Z" fill="var(--primary-color)"/>
                        </svg>
                        <span>UGCS</span>
                    </div>
                    <p>Advanced campus security management for modern universities.</p>
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
                        <li><a href="#">Support</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Terms of Service</a></li>
                    </ul>
                </div>
                
                <div class="footer-social">
                    <h4>Connect With Us</h4>
                    <div class="social-links">
                        <a href="#" class="social-link" aria-label="Facebook">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="Twitter">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                            </svg>
                        </a>
                        <a href="#" class="social-link" aria-label="LinkedIn">
                            <svg viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; 2024 University Gate Control System. All rights reserved.</p>
                <p>Version 1.0.0</p>
            </div>
        </div>
    </footer>

    <!-- Scroll to Top Button -->
    <button id="scrollToTop" class="scroll-to-top" aria-label="Scroll to top">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="18 15 12 9 6 15"/>
        </svg>
    </button>

    <script src="assets/js/app.js"></script>
    <script src="assets/js/theme.js"></script>
</body>
</html>
