/**
 * Admin Scripts for Samira Theme
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

jQuery(document).ready(function($) {
    'use strict';

    console.log(samira_admin.strings.admin_loaded);

    // Initialize all admin functionality
    initImageUpload();
    initColorPicker();
    initFormValidation();
    initTooltips();
    initAutoSave();
    initTabs();

    /**
     * Image upload functionality
     */
    function initImageUpload() {
        $('.samira-upload-image').on('click', function(e) {
            e.preventDefault();

            const $button = $(this);
            const $container = $button.closest('.samira-image-upload');
            const $input = $container.find('input[type="hidden"]');
            const $preview = $container.find('.samira-image-preview');

            // Create WordPress media uploader
            const mediaUploader = wp.media({
                title: samira_admin.strings.select_image,
                button: {
                    text: samira_admin.strings.use_image
                },
                multiple: false,
                library: {
                    type: 'image'
                }
            });

            // On select
            mediaUploader.on('select', function() {
                const attachment = mediaUploader.state().get('selection').first().toJSON();

                $input.val(attachment.url);
                $preview.html('<img src="' + attachment.url + '" alt="' + samira_admin.strings.selected_image_alt + '" style="max-width: 200px; height: auto;" />');

                // Show success message
                showNotification(samira_admin.strings.image_uploaded, 'success');
            });

            // Open uploader
            mediaUploader.open();
        });

        // Remove image
        $('.samira-remove-image').on('click', function(e) {
            e.preventDefault();

            const $button = $(this);
            const $container = $button.closest('.samira-image-upload');
            const $input = $container.find('input[type="hidden"]');
            const $preview = $container.find('.samira-image-preview');

            $input.val('');
            $preview.empty();

            showNotification(samira_admin.strings.image_removed, 'info');
        });
    }

    /**
     * Color picker initialization
     */
    function initColorPicker() {
        if ($.fn.wpColorPicker) {
            $('.samira-color-picker').wpColorPicker({
                change: function(event, ui) {
                    const color = ui.color.toString();
                    updateColorPreview(color);
                },
                clear: function() {
                    updateColorPreview('#D4A574');
                }
            });
        }

        function updateColorPreview(color) {
            $('.samira-preview-button').css('background-color', color);
            $('.samira-preview-link').css('color', color);

            // Update CSS custom property if supported
            if (CSS.supports('color', 'var(--test)')) {
                document.documentElement.style.setProperty('--samira-accent-preview', color);
            }
        }
    }

    /**
     * Form validation
     */
    function initFormValidation() {
        $('.samira-form').on('submit', function(e) {
            let hasErrors = false;
            const $form = $(this);

            // Clear previous errors
            $form.find('.error').removeClass('error');
            $form.find('.error-message').remove();

            // Validate required fields
            $form.find('[required]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();

                if (!value) {
                    $field.addClass('error').after('<span class="error-message" style="color: #ef4444; font-size: 0.9rem; display: block; margin-top: 0.25rem;">' + samira_admin.strings.required_field + '</span>');
                    hasErrors = true;
                }
            });

            // Validate email fields
            $form.find('input[type="email"]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();

                if (value && !isValidEmail(value)) {
                    $field.addClass('error').after('<span class="error-message" style="color: #ef4444; font-size: 0.9rem; display: block; margin-top: 0.25rem;">' + samira_admin.strings.email_invalid + '</span>');
                    hasErrors = true;
                }
            });

            // Validate URL fields
            $form.find('input[type="url"]').each(function() {
                const $field = $(this);
                const value = $field.val().trim();

                if (value && !isValidURL(value)) {
                    $field.addClass('error').after('<span class="error-message" style="color: #ef4444; font-size: 0.9rem; display: block; margin-top: 0.25rem;">' + samira_admin.strings.url_invalid + '</span>');
                    hasErrors = true;
                }
            });

            if (hasErrors) {
                e.preventDefault();
                showNotification(samira_admin.strings.form_error, 'error');

                // Scroll to first error
                const $firstError = $form.find('.error').first();
                if ($firstError.length) {
                    $('html, body').animate({
                        scrollTop: $firstError.offset().top - 100
                    }, 500);
                }
            } else {
                // Show loading state
                const $submitBtn = $form.find('input[type="submit"], button[type="submit"]');
                $submitBtn.prop('disabled', true).addClass('samira-loading');

                if ($submitBtn.is('input')) {
                    $submitBtn.data('original-value', $submitBtn.val()).val(samira_admin.strings.saving);
                } else {
                    $submitBtn.data('original-text', $submitBtn.text()).text(samira_admin.strings.saving);
                }
            }
        });
    }

    /**
     * Tooltip initialization
     */
    function initTooltips() {
        $('[data-tooltip]').each(function() {
            const $element = $(this);
            const tooltip = $element.attr('data-tooltip');

            $element.on('mouseenter', function() {
                if ($('.samira-tooltip').length === 0) {
                    const $tooltip = $('<div class="samira-tooltip" style="position: absolute; background: #1f2937; color: white; padding: 0.5rem 0.75rem; border-radius: 4px; font-size: 0.8rem; z-index: 1000; pointer-events: none; white-space: nowrap;">' + tooltip + '</div>');
                    $('body').append($tooltip);
                }
            });

            $element.on('mousemove', function(e) {
                $('.samira-tooltip').css({
                    left: e.pageX + 10,
                    top: e.pageY - 30
                });
            });

            $element.on('mouseleave', function() {
                $('.samira-tooltip').remove();
            });
        });
    }

    /**
     * Auto-save functionality
     */
    function initAutoSave() {
        let autoSaveTimeout;

        $('.samira-form input, .samira-form textarea, .samira-form select').on('input change', function() {
            clearTimeout(autoSaveTimeout);

            // Show unsaved changes indicator
            if (!$('.unsaved-changes').length) {
                $('.samira-submit').prepend('<span class="unsaved-changes" style="color: #f59e0b; font-style: italic; margin-right: 1rem;">' + samira_admin.strings.unsaved_changes + '</span>');
            }

            // Auto-save after 30 seconds of inactivity
            autoSaveTimeout = setTimeout(function() {
                autoSaveForm();
            }, 30000);
        });

        function autoSaveForm() {
            const $form = $('.samira-form');
            const formData = $form.serialize() + '&auto_save=1';

            $.ajax({
                url: $form.attr('action') || window.location.href,
                type: 'POST',
                data: formData,
                success: function(response) {
                    $('.unsaved-changes').remove();
                    showNotification(samira_admin.strings.draft_saved, 'info', 3000);
                },
                error: function() {
                    console.warn(samira_admin.strings.autosave_failed);
                }
            });
        }
    }

    /**
     * Tab functionality
     */
    function initTabs() {
        // Handle tab clicks with smooth transition
        $('.nav-tab').on('click', function(e) {
            if ($(this).attr('href').indexOf('#') !== -1) {
                e.preventDefault();

                const $tab = $(this);
                const targetTab = $tab.attr('href').split('tab=')[1];

                // Add loading state
                $tab.addClass('samira-loading');

                // Redirect with smooth transition
                setTimeout(function() {
                    window.location.href = $tab.attr('href');
                }, 150);
            }
        });

        // Add fade-in animation to tab content
        $('.samira-tab-content').addClass('fade-in');
    }

    /**
     * Utility functions
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }

    function isValidURL(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    function showNotification(message, type = 'info', duration = 5000) {
        const $notification = $('<div class="samira-notification" style="position: fixed; top: 32px; right: 20px; z-index: 10000; padding: 1rem 1.5rem; border-radius: 6px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); font-weight: 500; transform: translateX(100%); transition: transform 0.3s ease;">' + message + '</div>');

        // Set notification style based on type
        switch (type) {
            case 'success':
                $notification.css({
                    'background': '#ecfdf5',
                    'color': '#065f46',
                    'border': '1px solid #a7f3d0'
                });
                break;
            case 'error':
                $notification.css({
                    'background': '#fef2f2',
                    'color': '#7f1d1d',
                    'border': '1px solid #fca5a5'
                });
                break;
            case 'warning':
                $notification.css({
                    'background': '#fffbeb',
                    'color': '#78350f',
                    'border': '1px solid #fed7aa'
                });
                break;
            default:
                $notification.css({
                    'background': '#eff6ff',
                    'color': '#1e40af',
                    'border': '1px solid #93c5fd'
                });
        }

        $('body').append($notification);

        // Animate in
        setTimeout(function() {
            $notification.css('transform', 'translateX(0)');
        }, 100);

        // Auto-hide
        setTimeout(function() {
            $notification.css('transform', 'translateX(100%)');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        }, duration);

        // Click to dismiss
        $notification.on('click', function() {
            $(this).css('transform', 'translateX(100%)');
            setTimeout(function() {
                $notification.remove();
            }, 300);
        });
    }

    /**
     * Real-time preview functionality
     */
    function initLivePreview() {
        // This would require iframe communication or AJAX updates
        // For now, we'll just show the "Open Site" button
        $('.live-preview-btn').on('click', function() {
            window.open($(this).data('preview-url'), 'samira-preview', 'width=1200,height=800,scrollbars=yes,resizable=yes');
        });
    }

    /**
     * Keyboard shortcuts
     */
    $(document).on('keydown', function(e) {
        // Ctrl+S or Cmd+S to save
        if ((e.ctrlKey || e.metaKey) && e.which === 83) {
            e.preventDefault();
            $('.samira-form').submit();
        }

        // Escape to close notifications
        if (e.which === 27) {
            $('.samira-notification').click();
        }
    });

    /**
     * Handle form reset
     */
    window.samiraResetOptions = function() {
        if (confirm(samira_admin.strings.confirm_reset)) {
            const $button = $('#reset-options');
            $button.prop('disabled', true).text(samira_admin.strings.resetting);

            $.post(ajaxurl, {
                action: 'samira_reset_options',
                nonce: $('#samira_nonce').val()
            }, function(response) {
                if (response.success) {
                    showNotification(samira_admin.strings.reset_success, 'success');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                } else {
                    showNotification(samira_admin.strings.reset_error, 'error');
                    $button.prop('disabled', false).text(samira_admin.strings.reset_button);
                }
            }).fail(function() {
                showNotification(samira_admin.strings.connection_error, 'error');
                $button.prop('disabled', false).text(samira_admin.strings.reset_button);
            });
        }
    };

    // Initialize live preview if available
    initLivePreview();

    // Show welcome message for new installations
    if (window.location.href.indexOf('samira-theme-settings') !== -1 && !localStorage.getItem('samira-admin-visited')) {
        setTimeout(function() {
            showNotification(samira_admin.strings.welcome_message, 'info', 8000);
            localStorage.setItem('samira-admin-visited', 'true');
        }, 1000);
    }
});
