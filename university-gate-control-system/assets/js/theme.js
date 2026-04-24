/**
 * Theme Switching Functionality
 * Light/Dark mode toggle with localStorage persistence
 */

// ============================================
// THEME INITIALIZATION
// ============================================

(function() {
    // Check for saved theme preference or default to light mode
    const savedTheme = localStorage.getItem('theme') || 'light';
    setTheme(savedTheme);
})();

// ============================================
// THEME TOGGLE FUNCTION
// ============================================

function setTheme(theme) {
    document.documentElement.setAttribute('data-theme', theme);
    localStorage.setItem('theme', theme);
    
    // Update toggle button state if it exists
    updateThemeToggle(theme);
    
    // Dispatch custom event for other components to react
    window.dispatchEvent(new CustomEvent('themechange', { detail: { theme } }));
}

function toggleTheme() {
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    setTheme(newTheme);
}

function updateThemeToggle(theme) {
    const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
    
    toggleButtons.forEach(button => {
        const icon = button.querySelector('.theme-icon');
        if (icon) {
            if (theme === 'dark') {
                icon.innerHTML = getSunIcon();
            } else {
                icon.innerHTML = getMoonIcon();
            }
        }
        
        // Update aria-label
        button.setAttribute('aria-label', `Switch to ${theme === 'light' ? 'dark' : 'light'} mode`);
    });
}

// ============================================
// ICON SVG STRINGS
// ============================================

function getSunIcon() {
    return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="5"/>
        <line x1="12" y1="1" x2="12" y2="3"/>
        <line x1="12" y1="21" x2="12" y2="23"/>
        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
        <line x1="1" y1="12" x2="3" y2="12"/>
        <line x1="21" y1="12" x2="23" y2="12"/>
        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
    </svg>`;
}

function getMoonIcon() {
    return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
    </svg>`;
}

// ============================================
// INITIALIZE THEME TOGGLE BUTTONS
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    initThemeToggles();
    initKeyboardShortcut();
});

function initThemeToggles() {
    const toggleButtons = document.querySelectorAll('[data-theme-toggle]');
    
    toggleButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            toggleTheme();
        });
    });
    
    // Initialize icons based on current theme
    const currentTheme = document.documentElement.getAttribute('data-theme') || 'light';
    updateThemeToggle(currentTheme);
}

function initKeyboardShortcut() {
    // Allow toggling theme with Alt + T keyboard shortcut
    document.addEventListener('keydown', function(e) {
        if (e.altKey && e.key === 't') {
            e.preventDefault();
            toggleTheme();
        }
    });
}

// ============================================
// THEME PREFERENCES IN PROFILE
// ============================================

// Function to save theme preference to server (for logged-in users)
async function saveThemePreference(theme) {
    try {
        const response = await fetch('actions/settings/theme-action.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ theme })
        });
        
        if (!response.ok) {
            throw new Error('Failed to save theme preference');
        }
        
        const result = await response.json();
        
        if (result.success) {
            console.log('Theme preference saved successfully');
        } else {
            console.error('Failed to save theme preference:', result.message);
        }
    } catch (error) {
        console.error('Error saving theme preference:', error);
    }
}

// Listen for theme changes and save to server if user is logged in
window.addEventListener('themechange', function(e) {
    if (typeof isLoggedIn !== 'undefined' && isLoggedIn()) {
        saveThemePreference(e.detail.theme);
    }
});

// ============================================
// SYSTEM THEME DETECTION
// ============================================

// Optionally, detect and use system theme preference
function detectSystemTheme() {
    if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
        return 'dark';
    }
    return 'light';
}

// Listen for system theme changes
if (window.matchMedia) {
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        // Only auto-switch if user hasn't set a preference
        const savedTheme = localStorage.getItem('theme');
        if (!savedTheme) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });
}

// ============================================
// THEME TRANSITION EFFECTS
// ============================================

// Add smooth transition when switching themes
const style = document.createElement('style');
style.textContent = `
    *, *::before, *::after {
        transition-property: background-color, border-color, color, fill, stroke !important;
        transition-duration: 0.3s !important;
        transition-timing-function: ease-in-out !important;
    }
    
    /* Exclude certain elements from transition */
    .no-theme-transition, 
    .no-theme-transition *, 
    .no-theme-transition::before, 
    .no-theme-transition::after {
        transition-property: none !important;
    }
`;
document.head.appendChild(style);

// ============================================
// THEME SWITCHER DROPDOWN/MODAL
// ============================================

function showThemeSelector() {
    const selector = document.createElement('div');
    selector.className = 'theme-selector modal-overlay';
    selector.id = 'theme-selector';
    selector.innerHTML = `
        <div class="modal">
            <div class="modal-header">
                <h3 class="modal-title">Choose Theme</h3>
                <button class="modal-close" data-modal-close>&times;</button>
            </div>
            <div class="modal-body">
                <div class="theme-options">
                    <button class="theme-option" data-theme-value="light">
                        <div class="theme-option-icon">${getSunIcon()}</div>
                        <span>Light Mode</span>
                    </button>
                    <button class="theme-option" data-theme-value="dark">
                        <div class="theme-option-icon">${getMoonIcon()}</div>
                        <span>Dark Mode</span>
                    </button>
                    <button class="theme-option" data-theme-value="system">
                        <div class="theme-option-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/>
                                <line x1="8" y1="21" x2="16" y2="21"/>
                                <line x1="12" y1="17" x2="12" y2="21"/>
                            </svg>
                        </div>
                        <span>System Default</span>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(selector);
    
    // Show modal
    setTimeout(() => selector.classList.add('active'), 10);
    
    // Handle theme selection
    const options = selector.querySelectorAll('.theme-option');
    options.forEach(option => {
        option.addEventListener('click', function() {
            const themeValue = this.dataset.themeValue;
            
            if (themeValue === 'system') {
                localStorage.removeItem('theme');
                setTheme(detectSystemTheme());
            } else {
                setTheme(themeValue);
            }
            
            closeModal(selector);
        });
    });
    
    // Close on overlay click
    selector.addEventListener('click', function(e) {
        if (e.target === selector) {
            closeModal(selector);
        }
    });
    
    // Close on ESC
    const closeOnEsc = function(e) {
        if (e.key === 'Escape') {
            closeModal(selector);
            document.removeEventListener('keydown', closeOnEsc);
        }
    };
    document.addEventListener('keydown', closeOnEsc);
}

// Add theme selector styles
const themeSelectorStyle = document.createElement('style');
themeSelectorStyle.textContent = `
    .theme-selector .theme-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: var(--spacing-lg);
    }
    
    .theme-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: var(--spacing-sm);
        padding: var(--spacing-lg);
        background-color: var(--bg-secondary);
        border: 2px solid var(--border-color);
        border-radius: var(--radius-lg);
        cursor: pointer;
        transition: all var(--transition-base);
    }
    
    .theme-option:hover {
        border-color: var(--primary-color);
        background-color: var(--bg-tertiary);
        transform: translateY(-4px);
    }
    
    .theme-option.active {
        border-color: var(--primary-color);
        background-color: rgba(var(--primary-rgb), 0.1);
    }
    
    .theme-option-icon {
        width: 48px;
        height: 48px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text-primary);
    }
    
    .theme-option-icon svg {
        width: 100%;
        height: 100%;
    }
    
    @media (max-width: 768px) {
        .theme-selector .theme-options {
            grid-template-columns: 1fr;
        }
    }
`;
document.head.appendChild(themeSelectorStyle);

// Export functions for global access
window.setTheme = setTheme;
window.toggleTheme = toggleTheme;
window.showThemeSelector = showThemeSelector;

console.log('Theme system initialized');
