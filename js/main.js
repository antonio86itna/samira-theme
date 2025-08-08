/**
 * Samira Theme Main JavaScript
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

(function($) {
    'use strict';

    // DOM Ready
    $(document).ready(function() {
        initTheme();
    });

    // Initialize all theme functionality
    function initTheme() {
        console.log(samira_ajax.strings.theme_initialized);

        initSmoothScroll();
        initScrollAnimations();
        initHeaderScroll();
        initMobileMenu();
        initNewsletterForm();
        initScrollToTop();
        initLazyLoading();
        initPortfolioTabs();
    }

    // Smooth scrolling for anchor links
    function initSmoothScroll() {
        $('a[href^="#"]').on('click', function(e) {
            const targetId = $(this).attr('href');
            const $target = $(targetId);

            if ($target.length) {
                e.preventDefault();

                const headerHeight = $('.header').outerHeight() || 80;
                const targetPosition = $target.offset().top - headerHeight - 20;

                $('html, body').animate({
                    scrollTop: Math.max(0, targetPosition)
                }, {
                    duration: 800,
                    easing: 'swing'
                });

                // Close mobile menu if open
                samiraTheme.closeMobileMenu();

                // Update active nav link
                updateActiveNavLink(targetId);
            }
        });
    }

    // Scroll animations
    function initScrollAnimations() {
        // Intersection Observer for fade-in animations
        if ('IntersectionObserver' in window) {
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, observerOptions);

            $('.section').each(function() {
                observer.observe(this);
            });
        } else {
            // Fallback for browsers without Intersection Observer
            $('.section').addClass('visible');
        }

        // Scroll spy for navigation
        $(window).on('scroll', throttle(updateActiveNavOnScroll, 100));
    }

    // Header scroll effects
    function initHeaderScroll() {
        let ticking = false;

        function updateHeader() {
            const scrollTop = $(window).scrollTop();
            const $header = $('.header');

            if (scrollTop > 100) {
                $header.addClass('scrolled');
            } else {
                $header.removeClass('scrolled');
            }

            ticking = false;
        }

        $(window).on('scroll', function() {
            if (!ticking) {
                requestAnimationFrame(updateHeader);
                ticking = true;
            }
        });
    }

    // Mobile menu functionality
    function initMobileMenu() {
        const $toggle = $('.menu-toggle');
        const $menu = $('.nav-menu');

        $toggle.on('click', function() {
            const isOpen = $toggle.attr('aria-expanded') === 'true';

            if (isOpen) {
                closeMobileMenu();
            } else {
                openMobileMenu();
            }
        });

        // Close menu on link click
        $('.nav-menu a').on('click', function() {
            closeMobileMenu();
        });

        // Close menu on escape key
        $(document).on('keydown', function(e) {
            if (e.keyCode === 27 && $menu.hasClass('active')) {
                closeMobileMenu();
            }
        });

        // Close menu on outside click
        $(document).on('click', function(e) {
            if ($menu.hasClass('active') &&
                !$(e.target).closest('.nav-menu, .menu-toggle').length) {
                closeMobileMenu();
            }
        });

        function openMobileMenu() {
            $toggle.attr('aria-expanded', 'true');
            $menu.addClass('active');
        }

        function closeMobileMenu() {
            $toggle.attr('aria-expanded', 'false');
            $menu.removeClass('active');
        }
    }

    // Newsletter form functionality
    function initNewsletterForm() {
        const $form = $('#newsletter-form');
        const $message = $('#newsletter-message');

        if (!$form.length) return;

        $form.on('submit', function(e) {
            e.preventDefault();

            const $submit = $form.find('.newsletter__submit');
            const $btnText = $submit.find('.btn-text');
            const $btnLoading = $submit.find('.btn-loading');

            // Validate form
            const name = $form.find('input[name="name"]').val().trim();
            const email = $form.find('input[name="email"]').val().trim();

            if (!name) {
                showMessage('error', samira_ajax.strings.required + ': ' + samira_ajax.strings.name);
                return;
            }

            if (!email || !isValidEmail(email)) {
                showMessage('error', samira_ajax.strings.email_invalid);
                return;
            }

            // Show loading state
            $submit.prop('disabled', true);
            $btnText.hide();
            $btnLoading.show();

            // AJAX request
            $.ajax({
                url: samira_ajax.ajax_url,
                type: 'POST',
                dataType: 'json',
                data: {
                    action: 'samira_newsletter_signup',
                    nonce: samira_ajax.nonce,
                    name: name,
                    email: email
                },
                success: function(response) {
                    if (response.success) {
                        showMessage('success', response.data.message || samira_ajax.strings.success);
                        $form[0].reset();
                    } else {
                        showMessage('error', response.data.message || samira_ajax.strings.error);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(samira_ajax.strings.ajax_error, error);
                    showMessage('error', samira_ajax.strings.error);
                },
                complete: function() {
                    // Reset button state
                    $submit.prop('disabled', false);
                    $btnText.show();
                    $btnLoading.hide();
                }
            });
        });

        function showMessage(type, text) {
            $message.removeClass('success error show')
                   .addClass(type)
                   .text(text);

            // Trigger reflow to ensure class is applied
            $message[0].offsetHeight;

            $message.addClass('show');

            // Auto-hide after 5 seconds
            setTimeout(function() {
                $message.removeClass('show');
            }, 5000);
        }

        function isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        }
    }

    // Scroll to top functionality
    function initScrollToTop() {
        const $scrollTop = $('<button class="scroll-to-top" aria-label="' + samira_ajax.strings.scroll_to_top + '"><svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4l8 8h-6v8h-4v-8H4l8-8z"/></svg></button>');

        $('body').append($scrollTop);

        $scrollTop.on('click', function() {
            $('html, body').animate({
                scrollTop: 0
            }, 800);
        });

        $(window).on('scroll', throttle(function() {
            if ($(window).scrollTop() > 500) {
                $scrollTop.addClass('visible');
            } else {
                $scrollTop.removeClass('visible');
            }
        }, 100));
    }

    // Lazy loading for images
    function initLazyLoading() {
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver(function(entries) {
                entries.forEach(function(entry) {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        const src = img.dataset.src;

                        if (src) {
                            img.src = src;
                            img.classList.remove('lazy');
                            img.classList.add('loaded');
                            imageObserver.unobserve(img);
                        }
                    }
                });
            });

            $('img[data-src]').each(function() {
                imageObserver.observe(this);
            });
        }
    }

    // Portfolio tabs filtering
    function initPortfolioTabs() {
        const $container = $('.portfolio-tabs');
        if (!$container.length) {
            return;
        }

        const $tabs = $container.find('.portfolio-tab');
        const $items = $('.portfolio-item');
        const current = $container.data('current') || 'all';

        $tabs.on('click', function() {
            const term = $(this).data('term');
            $tabs.removeClass('active');
            $(this).addClass('active');

            if (term === 'all') {
                $items.show();
            } else {
                $items.hide().filter(function() {
                    const terms = $(this).data('terms');
                    if (!terms) {
                        return false;
                    }
                    return terms.split(' ').includes(term);
                }).show();
            }
        });

        $container.find(`[data-term="${current}"]`).trigger('click');
    }

    // Update active navigation link
    function updateActiveNavLink(targetId) {
        $('.nav-menu a').removeClass('active');
        $(`[href="${targetId}"]`).addClass('active');
    }

    // Scroll spy for navigation
    function updateActiveNavOnScroll() {
        const scrollTop = $(window).scrollTop();
        const headerHeight = $('.header').outerHeight() || 80;

        $('.section[id]').each(function() {
            const $section = $(this);
            const sectionTop = $section.offset().top - headerHeight - 100;
            const sectionBottom = sectionTop + $section.outerHeight();

            if (scrollTop >= sectionTop && scrollTop < sectionBottom) {
                const sectionId = '#' + $section.attr('id');
                updateActiveNavLink(sectionId);
                return false; // Break the loop
            }
        });
    }

    // Utility: Throttle function
    function throttle(func, wait) {
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

    // Utility: Debounce function
    function debounce(func, wait, immediate) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                timeout = null;
                if (!immediate) func(...args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func(...args);
        };
    }

    // Handle form errors gracefully
    $(document).on('ajaxError', function(event, xhr, settings, error) {
        if (settings.url === samira_ajax.ajax_url) {
            console.error(samira_ajax.strings.ajax_error, error);
        }
    });

    // Accessibility improvements
    function initA11y() {
        // Skip links
        $('.skip-link').on('click', function(e) {
            const target = $(this).attr('href');
            const $target = $(target);

            if ($target.length) {
                e.preventDefault();
                $target.focus();

                if (!$target.attr('tabindex')) {
                    $target.attr('tabindex', '-1');
                }
            }
        });

        // Focus management for mobile menu
        $('.menu-toggle').on('click', function() {
            setTimeout(function() {
                if ($('.nav-menu').hasClass('active')) {
                    $('.nav-menu a:first').focus();
                }
            }, 300);
        });
    }

    // Initialize accessibility features
    initA11y();

    // Export functions for external use
    window.samiraTheme = {
        initTheme: initTheme,
        closeMobileMenu: function() {
            $('.menu-toggle').attr('aria-expanded', 'false');
            $('.nav-menu').removeClass('active');
        }
    };

})(jQuery);
