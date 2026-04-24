/**
 * Main Application JavaScript
 * Core functionality and initialization
 */

// Wait for DOM to be ready
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all components
    initMobileMenu();
    initSmoothScroll();
    initScrollToTop();
    initAnimations();
    initFormValidation();
    initModals();
    initNotifications();
});

// ============================================
// MOBILE MENU TOGGLE
// ============================================

function initMobileMenu() {
    const mobileMenuBtn = document.querySelector('.mobile-menu-btn');
    const landingNav = document.querySelector('.landing-nav');
    
    if (mobileMenuBtn && landingNav) {
        mobileMenuBtn.addEventListener('click', function() {
            landingNav.classList.toggle('active');
            this.classList.toggle('active');
        });
        
        // Close menu when clicking on a link
        const navLinks = landingNav.querySelectorAll('.nav-link, .btn');
        navLinks.forEach(link => {
            link.addEventListener('click', function() {
                landingNav.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!mobileMenuBtn.contains(e.target) && !landingNav.contains(e.target)) {
                landingNav.classList.remove('active');
                mobileMenuBtn.classList.remove('active');
            }
        });
    }
}

// ============================================
// SMOOTH SCROLL FOR ANCHOR LINKS
// ============================================

function initSmoothScroll() {
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            if (href !== '#' && href.length > 1) {
                const target = document.querySelector(href);
                
                if (target) {
                    e.preventDefault();
                    
                    const headerHeight = document.querySelector('.navbar')?.offsetHeight || 0;
                    const targetPosition = target.offsetTop - headerHeight;
                    
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                }
            }
        });
    });
}

// ============================================
// SCROLL TO TOP BUTTON
// ============================================

function initScrollToTop() {
    const scrollToTopBtn = document.getElementById('scrollToTop');
    
    if (scrollToTopBtn) {
        window.addEventListener('scroll', function() {
            if (window.pageYOffset > 300) {
                scrollToTopBtn.classList.add('visible');
            } else {
                scrollToTopBtn.classList.remove('visible');
            }
        });
        
        scrollToTopBtn.addEventListener('click', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
}

// ============================================
// SCROLL ANIMATIONS
// ============================================

function initAnimations() {
    const observerOptions = {
        root: null,
        rootMargin: '0px',
        threshold: 0.1
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('animate-in');
                observer.unobserve(entry.target);
            }
        });
    }, observerOptions);
    
    // Observe elements with animation classes
    const animatedElements = document.querySelectorAll('.feature-card, .stat-item, .contact-item');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(20px)';
        el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
        observer.observe(el);
    });
}

// Add CSS for animations
const style = document.createElement('style');
style.textContent = `
    .animate-in {
        opacity: 1 !important;
        transform: translateY(0) !important;
    }
`;
document.head.appendChild(style);

// ============================================
// FORM VALIDATION
// ============================================

function initFormValidation() {
    const forms = document.querySelectorAll('form:not([novalidate])');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
        
        // Real-time validation
        const inputs = form.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
            
            input.addEventListener('input', function() {
                // Remove error state on input
                this.classList.remove('error');
                const errorEl = this.parentElement.querySelector('.form-error');
                if (errorEl) {
                    errorEl.remove();
                }
            });
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const requiredFields = form.querySelectorAll('[required]');
    
    requiredFields.forEach(field => {
        if (!validateField(field)) {
            isValid = false;
        }
    });
    
    return isValid;
}

function validateField(field) {
    const value = field.value.trim();
    const type = field.type;
    const isRequired = field.hasAttribute('required');
    
    // Reset error state
    field.classList.remove('error');
    const existingError = field.parentElement.querySelector('.form-error');
    if (existingError) {
        existingError.remove();
    }
    
    // Check required
    if (isRequired && !value) {
        showError(field, 'This field is required');
        return false;
    }
    
    // Skip further validation if empty and not required
    if (!value && !isRequired) {
        return true;
    }
    
    // Email validation
    if (type === 'email' && value) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(value)) {
            showError(field, 'Please enter a valid email address');
            return false;
        }
    }
    
    // Password validation
    if (type === 'password' && value) {
        if (value.length < 8) {
            showError(field, 'Password must be at least 8 characters');
            return false;
        }
    }
    
    // Number validation
    if (type === 'number' && value) {
        if (isNaN(value)) {
            showError(field, 'Please enter a valid number');
            return false;
        }
    }
    
    // Min length validation
    const minLength = field.getAttribute('minlength');
    if (minLength && value.length < parseInt(minLength)) {
        showError(field, `Minimum ${minLength} characters required`);
        return false;
    }
    
    // Max length validation
    const maxLength = field.getAttribute('maxlength');
    if (maxLength && value.length > parseInt(maxLength)) {
        showError(field, `Maximum ${maxLength} characters allowed`);
        return false;
    }
    
    return true;
}

function showError(field, message) {
    field.classList.add('error');
    field.style.borderColor = 'var(--danger-color)';
    
    const errorEl = document.createElement('div');
    errorEl.className = 'form-error';
    errorEl.textContent = message;
    
    field.parentElement.appendChild(errorEl);
}

// ============================================
// MODAL FUNCTIONALITY
// ============================================

function initModals() {
    // Modal trigger buttons
    const modalTriggers = document.querySelectorAll('[data-modal-target]');
    
    modalTriggers.forEach(trigger => {
        trigger.addEventListener('click', function() {
            const modalId = this.getAttribute('data-modal-target');
            const modal = document.querySelector(modalId);
            
            if (modal) {
                openModal(modal);
            }
        });
    });
    
    // Modal close buttons
    const modalCloseButtons = document.querySelectorAll('.modal-close, [data-modal-close]');
    
    modalCloseButtons.forEach(button => {
        button.addEventListener('click', function() {
            const modal = this.closest('.modal-overlay');
            if (modal) {
                closeModal(modal);
            }
        });
    });
    
    // Close modal on overlay click
    const modalOverlays = document.querySelectorAll('.modal-overlay');
    
    modalOverlays.forEach(overlay => {
        overlay.addEventListener('click', function(e) {
            if (e.target === overlay) {
                closeModal(overlay);
            }
        });
    });
    
    // Close modal on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const activeModal = document.querySelector('.modal-overlay.active');
            if (activeModal) {
                closeModal(activeModal);
            }
        }
    });
}

function openModal(modal) {
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Focus first focusable element
    const firstFocusable = modal.querySelector('button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])');
    if (firstFocusable) {
        setTimeout(() => firstFocusable.focus(), 100);
    }
}

function closeModal(modal) {
    modal.classList.remove('active');
    document.body.style.overflow = '';
    
    // Return focus to trigger element
    const trigger = document.querySelector('[data-modal-target="#' + modal.id + '"]');
    if (trigger) {
        trigger.focus();
    }
}

// Global modal functions
window.openModal = openModal;
window.closeModal = closeModal;

// ============================================
// NOTIFICATION SYSTEM
// ============================================

function initNotifications() {
    // Create toast container if it doesn't exist
    let toastContainer = document.querySelector('.toast-container');
    
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.className = 'toast-container';
        document.body.appendChild(toastContainer);
    }
    
    // Show notifications from session (if any)
    const notificationData = document.querySelector('#notification-data');
    if (notificationData) {
        try {
            const notifications = JSON.parse(notificationData.textContent);
            notifications.forEach(note => {
                showToast(note.message, note.type);
            });
        } catch (e) {
            console.error('Error parsing notification data:', e);
        }
    }
}

function showToast(message, type = 'info', duration = 5000) {
    const toastContainer = document.querySelector('.toast-container');
    
    if (!toastContainer) {
        return;
    }
    
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <div class="toast-content">
            <p>${escapeHtml(message)}</p>
        </div>
        <button class="toast-close" aria-label="Close">×</button>
    `;
    
    toastContainer.appendChild(toast);
    
    // Auto-remove after duration
    const timeoutId = setTimeout(() => {
        removeToast(toast);
    }, duration);
    
    // Close button
    const closeBtn = toast.querySelector('.toast-close');
    closeBtn.addEventListener('click', () => {
        clearTimeout(timeoutId);
        removeToast(toast);
    });
}

function removeToast(toast) {
    toast.style.animation = 'slideOut 0.3s ease forwards';
    
    setTimeout(() => {
        toast.remove();
    }, 300);
}

// Add slideOut animation
const slideOutStyle = document.createElement('style');
slideOutStyle.textContent = `
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(slideOutStyle);

// Global toast function
window.showToast = showToast;

// ============================================
// UTILITY FUNCTIONS
// ============================================

// Escape HTML to prevent XSS
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Format date
function formatDate(dateString, format = 'short') {
    const date = new Date(dateString);
    
    if (format === 'short') {
        return date.toLocaleDateString();
    } else if (format === 'long') {
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
    } else if (format === 'time') {
        return date.toLocaleTimeString();
    } else {
        return date.toLocaleString();
    }
}

// Debounce function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Throttle function
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

// Local storage helpers
const storage = {
    get: function(key) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : null;
        } catch (e) {
            console.error('Error reading from localStorage:', e);
            return null;
        }
    },
    
    set: function(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch (e) {
            console.error('Error writing to localStorage:', e);
            return false;
        }
    },
    
    remove: function(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch (e) {
            console.error('Error removing from localStorage:', e);
            return false;
        }
    }
};

window.storage = storage;

// ============================================
// AJAX HELPER
// ============================================

async function ajaxRequest(url, options = {}) {
    const defaultOptions = {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    };
    
    const config = { ...defaultOptions, ...options };
    
    // Add CSRF token if available
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
    if (csrfToken) {
        config.headers['X-CSRF-TOKEN'] = csrfToken;
    }
    
    try {
        const response = await fetch(url, config);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const contentType = response.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            return await response.json();
        } else {
            return await response.text();
        }
    } catch (error) {
        console.error('AJAX request failed:', error);
        throw error;
    }
}

window.ajaxRequest = ajaxRequest;

// ============================================
// TABLE SORTING
// ============================================

function initTableSorting() {
    const tables = document.querySelectorAll('table[data-sortable]');
    
    tables.forEach(table => {
        const headers = table.querySelectorAll('th[data-sortable]');
        
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', function() {
                const columnIndex = this.cellIndex;
                const direction = this.dataset.sortDirection || 'asc';
                
                sortTable(table, columnIndex, direction);
                
                // Toggle sort direction
                this.dataset.sortDirection = direction === 'asc' ? 'desc' : 'asc';
                
                // Update visual indicators
                headers.forEach(h => h.classList.remove('sort-asc', 'sort-desc'));
                this.classList.add(direction === 'asc' ? 'sort-asc' : 'sort-desc');
            });
        });
    });
}

function sortTable(table, columnIndex, direction) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        // Check if values are numbers
        const aNum = parseFloat(aValue);
        const bNum = parseFloat(bValue);
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return direction === 'asc' ? aNum - bNum : bNum - aNum;
        }
        
        // String comparison
        return direction === 'asc' 
            ? aValue.localeCompare(bValue)
            : bValue.localeCompare(aValue);
    });
    
    // Re-append sorted rows
    rows.forEach(row => tbody.appendChild(row));
}

// Initialize on DOM ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initTableSorting);
} else {
    initTableSorting();
}

console.log('University Gate Control System initialized successfully.');
