/**
 * Dark Mode Toggle Functionality
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

(function() {
    'use strict';

    // Initialize dark mode
    document.addEventListener('DOMContentLoaded', function() {
        initDarkMode();
    });

    function initDarkMode() {
        const toggle = document.querySelector('.dark-mode-toggle');
        const body = document.body;

        if (!toggle) {
            console.warn('Dark mode toggle not found');
            return;
        }

        // Check for saved preference or system preference
        const savedMode = localStorage.getItem('samira-dark-mode');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const defaultOn = typeof samira_dark_mode !== 'undefined' && Boolean(Number(samira_dark_mode.default_on));
        const shouldBeDark = savedMode ? savedMode === 'true' : (defaultOn ? true : systemPrefersDark);

        // Apply initial mode
        if (shouldBeDark) {
            enableDarkMode();
        } else {
            disableDarkMode();
        }

        // Toggle event listener
        toggle.addEventListener('click', function() {
            const isDark = body.classList.contains('dark-mode');

            if (isDark) {
                disableDarkMode();
                localStorage.setItem('samira-dark-mode', 'false');
            } else {
                enableDarkMode();
                localStorage.setItem('samira-dark-mode', 'true');
            }

            // Announce change to screen readers
            announceToScreenReader(isDark ? 'Modalità chiara attivata' : 'Modalità scura attivata');
        });

        // Listen for system preference changes
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
            // Only auto-switch if user hasn't set a preference
            if (!localStorage.getItem('samira-dark-mode')) {
                if (e.matches) {
                    enableDarkMode();
                } else {
                    disableDarkMode();
                }
            }
        });

        // Keyboard accessibility
        toggle.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggle.click();
            }
        });

        function enableDarkMode() {
            body.classList.add('dark-mode');
            updateToggleIcon(true);
            updateToggleAria(true);

            // Dispatch custom event
            window.dispatchEvent(new CustomEvent('darkModeEnabled'));
        }

        function disableDarkMode() {
            body.classList.remove('dark-mode');
            updateToggleIcon(false);
            updateToggleAria(false);

            // Dispatch custom event
            window.dispatchEvent(new CustomEvent('darkModeDisabled'));
        }

        function updateToggleIcon(isDark) {
            const icon = toggle.querySelector('svg');

            if (!icon) return;

            if (isDark) {
                // Moon icon for dark mode
                icon.innerHTML = `
                    <path d="M17.293 13.293A8 8 0 0 1 6.707 2.707a8.001 8.001 0 1 0 10.586 10.586Z" fill="currentColor"/>
                `;
            } else {
                // Sun icon for light mode
                icon.innerHTML = `
                    <path d="M10 15a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" fill="currentColor"/>
                    <path d="M10 1v2M10 17v2M18.66 7.34l-1.42 1.42M4.76 12.24l-1.42 1.42M1 10h2M17 10h2M18.66 12.66l-1.42-1.42M4.76 7.76L3.34 6.34" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                `;
            }
        }

        function updateToggleAria(isDark) {
            toggle.setAttribute('aria-pressed', isDark);

            const currentLabel = toggle.getAttribute('aria-label');
            const newLabel = isDark 
                ? 'Attiva modalità chiara' 
                : 'Attiva modalità scura';

            toggle.setAttribute('aria-label', newLabel);
        }

        function announceToScreenReader(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('aria-live', 'polite');
            announcement.setAttribute('aria-atomic', 'true');
            announcement.className = 'sr-only';
            announcement.textContent = message;

            document.body.appendChild(announcement);

            // Remove after announcement
            setTimeout(() => {
                document.body.removeChild(announcement);
            }, 1000);
        }
    }

    // Add utility class for screen readers
    const style = document.createElement('style');
    style.textContent = `
        .sr-only {
            position: absolute !important;
            width: 1px !important;
            height: 1px !important;
            padding: 0 !important;
            margin: -1px !important;
            overflow: hidden !important;
            clip: rect(0, 0, 0, 0) !important;
            white-space: nowrap !important;
            border: 0 !important;
        }

        .scroll-to-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            z-index: 999;
            background: var(--color-accent);
            color: white;
            border: none;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            opacity: 0;
            visibility: hidden;
            transition: var(--transition-base);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .scroll-to-top.visible {
            opacity: 1;
            visibility: visible;
        }

        .scroll-to-top:hover {
            background: var(--color-accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        }

        .mobile-menu {
            position: fixed;
            top: 70px;
            left: 0;
            right: 0;
            background: var(--color-surface);
            border-bottom: 1px solid var(--color-border);
            box-shadow: 0 4px 20px var(--color-shadow);
            transform: translateY(-100%);
            transition: transform 0.3s ease;
            z-index: 999;
        }

        .mobile-menu.active {
            transform: translateY(0);
        }

        .mobile-menu__content {
            padding: 2rem;
        }

        .mobile-nav-link,
        .mobile-nav-menu a {
            display: block;
            padding: 1rem 0;
            color: var(--color-text);
            text-decoration: none;
            font-family: var(--font-heading);
            font-weight: 500;
            border-bottom: 1px solid var(--color-border);
            transition: var(--transition-base);
        }

        .mobile-nav-link:hover,
        .mobile-nav-menu a:hover {
            color: var(--color-accent);
            padding-left: 1rem;
        }

        .mobile-nav-link:last-child,
        .mobile-nav-menu a:last-child {
            border-bottom: none;
        }

        .mobile-menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-around;
            width: 24px;
            height: 18px;
            background: transparent;
            border: none;
            cursor: pointer;
            padding: 0;
        }

        .hamburger-line {
            width: 100%;
            height: 2px;
            background-color: var(--color-text);
            transition: var(--transition-base);
        }

        .mobile-menu-toggle[aria-expanded="true"] .hamburger-line:nth-child(1) {
            transform: rotate(-45deg) translate(-5px, 6px);
        }

        .mobile-menu-toggle[aria-expanded="true"] .hamburger-line:nth-child(2) {
            opacity: 0;
        }

        .mobile-menu-toggle[aria-expanded="true"] .hamburger-line:nth-child(3) {
            transform: rotate(45deg) translate(-5px, -6px);
        }

        body.mobile-menu-open {
            overflow: hidden;
        }

        /* Mobile styles */
        @media (max-width: 768px) {
            .header__nav {
                display: none;
            }

            .mobile-menu-toggle {
                display: flex;
            }
        }

        /* Loading animation for newsletter */
        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .loading-spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease-in-out infinite;
        }

        /* Newsletter message animations */
        .newsletter__message {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease;
        }

        .newsletter__message.show {
            opacity: 1;
            transform: translateY(0);
        }

        /* Focus styles for better accessibility */
        .btn:focus,
        .form-input:focus,
        .nav-link:focus,
        .social-link:focus,
        .dark-mode-toggle:focus,
        .mobile-menu-toggle:focus {
            outline: 2px solid var(--color-accent);
            outline-offset: 2px;
        }

        /* High contrast mode support */
        @media (prefers-contrast: high) {
            :root {
                --color-shadow: rgba(0, 0, 0, 0.3);
                --color-border: #666;
            }

            body.dark-mode {
                --color-shadow: rgba(0, 0, 0, 0.5);
                --color-border: #999;
            }
        }

        /* Reduced motion support */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
                scroll-behavior: auto !important;
            }

            .section {
                opacity: 1 !important;
                transform: none !important;
            }
        }
    `;

    document.head.appendChild(style);

    // Export for external use
    window.samiraDarkMode = {
        toggle: function() {
            const toggle = document.querySelector('.dark-mode-toggle');
            if (toggle) toggle.click();
        },

        isDarkMode: function() {
            return document.body.classList.contains('dark-mode');
        },

        setDarkMode: function(enabled) {
            const toggle = document.querySelector('.dark-mode-toggle');
            const body = document.body;
            const currentlyDark = body.classList.contains('dark-mode');

            if (enabled && !currentlyDark) {
                toggle.click();
            } else if (!enabled && currentlyDark) {
                toggle.click();
            }
        }
    };

})();
