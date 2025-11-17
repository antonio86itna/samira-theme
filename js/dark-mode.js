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
            console.warn(samira_dark_mode.strings.toggle_not_found);
            return;
        }

        // Check for saved preference or system preference
        const savedMode = localStorage.getItem('samira-dark-mode');
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const defaultOn = typeof samira_dark_mode !== 'undefined' && Boolean(Number(samira_dark_mode.default_on));
        const shouldBeDark = savedMode === null ? (defaultOn || systemPrefersDark) : savedMode === 'true';

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
            announceToScreenReader(isDark ? samira_dark_mode.strings.light_mode_activated : samira_dark_mode.strings.dark_mode_activated);
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
            const icon = toggle.querySelector('.dark-mode-icon');

            if (!icon) return;

            icon.setAttribute('viewBox', '0 0 24 24');
            icon.setAttribute('fill', 'none');
            icon.setAttribute('stroke', 'currentColor');
            icon.setAttribute('stroke-width', '2');
            icon.setAttribute('stroke-linecap', 'round');
            icon.setAttribute('stroke-linejoin', 'round');

            if (isDark) {
                // Sun icon for dark mode (to switch back to light)
                icon.innerHTML = `
                    <circle cx="12" cy="12" r="5"/>
                    <line x1="12" y1="1" x2="12" y2="3"/>
                    <line x1="12" y1="21" x2="12" y2="23"/>
                    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                    <line x1="1" y1="12" x2="3" y2="12"/>
                    <line x1="21" y1="12" x2="23" y2="12"/>
                    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                `;
            } else {
                // Moon icon for light mode (to switch to dark)
                icon.innerHTML = `
                    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/>
                `;
            }
        }

        function updateToggleAria(isDark) {
            toggle.setAttribute('aria-pressed', isDark);

            const currentLabel = toggle.getAttribute('aria-label');
            const newLabel = isDark
                ? samira_dark_mode.strings.activate_light_mode
                : samira_dark_mode.strings.activate_dark_mode;

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
