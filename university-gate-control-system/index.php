<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>University Gate Control System - Home</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/landing.css">
</head>
<body class="landing-page">
    <!-- Navigation Header -->
    <header class="landing-header">
        <nav class="navbar">
            <div class="container">
                <div class="nav-brand">
                    <img src="assets/images/logo/university-logo.png" alt="University Logo" class="logo" onerror="this.style.display='none'">
                    <span class="brand-text">University Gate Control</span>
                </div>
                <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <ul class="nav-menu" id="navMenu">
                    <li><a href="#home">Home</a></li>
                    <li><a href="#features">Features</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#contact">Contact</a></li>
                    <li><a href="login.php" class="btn-login">Login</a></li>
                </ul>
            </div>
        </nav>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="hero-overlay"></div>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title">Secure Campus Access Management</h1>
                <p class="hero-subtitle">Advanced gate control system for modern universities. Managing people, materials, and security with precision.</p>
                <div class="hero-buttons">
                    <a href="login.php" class="btn btn-primary btn-large">Get Started</a>
                    <a href="#features" class="btn btn-outline btn-large">Learn More</a>
                </div>
            </div>
            <div class="hero-visual">
                <div class="security-shield">
                    <svg viewBox="0 0 100 100" class="shield-icon">
                        <path d="M50 5 L85 20 L85 50 Q85 80 50 95 Q15 80 15 50 L15 20 Z" fill="none" stroke="currentColor" stroke-width="2"/>
                        <path d="M35 50 L45 60 L65 40" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>
                </div>
            </div>
        </div>
        <div class="scroll-indicator">
            <span>Scroll Down</span>
            <div class="scroll-arrow"></div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats-section">
        <div class="container">
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">5+</div>
                    <div class="stat-label">Gates Monitored</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">10K+</div>
                    <div class="stat-label">Daily Entries</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">99.9%</div>
                    <div class="stat-label">Uptime</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Security Coverage</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">System Features</h2>
                <p class="section-subtitle">Comprehensive access control and security management</p>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </div>
                    <h3>People Access Control</h3>
                    <p>Manage student, staff, and visitor entry with ID scanning and QR code verification.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"></rect>
                            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"></path>
                        </svg>
                    </div>
                    <h3>Material Tracking</h3>
                    <p>Track laptops, equipment, and university assets entering and exiting campus.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                        </svg>
                    </div>
                    <h3>Security Inspection</h3>
                    <p>Comprehensive security checks with bag inspection and prohibited item detection.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="20" x2="18" y2="10"></line>
                            <line x1="12" y1="20" x2="12" y2="4"></line>
                            <line x1="6" y1="20" x2="6" y2="14"></line>
                        </svg>
                    </div>
                    <h3>Incident Management</h3>
                    <p>Report, track, and resolve security incidents with escalation workflows.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                        </svg>
                    </div>
                    <h3>Detailed Reports</h3>
                    <p>Generate comprehensive reports on access patterns, materials, and incidents.</p>
                </div>
                <div class="feature-card">
                    <div class="feature-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="3" y1="10" x2="21" y2="10"></line>
                        </svg>
                    </div>
                    <h3>Visitor Management</h3>
                    <p>Register visitors, generate temporary passes, and track visitor movements.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="about-section">
        <div class="container">
            <div class="about-grid">
                <div class="about-content">
                    <h2 class="section-title">About The System</h2>
                    <p>The University Gate Control System is a comprehensive security management platform designed to streamline access control for educational institutions.</p>
                    <p>Our system provides real-time monitoring of all entry and exit points, ensuring the safety of students, staff, and university property while maintaining efficient traffic flow during peak hours.</p>
                    <ul class="about-features">
                        <li>✓ Multi-role access control</li>
                        <li>✓ Real-time monitoring and alerts</li>
                        <li>✓ Comprehensive audit trails</li>
                        <li>✓ Mobile-responsive design</li>
                        <li>✓ Dark/Light theme support</li>
                    </ul>
                </div>
                <div class="about-image">
                    <div class="illustration-placeholder">
                        <svg viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="40" y="60" width="120" height="100" rx="8" stroke="currentColor" stroke-width="2"/>
                            <circle cx="100" cy="100" r="30" stroke="currentColor" stroke-width="2"/>
                            <path d="M100 70 L100 90 L115 100" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="contact-section">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title">Contact Us</h2>
                <p class="section-subtitle">Get in touch with our support team</p>
            </div>
            <div class="contact-grid">
                <div class="contact-info">
                    <div class="contact-item">
                        <div class="contact-icon">📍</div>
                        <div>
                            <h4>Address</h4>
                            <p>University Administration Building<br>Campus Road, University City</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📧</div>
                        <div>
                            <h4>Email</h4>
                            <p>security@university.edu<br>support@university.edu</p>
                        </div>
                    </div>
                    <div class="contact-item">
                        <div class="contact-icon">📞</div>
                        <div>
                            <h4>Phone</h4>
                            <p>+1 (555) 123-4567<br>+1 (555) 987-6543</p>
                        </div>
                    </div>
                </div>
                <form class="contact-form" onsubmit="return handleContactSubmit(event)">
                    <div class="form-group">
                        <label for="contactName">Full Name</label>
                        <input type="text" id="contactName" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="contactEmail">Email Address</label>
                        <input type="email" id="contactEmail" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="contactMessage">Message</label>
                        <textarea id="contactMessage" name="message" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="landing-footer">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-about">
                    <h3>University Gate Control</h3>
                    <p>Securing campus access with modern technology and comprehensive management tools.</p>
                </div>
                <div class="footer-links">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#home">Home</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#about">About</a></li>
                        <li><a href="login.php">Login</a></li>
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
                    <div class="social-icons">
                        <a href="#" aria-label="Facebook">📘</a>
                        <a href="#" aria-label="Twitter">🐦</a>
                        <a href="#" aria-label="LinkedIn">💼</a>
                        <a href="#" aria-label="Instagram">📷</a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> University Gate Control System. All rights reserved.</p>
                <p>Version 1.0.0</p>
            </div>
        </div>
    </footer>

    <script src="assets/js/app.js"></script>
    <script>
        function toggleMobileMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }

        function handleContactSubmit(e) {
            e.preventDefault();
            alert('Thank you for your message! We will get back to you soon.');
            e.target.reset();
            return false;
        }

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
