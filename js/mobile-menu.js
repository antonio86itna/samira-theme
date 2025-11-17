/**
 * Mobile Menu Functionality
 *
 * @package Samira_Theme
 * @version 1.0.0
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initMobileMenu();
        initScrollEffect();
    });

    /**
     * Initialize Mobile Menu
     */
    function initMobileMenu() {
        const header = document.querySelector('.header');
        const navigation = document.querySelector('.main-navigation');
        const navMenu = navigation ? navigation.querySelector('.nav-menu') : null;

        if (!header || !navigation) {
            return;
        }

        // Create mobile menu toggle if it doesn't exist
        let toggle = header.querySelector('.mobile-menu-toggle');

        if (!toggle) {
            toggle = createMobileToggle();
            const headerActions = header.querySelector('.header__actions') || header.querySelector('.header__content');

            if (headerActions) {
                // Insert before dark mode toggle or at the end
                const darkModeToggle = header.querySelector('.dark-mode-toggle');
                if (darkModeToggle && darkModeToggle.parentElement === headerActions) {
                    headerActions.insertBefore(toggle, darkModeToggle);
                } else {
                    headerActions.appendChild(toggle);
                }
            }
        }

        // Add mobile contact button to menu if it doesn't exist
        if (navMenu && window.innerWidth <= 768) {
            addMobileContactButton(navMenu);
        }

        // Toggle menu on click
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            toggleMenu();
        });

        // Close menu on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && navigation.classList.contains('active')) {
                toggleMenu();
            }
        });

        // Close menu when clicking menu links
        if (navMenu) {
            const menuLinks = navMenu.querySelectorAll('a');
            menuLinks.forEach(function(link) {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setTimeout(toggleMenu, 300);
                    }
                });
            });
        }

        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768 && navigation.classList.contains('active')) {
                    toggleMenu();
                } else if (window.innerWidth <= 768) {
                    addMobileContactButton(navMenu);
                }
            }, 250);
        });

        /**
         * Toggle mobile menu
         */
        function toggleMenu() {
            const isActive = navigation.classList.contains('active');

            navigation.classList.toggle('active');
            toggle.classList.toggle('active');
            document.body.classList.toggle('mobile-menu-open');

            // Update ARIA attributes
            toggle.setAttribute('aria-expanded', !isActive);
            navigation.setAttribute('aria-hidden', isActive);

            // Announce to screen readers
            announceToScreenReader(
                isActive ? 'Menu closed' : 'Menu opened'
            );
        }

        /**
         * Create mobile menu toggle button
         */
        function createMobileToggle() {
            const button = document.createElement('button');
            button.className = 'mobile-menu-toggle';
            button.setAttribute('aria-label', 'Toggle menu');
            button.setAttribute('aria-expanded', 'false');
            button.setAttribute('aria-controls', 'site-navigation');

            for (let i = 0; i < 3; i++) {
                const line = document.createElement('span');
                line.className = 'mobile-menu-toggle__line';
                button.appendChild(line);
            }

            return button;
        }

        /**
         * Add contact button to mobile menu
         */
        function addMobileContactButton(menu) {
            if (!menu) return;

            // Check if already exists
            if (menu.querySelector('.mobile-contact-btn')) return;

            // Get desktop contact button
            const desktopCta = document.querySelector('.header__cta');
            if (!desktopCta) return;

            // Create mobile version
            const mobileBtn = document.createElement('a');
            mobileBtn.href = desktopCta.href || '#contact';
            mobileBtn.className = 'mobile-contact-btn';
            mobileBtn.textContent = desktopCta.textContent || 'Contact';
            mobileBtn.style.display = 'none';

            // Add to menu
            menu.parentElement.appendChild(mobileBtn);
        }

        /**
         * Announce to screen readers
         */
        function announceToScreenReader(message) {
            const announcement = document.createElement('div');
            announcement.setAttribute('role', 'status');
            announcement.setAttribute('aria-live', 'polite');
            announcement.className = 'sr-only';
            announcement.textContent = message;

            document.body.appendChild(announcement);

            setTimeout(function() {
                document.body.removeChild(announcement);
            }, 1000);
        }
    }

    /**
     * Add scroll effect to header
     */
    function initScrollEffect() {
        const header = document.querySelector('.header');
        if (!header) return;

        let lastScroll = 0;

        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;

            if (currentScroll > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }

            lastScroll = currentScroll;
        });
    }

    // Add sr-only utility class if not exists
    if (!document.querySelector('style[data-mobile-menu-styles]')) {
        const style = document.createElement('style');
        style.setAttribute('data-mobile-menu-styles', '');
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
        `;
        document.head.appendChild(style);
    }

})();
