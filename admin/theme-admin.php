<?php
/**
 * Theme Administration Panel (English Version)
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu
 */
function samira_add_admin_menu() {
    add_menu_page(
        __('Samira Theme', 'samira-theme'),
        __('Samira Theme', 'samira-theme'),
        'manage_options',
        'samira-theme-settings',
        'samira_admin_page',
        'dashicons-admin-customizer',
        30
    );

    add_submenu_page(
        'samira-theme-settings',
        __('General Settings', 'samira-theme'),
        __('Settings', 'samira-theme'),
        'manage_options',
        'samira-theme-settings',
        'samira_admin_page'
    );

    add_submenu_page(
        'samira-theme-settings',
        __('Newsletter', 'samira-theme'),
        __('Newsletter', 'samira-theme'),
        'manage_options',
        'samira-newsletter-settings',
        'samira_newsletter_page'
    );

    add_submenu_page(
        'samira-theme-settings',
        __('Statistics', 'samira-theme'),
        __('Statistics', 'samira-theme'),
        'manage_options',
        'samira-theme-stats',
        'samira_stats_page'
    );

    add_submenu_page(
        'samira-theme-settings',
        __('Import/Export', 'samira-theme'),
        __('Import/Export', 'samira-theme'),
        'manage_options',
        'samira-theme-import-export',
        'samira_import_export_page'
    );
}
add_action('admin_menu', 'samira_add_admin_menu');

/**
 * Main admin page
 */
function samira_admin_page() {
    // Handle form submission
    if ($_POST && wp_verify_nonce($_POST['samira_nonce'], 'samira_settings')) {
        samira_save_settings($_POST);
        echo '<div class="notice notice-success"><p>' . __('Settings saved successfully!', 'samira-theme') . '</p></div>';
    }

    $current_tab = $_GET['tab'] ?? 'general';
    ?>

    <div class="wrap samira-admin">
        <h1><?php _e('Samira Theme Settings', 'samira-theme'); ?></h1>

        <nav class="nav-tab-wrapper">
            <a href="?page=samira-theme-settings&tab=general" class="nav-tab <?php echo $current_tab === 'general' ? 'nav-tab-active' : ''; ?>">
                <?php _e('General', 'samira-theme'); ?>
            </a>
            <a href="?page=samira-theme-settings&tab=hero" class="nav-tab <?php echo $current_tab === 'hero' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Hero Section', 'samira-theme'); ?>
            </a>
            <a href="?page=samira-theme-settings&tab=about" class="nav-tab <?php echo $current_tab === 'about' ? 'nav-tab-active' : ''; ?>">
                <?php _e('About Section', 'samira-theme'); ?>
            </a>
            <a href="?page=samira-theme-settings&tab=about-page" class="nav-tab <?php echo $current_tab === 'about-page' ? 'nav-tab-active' : ''; ?>">
                <?php _e('About Page', 'samira-theme'); ?>
            </a>
            <a href="?page=samira-theme-settings&tab=books" class="nav-tab <?php echo $current_tab === 'books' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Books Section', 'samira-theme'); ?>
            </a>
            <a href="?page=samira-theme-settings&tab=social" class="nav-tab <?php echo $current_tab === 'social' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Social Media', 'samira-theme'); ?>
            </a>
            <a href="?page=samira-theme-settings&tab=style" class="nav-tab <?php echo $current_tab === 'social' ? 'nav-tab-active' : ''; ?>">
                <?php _e('Style', 'samira-theme'); ?>
            </a>
        </nav>

        <form method="post" action="" enctype="multipart/form-data" class="samira-form">
            <?php wp_nonce_field('samira_settings', 'samira_nonce'); ?>

            <div class="samira-content">
                <div class="samira-main">
                    <?php
                    switch ($current_tab) {
                        case 'hero':
                            samira_render_hero_tab();
                            break;
                        case 'about':
                            samira_render_about_tab();
                            break;
                        case 'about-page':
                            samira_render_about_page_tab();
                            break;
                        case 'books':
                            samira_render_books_tab();
                            break;
                        case 'social':
                            samira_render_social_tab();
                            break;
                        case 'style':
                            samira_render_style_tab();
                            break;
                        default:
                            samira_render_general_tab();
                    }
                    ?>
                </div>

                <div class="samira-sidebar">
                    <div class="samira-card">
                        <h3><?php _e('Live Preview', 'samira-theme'); ?></h3>
                        <p><?php _e('View changes in real time:', 'samira-theme'); ?></p>
                        <a href="<?php echo home_url(); ?>" target="_blank" class="button button-secondary">
                            <?php _e('Open Site', 'samira-theme'); ?>
                        </a>
                        <br><br>
                        <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                            <?php _e('WordPress Customizer', 'samira-theme'); ?>
                        </a>
                    </div>

                    <div class="samira-card">
                        <h3><?php _e('Quick Actions', 'samira-theme'); ?></h3>
                        <p>
                            <a href="?page=samira-newsletter-settings" class="button button-secondary">
                                <?php _e('Configure Newsletter', 'samira-theme'); ?>
                            </a>
                        </p>
                        <p>
                            <a href="?page=samira-theme-stats" class="button button-secondary">
                                <?php _e('View Statistics', 'samira-theme'); ?>
                            </a>
                        </p>
                        <p>
                            <button type="button" class="button button-secondary" onclick="samiraResetOptions()">
                                <?php _e('Reset to Default Settings', 'samira-theme'); ?>
                            </button>
                        </p>
                    </div>
                </div>
            </div>

            <div class="samira-submit">
                <?php submit_button(__('Save Settings', 'samira-theme'), 'primary', 'submit', false); ?>
            </div>
        </form>
    </div>

    <script>
    function samiraResetOptions() {
        if (confirm('Are you sure you want to reset all settings to default values? This action cannot be undone.')) {
            jQuery.post(ajaxurl, {
                action: 'samira_reset_options',
                nonce: '<?php echo wp_create_nonce('samira_reset'); ?>'
            }, function(response) {
                if (response.success) {
                    alert('Settings reset successfully!');
                    location.reload();
                } else {
                    alert('Error resetting settings.');
                }
            });
        }
    }
    </script>

    <?php
}

/**
 * Render General Tab
 */
function samira_render_general_tab() {
    ?>
    <div class="samira-tab-content">
        <h2><?php _e('General Settings', 'samira-theme'); ?></h2>

        <h3><?php _e('Logo Settings', 'samira-theme'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label><?php _e('Logo Type', 'samira-theme'); ?></label>
                </th>
                <td>
                    <fieldset>
                        <label>
                            <input type="radio" name="samira_logo_type" value="text"
                                   <?php checked(get_option('samira_logo_type', 'text'), 'text'); ?> />
                            <?php _e('Text Logo', 'samira-theme'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="samira_logo_type" value="image"
                                   <?php checked(get_option('samira_logo_type', 'text'), 'image'); ?> />
                            <?php _e('Image Logo', 'samira-theme'); ?>
                        </label>
                    </fieldset>
                    <p class="description"><?php _e('Choose between a text-based logo or an image logo', 'samira-theme'); ?></p>
                </td>
            </tr>

            <tr class="logo-text-option">
                <th scope="row">
                    <label for="samira_logo_text"><?php _e('Logo Text', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_logo_text" name="samira_logo_text"
                           value="<?php echo esc_attr(get_option('samira_logo_text', __( 'SM', 'samira-theme' ))); ?>"
                           class="regular-text" />
                    <p class="description"><?php _e('Text to display as your logo', 'samira-theme'); ?></p>
                </td>
            </tr>

            <tr class="logo-image-option">
                <th scope="row">
                    <label for="samira_logo_image"><?php _e('Logo Image', 'samira-theme'); ?></label>
                </th>
                <td>
                    <div class="samira-image-upload">
                        <input type="hidden" id="samira_logo_image" name="samira_logo_image"
                               value="<?php echo esc_url(get_option('samira_logo_image', '')); ?>" />
                        <div class="samira-image-preview">
                            <?php
                            $logo_image = get_option('samira_logo_image', '');
                            if ($logo_image): ?>
                                <img src="<?php echo esc_url($logo_image); ?>" alt="Logo" style="max-width: 200px; max-height: 80px; height: auto;" />
                            <?php endif; ?>
                        </div>
                        <p>
                            <button type="button" class="button samira-upload-image"><?php _e('Upload Logo', 'samira-theme'); ?></button>
                            <button type="button" class="button samira-remove-image"><?php _e('Remove Logo', 'samira-theme'); ?></button>
                        </p>
                        <p class="description"><?php _e('Recommended size: 200x60px (PNG format with transparent background recommended)', 'samira-theme'); ?></p>
                    </div>
                </td>
            </tr>
        </table>

        <script>
        jQuery(document).ready(function($) {
            function toggleLogoOptions() {
                var logoType = $('input[name="samira_logo_type"]:checked').val();
                if (logoType === 'text') {
                    $('.logo-text-option').show();
                    $('.logo-image-option').hide();
                } else {
                    $('.logo-text-option').hide();
                    $('.logo-image-option').show();
                }
            }

            toggleLogoOptions();
            $('input[name="samira_logo_type"]').on('change', toggleLogoOptions);
        });
        </script>

        <h3><?php _e('Site Information', 'samira-theme'); ?></h3>
        <table class="form-table">

            <tr>
                <th scope="row">
                    <label for="samira_copyright_name"><?php _e('Copyright Name', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_copyright_name" name="samira_copyright_name" 
                           value="<?php echo esc_attr(get_option('samira_copyright_name', __( 'Samira Mahmoodi', 'samira-theme' ))); ?>"
                           class="regular-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_footer_text"><?php _e('Footer Text', 'samira-theme'); ?></label>
                </th>
                <td>
                    <textarea id="samira_footer_text" name="samira_footer_text" rows="3" class="large-text"><?php echo esc_textarea(get_option('samira_footer_text', __( 'Writer and artist. Art is my safe haven for self-expression.', 'samira-theme' ))); ?></textarea>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_contact_email"><?php _e('Contact Email', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="email" id="samira_contact_email" name="samira_contact_email" 
                           value="<?php echo esc_attr(get_option('samira_contact_email', '')); ?>" 
                           class="regular-text" />
                </td>
            </tr>
        </table>

        <h3><?php _e('Performance Optimizations', 'samira-theme'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Disable Emojis', 'samira-theme'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="samira_disable_emojis" value="1" 
                               <?php checked(get_option('samira_disable_emojis', true)); ?> />
                        <?php _e('Disable WordPress emojis to improve performance', 'samira-theme'); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Clean Head', 'samira-theme'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="samira_clean_head" value="1" 
                               <?php checked(get_option('samira_clean_head', true)); ?> />
                        <?php _e('Remove unnecessary tags from head for cleaner code', 'samira-theme'); ?>
                    </label>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Lazy Loading', 'samira-theme'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="samira_lazy_loading" value="1" 
                               <?php checked(get_option('samira_lazy_loading', true)); ?> />
                        <?php _e('Enable lazy loading for images', 'samira-theme'); ?>
                    </label>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Render Hero Tab
 */
function samira_render_hero_tab() {
    ?>
    <div class="samira-tab-content">
        <h2><?php _e('Hero Section', 'samira-theme'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="samira_hero_title"><?php _e('Main Title', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_hero_title" name="samira_hero_title" 
                           value="<?php echo esc_attr(get_option('samira_hero_title', __( 'Samira Mahmoodi', 'samira-theme' ))); ?>"
                           class="large-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_hero_subtitle"><?php _e('Subtitle', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_hero_subtitle" name="samira_hero_subtitle" 
                           value="<?php echo esc_attr(get_option('samira_hero_subtitle', __( 'Writing, Art, Rebirth', 'samira-theme' ))); ?>"
                           class="large-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_hero_image"><?php _e('Hero Image', 'samira-theme'); ?></label>
                </th>
                <td>
                    <div class="samira-image-upload">
                        <input type="hidden" id="samira_hero_image" name="samira_hero_image" 
                               value="<?php echo esc_url(get_option('samira_hero_image', '')); ?>" />
                        <div class="samira-image-preview">
                            <?php 
                            $hero_image = get_option('samira_hero_image', '');
                            if ($hero_image): ?>
                                <img src="<?php echo esc_url($hero_image); ?>" alt="Hero Image" style="max-width: 200px; height: auto;" />
                            <?php endif; ?>
                        </div>
                        <p>
                            <button type="button" class="button samira-upload-image"><?php _e('Upload Image', 'samira-theme'); ?></button>
                            <button type="button" class="button samira-remove-image"><?php _e('Remove Image', 'samira-theme'); ?></button>
                        </p>
                        <p class="description"><?php _e('Optimal size: 600x600px', 'samira-theme'); ?></p>
                    </div>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Render About Tab
 */
function samira_render_about_tab() {
    ?>
    <div class="samira-tab-content">
        <h2><?php _e('About Me Section', 'samira-theme'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="samira_about_title"><?php _e('Section Title', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_about_title" name="samira_about_title" 
                           value="<?php echo esc_attr(get_option('samira_about_title', __( 'About Me', 'samira-theme' ))); ?>"
                           class="large-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_about_content"><?php _e('About Content', 'samira-theme'); ?></label>
                </th>
                <td>
                    <?php
                    $about_content = get_option('samira_about_content', __( 'Samira Mahmoodi began writing shortly after graduating from college...', 'samira-theme' ));
                    wp_editor($about_content, 'samira_about_content', array(
                        'textarea_rows' => 10,
                        'media_buttons' => false,
                        'teeny' => true
                    ));
                    ?>
                    <p class="description"><?php _e('Write your biography and personal story', 'samira-theme'); ?></p>
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Render About Page Tab
 */
function samira_render_about_page_tab() {
    ?>
    <div class="samira-tab-content">
        <h2><?php _e('About Page Settings', 'samira-theme'); ?></h2>

        <p class="description">
            <?php _e('Configure the standalone About page with a custom image gallery. Create a new page and select the "About Page" template.', 'samira-theme'); ?>
        </p>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="samira_about_page_title"><?php _e('Page Title', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_about_page_title" name="samira_about_page_title"
                           value="<?php echo esc_attr(get_option('samira_about_page_title', __( 'About Me', 'samira-theme' ))); ?>"
                           class="large-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_about_page_description"><?php _e('Page Description', 'samira-theme'); ?></label>
                </th>
                <td>
                    <?php
                    $about_page_description = get_option('samira_about_page_description', '');
                    wp_editor($about_page_description, 'samira_about_page_description', array(
                        'textarea_rows' => 10,
                        'media_buttons' => true,
                        'teeny' => false
                    ));
                    ?>
                    <p class="description"><?php _e('Detailed content for your About page', 'samira-theme'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label><?php _e('Image Gallery', 'samira-theme'); ?></label>
                </th>
                <td>
                    <div class="samira-gallery-upload">
                        <input type="hidden" id="samira_about_page_gallery" name="samira_about_page_gallery"
                               value="<?php echo esc_attr(implode(',', get_option('samira_about_page_gallery', array()))); ?>" />

                        <div class="samira-gallery-preview" id="gallery-preview">
                            <?php
                            $gallery_images = get_option('samira_about_page_gallery', array());
                            if (!empty($gallery_images) && is_array($gallery_images)) {
                                foreach ($gallery_images as $image_id) {
                                    if ($image_id) {
                                        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                                        if ($image_url) {
                                            echo '<div class="gallery-image-item" data-id="' . esc_attr($image_id) . '">';
                                            echo '<img src="' . esc_url($image_url) . '" alt="" />';
                                            echo '<button type="button" class="remove-gallery-image" title="' . esc_attr__('Remove', 'samira-theme') . '">&times;</button>';
                                            echo '</div>';
                                        }
                                    }
                                }
                            }
                            ?>
                        </div>

                        <p>
                            <button type="button" class="button samira-upload-gallery" id="upload-gallery-btn">
                                <?php _e('Add Images to Gallery', 'samira-theme'); ?>
                            </button>
                            <button type="button" class="button samira-clear-gallery">
                                <?php _e('Clear All Images', 'samira-theme'); ?>
                            </button>
                        </p>
                        <p class="description">
                            <?php _e('You can add up to 50 images. Images will be displayed in a responsive 3-column grid. The first 9 images will be shown initially, with a "Load More" button to display additional images.', 'samira-theme'); ?>
                        </p>
                        <p class="description">
                            <strong><?php _e('Current images:', 'samira-theme'); ?></strong>
                            <span id="gallery-count"><?php echo count($gallery_images); ?></span> / 50
                        </p>
                    </div>
                </td>
            </tr>
        </table>
    </div>

    <style>
    .samira-gallery-preview {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 10px;
        margin-bottom: 15px;
        padding: 15px;
        background: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 4px;
        min-height: 120px;
    }

    .gallery-image-item {
        position: relative;
        cursor: move;
    }

    .gallery-image-item img {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        display: block;
    }

    .gallery-image-item .remove-gallery-image {
        position: absolute;
        top: -5px;
        right: -5px;
        background: #dc3232;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        cursor: pointer;
        font-size: 18px;
        line-height: 1;
        display: none;
    }

    .gallery-image-item:hover .remove-gallery-image {
        display: block;
    }
    </style>

    <script>
    jQuery(document).ready(function($) {
        var galleryFrame;
        var maxImages = 50;

        // Upload gallery images
        $('#upload-gallery-btn').on('click', function(e) {
            e.preventDefault();

            var currentCount = $('#gallery-preview .gallery-image-item').length;
            if (currentCount >= maxImages) {
                alert('<?php echo esc_js(__('Maximum 50 images allowed', 'samira-theme')); ?>');
                return;
            }

            if (galleryFrame) {
                galleryFrame.open();
                return;
            }

            galleryFrame = wp.media({
                title: '<?php echo esc_js(__('Select Images for Gallery', 'samira-theme')); ?>',
                button: {
                    text: '<?php echo esc_js(__('Add to Gallery', 'samira-theme')); ?>'
                },
                multiple: true
            });

            galleryFrame.on('select', function() {
                var selection = galleryFrame.state().get('selection');
                var currentCount = $('#gallery-preview .gallery-image-item').length;
                var remaining = maxImages - currentCount;

                var added = 0;
                selection.map(function(attachment) {
                    if (added >= remaining) {
                        return;
                    }

                    attachment = attachment.toJSON();
                    var imageId = attachment.id;
                    var imageUrl = attachment.sizes && attachment.sizes.thumbnail ? attachment.sizes.thumbnail.url : attachment.url;

                    // Check if image already exists
                    if ($('#gallery-preview').find('[data-id="' + imageId + '"]').length === 0) {
                        $('#gallery-preview').append(
                            '<div class="gallery-image-item" data-id="' + imageId + '">' +
                            '<img src="' + imageUrl + '" alt="" />' +
                            '<button type="button" class="remove-gallery-image" title="<?php echo esc_attr__('Remove', 'samira-theme'); ?>">&times;</button>' +
                            '</div>'
                        );
                        added++;
                    }
                });

                updateGalleryInput();
            });

            galleryFrame.open();
        });

        // Remove gallery image
        $(document).on('click', '.remove-gallery-image', function(e) {
            e.preventDefault();
            $(this).parent().fadeOut(300, function() {
                $(this).remove();
                updateGalleryInput();
            });
        });

        // Clear all gallery images
        $('.samira-clear-gallery').on('click', function(e) {
            e.preventDefault();
            if (confirm('<?php echo esc_js(__('Are you sure you want to remove all images?', 'samira-theme')); ?>')) {
                $('#gallery-preview').empty();
                updateGalleryInput();
            }
        });

        // Update hidden input with gallery IDs
        function updateGalleryInput() {
            var ids = [];
            $('#gallery-preview .gallery-image-item').each(function() {
                ids.push($(this).data('id'));
            });
            $('#samira_about_page_gallery').val(ids.join(','));
            $('#gallery-count').text(ids.length);
        }

        // Make gallery sortable
        if ($.fn.sortable) {
            $('#gallery-preview').sortable({
                update: function() {
                    updateGalleryInput();
                }
            });
        }
    });
    </script>
    <?php
}

/**
 * Render Books Tab
 */
function samira_render_books_tab() {
    ?>
    <div class="samira-tab-content">
        <h2><?php _e('Books Section', 'samira-theme'); ?></h2>

        <div class="notice notice-info">
            <p><?php _e('You can also add books using the "Books" post type for more advanced management.', 'samira-theme'); ?>
               <a href="<?php echo admin_url('edit.php?post_type=books'); ?>"><?php _e('Manage Books', 'samira-theme'); ?></a></p>
        </div>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="samira_book_title"><?php _e('Main Book Title', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_book_title" name="samira_book_title" 
                           value="<?php echo esc_attr(get_option('samira_book_title', __( 'To Water Her Garden: A journey of self-discovery', 'samira-theme' ))); ?>"
                           class="large-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_book_year"><?php _e('Publication Year', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="text" id="samira_book_year" name="samira_book_year" 
                           value="<?php echo esc_attr(get_option('samira_book_year', __( '2019', 'samira-theme' ))); ?>"
                           class="regular-text" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_book_description"><?php _e('Book Description', 'samira-theme'); ?></label>
                </th>
                <td>
                    <textarea id="samira_book_description" name="samira_book_description" rows="4" class="large-text"><?php echo esc_textarea(get_option('samira_book_description', __( 'In this space I unveiled the reasons behind my sadness...', 'samira-theme' ))); ?></textarea>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_book_cover"><?php _e('Book Cover', 'samira-theme'); ?></label>
                </th>
                <td>
                    <div class="samira-image-upload">
                        <input type="hidden" id="samira_book_cover" name="samira_book_cover" 
                               value="<?php echo esc_url(get_option('samira_book_cover', '')); ?>" />
                        <div class="samira-image-preview">
                            <?php 
                            $book_cover = get_option('samira_book_cover', '');
                            if ($book_cover): ?>
                                <img src="<?php echo esc_url($book_cover); ?>" alt="Book Cover" style="max-width: 150px; height: auto;" />
                            <?php endif; ?>
                        </div>
                        <p>
                            <button type="button" class="button samira-upload-image"><?php _e('Upload Cover', 'samira-theme'); ?></button>
                            <button type="button" class="button samira-remove-image"><?php _e('Remove Cover', 'samira-theme'); ?></button>
                        </p>
                        <p class="description"><?php _e('Optimal size: 400x600px (2:3 ratio)', 'samira-theme'); ?></p>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="samira_book_link_amazon"><?php _e('Amazon Link', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="url" id="samira_book_link_amazon" name="samira_book_link_amazon"
                           value="<?php echo esc_attr(get_option('samira_book_link_amazon', '')); ?>"
                           class="large-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="samira_book_link_bam"><?php _e('Books-A-Million Link', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="url" id="samira_book_link_bam" name="samira_book_link_bam"
                           value="<?php echo esc_attr(get_option('samira_book_link_bam', '')); ?>"
                           class="large-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="samira_book_link_bookshop"><?php _e('Bookshop Link', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="url" id="samira_book_link_bookshop" name="samira_book_link_bookshop"
                           value="<?php echo esc_attr(get_option('samira_book_link_bookshop', '')); ?>"
                           class="large-text" />
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="samira_book_link_bn"><?php _e('Barnes &amp; Noble Link', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="url" id="samira_book_link_bn" name="samira_book_link_bn"
                           value="<?php echo esc_attr(get_option('samira_book_link_bn', '')); ?>"
                           class="large-text" />
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Render Social Tab
 */
function samira_render_social_tab() {
    ?>
    <div class="samira-tab-content">
        <h2><?php _e('Social Media', 'samira-theme'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="samira_social_instagram">Instagram</label>
                </th>
                <td>
                    <input type="url" id="samira_social_instagram" name="samira_social_instagram" 
                           value="<?php echo esc_url(get_option('samira_social_instagram', '')); ?>" 
                           class="large-text" 
                           placeholder="https://instagram.com/username" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_social_goodreads">Goodreads</label>
                </th>
                <td>
                    <input type="url" id="samira_social_goodreads" name="samira_social_goodreads" 
                           value="<?php echo esc_url(get_option('samira_social_goodreads', '')); ?>" 
                           class="large-text" 
                           placeholder="https://goodreads.com/user/show/username" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_social_linkedin">LinkedIn</label>
                </th>
                <td>
                    <input type="url" id="samira_social_linkedin" name="samira_social_linkedin" 
                           value="<?php echo esc_url(get_option('samira_social_linkedin', '')); ?>" 
                           class="large-text" 
                           placeholder="https://linkedin.com/in/username" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_social_twitter">Twitter/X</label>
                </th>
                <td>
                    <input type="url" id="samira_social_twitter" name="samira_social_twitter" 
                           value="<?php echo esc_url(get_option('samira_social_twitter', '')); ?>" 
                           class="large-text" 
                           placeholder="https://twitter.com/username" />
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="samira_social_facebook">Facebook</label>
                </th>
                <td>
                    <input type="url" id="samira_social_facebook" name="samira_social_facebook" 
                           value="<?php echo esc_url(get_option('samira_social_facebook', '')); ?>" 
                           class="large-text" 
                           placeholder="https://facebook.com/username" />
                </td>
            </tr>
        </table>
    </div>
    <?php
}

/**
 * Render Style Tab
 */
function samira_render_style_tab() {
    ?>
    <div class="samira-tab-content">
        <h2><?php _e('Style Customization', 'samira-theme'); ?></h2>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="samira_accent_color"><?php _e('Accent Color', 'samira-theme'); ?></label>
                </th>
                <td>
                    <input type="color" id="samira_accent_color" name="samira_accent_color"
                           value="<?php echo esc_attr(get_option('samira_accent_color', '#e26f8e')); ?>"
                           class="samira-color-picker" />
                    <p class="description"><?php _e('Main color used for links, buttons and accents', 'samira-theme'); ?></p>
                </td>
            </tr>

            <tr>
                <th scope="row"><?php _e('Default Dark Mode', 'samira-theme'); ?></th>
                <td>
                    <label>
                        <input type="checkbox" name="samira_enable_dark_mode" value="1" 
                               <?php checked(get_option('samira_enable_dark_mode', false)); ?> />
                        <?php _e('Enable dark mode as default theme', 'samira-theme'); ?>
                    </label>
                    <p class="description"><?php _e('Visitors can still change the mode with the toggle', 'samira-theme'); ?></p>
                </td>
            </tr>
        </table>

        <h3><?php _e('Accent Color Preview', 'samira-theme'); ?></h3>
        <div class="samira-color-preview">
            <div class="samira-preview-button" style="background-color: <?php echo esc_attr(get_option('samira_accent_color', '#e26f8e')); ?>;">
                <?php _e('Example Button', 'samira-theme'); ?>
            </div>
            <p><span class="samira-preview-link" style="color: <?php echo esc_attr(get_option('samira_accent_color', '#e26f8e')); ?>;"><?php _e('Example link', 'samira-theme'); ?></span></p>
        </div>
    </div>

    <script>
    jQuery(document).ready(function($) {
        $('#samira_accent_color').on('input', function() {
            var color = $(this).val();
            $('.samira-preview-button').css('background-color', color);
            $('.samira-preview-link').css('color', color);
        });
    });
    </script>
    <?php
}

/**
 * Save settings
 */
function samira_save_settings($data) {
    $defaults = samira_get_default_options();

    foreach ($defaults as $option_name => $default_value) {
        if (isset($data[$option_name])) {
            $value = $data[$option_name];

            // Special handling for gallery (convert comma-separated string to array)
            if ($option_name === 'samira_about_page_gallery' && is_string($value)) {
                $value = array_filter(array_map('intval', explode(',', $value)));
                // Limit to 50 images
                $value = array_slice($value, 0, 50);
            }

            $sanitized_value = samira_sanitize_option($value, $option_name);
            update_option($option_name, $sanitized_value);
        } else {
            // Handle checkboxes that might not be in POST data
            if (strpos($option_name, '_enable_') !== false ||
                strpos($option_name, '_disable_') !== false ||
                in_array($option_name, array('samira_clean_head', 'samira_lazy_loading'))) {
                update_option($option_name, false);
            }
        }
    }

    // Clear any caches
    if (function_exists('wp_cache_flush')) {
        wp_cache_flush();
    }
}

/**
 * Newsletter settings page
 */
function samira_newsletter_page() {
    // Handle form submission
    if ($_POST && wp_verify_nonce($_POST['samira_nonce'], 'samira_newsletter_settings')) {
        update_option('samira_newsletter_provider', sanitize_text_field($_POST['samira_newsletter_provider'] ?? ''));
        update_option('samira_newsletter_api_key', sanitize_text_field($_POST['samira_newsletter_api_key'] ?? ''));
        update_option('samira_newsletter_list_id', sanitize_text_field($_POST['samira_newsletter_list_id'] ?? ''));
        update_option('samira_newsletter_title', sanitize_text_field($_POST['samira_newsletter_title'] ?? ''));
        update_option('samira_newsletter_description', sanitize_textarea_field($_POST['samira_newsletter_description'] ?? ''));

        echo '<div class="notice notice-success"><p>' . __('Newsletter settings saved!', 'samira-theme') . '</p></div>';
    }
    ?>

    <div class="wrap samira-admin">
        <h1><?php _e('Newsletter Configuration', 'samira-theme'); ?></h1>

        <form method="post" action="" class="samira-form">
            <?php wp_nonce_field('samira_newsletter_settings', 'samira_nonce'); ?>

            <div class="samira-content">
                <div class="samira-main">
                    <div class="samira-card">
                        <h2><?php _e('Newsletter Provider', 'samira-theme'); ?></h2>

                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php _e('Provider', 'samira-theme'); ?></th>
                                <td>
                                    <select name="samira_newsletter_provider" id="newsletter-provider">
                                        <option value=""><?php _e('Select Provider', 'samira-theme'); ?></option>
                                        <option value="mailchimp" <?php selected(get_option('samira_newsletter_provider'), 'mailchimp'); ?>>Mailchimp</option>
                                        <option value="brevo" <?php selected(get_option('samira_newsletter_provider'), 'brevo'); ?>>Brevo (SendinBlue)</option>
                                    </select>
                                    <span id="provider-status" class="samira-provider-status"></span>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('API Key', 'samira-theme'); ?></th>
                                <td>
                                    <input type="password" name="samira_newsletter_api_key" 
                                           value="<?php echo esc_attr(get_option('samira_newsletter_api_key', '')); ?>" 
                                           class="large-text" />
                                    <p class="description">
                                        <strong>Mailchimp:</strong> Find your API key in Account > Extras > API keys<br>
                                        <strong>Brevo:</strong> Go to Account > SMTP & API > API Keys
                                    </p>
                                </td>
                            </tr>

                            <tr>
                                <th scope="row"><?php _e('List/Audience ID', 'samira-theme'); ?></th>
                                <td>
                                    <input type="text" name="samira_newsletter_list_id" 
                                           value="<?php echo esc_attr(get_option('samira_newsletter_list_id', '')); ?>" 
                                           class="regular-text" />
                                    <button type="button" id="load-lists" class="button"><?php _e('Load Lists', 'samira-theme'); ?></button>
                                    <div id="available-lists"></div>
                                </td>
                            </tr>
                        </table>

                        <p>
                            <button type="button" id="test-connection" class="button button-secondary">
                                <?php _e('Test Connection', 'samira-theme'); ?>
                            </button>
                            <span id="test-result"></span>
                        </p>
                    </div>

                    <div class="samira-card">
                        <h2><?php _e('Newsletter Text', 'samira-theme'); ?></h2>

                        <table class="form-table">
                            <tr>
                                <th scope="row">
                                    <label for="samira_newsletter_title"><?php _e('Section Title', 'samira-theme'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="samira_newsletter_title" name="samira_newsletter_title" 
                                           value="<?php echo esc_attr(get_option('samira_newsletter_title', __( 'Stay Connected', 'samira-theme' ))); ?>"
                                           class="large-text" />
                                </td>
                            </tr>

                            <tr>
                                <th scope="row">
                                    <label for="samira_newsletter_description"><?php _e('Description', 'samira-theme'); ?></label>
                                </th>
                                <td>
                                    <textarea id="samira_newsletter_description" name="samira_newsletter_description" 
                                              rows="3" class="large-text"><?php echo esc_textarea(get_option('samira_newsletter_description', __( 'Subscribe to receive my thoughts, updates on current and future releases.', 'samira-theme' ))); ?></textarea>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="samira-sidebar">
                    <div class="samira-card">
                        <h3><?php _e('Newsletter Statistics', 'samira-theme'); ?></h3>
                        <?php 
                        $stats = samira_get_newsletter_stats();
                        ?>
                        <div class="samira-stats">
                            <div class="stat-item">
                                <strong><?php echo $stats['total_attempts']; ?></strong>
                                <span><?php _e('Total attempts', 'samira-theme'); ?></span>
                            </div>
                            <div class="stat-item">
                                <strong><?php echo $stats['successful_subscriptions']; ?></strong>
                                <span><?php _e('Successful subscriptions', 'samira-theme'); ?></span>
                            </div>
                            <div class="stat-item">
                                <strong><?php echo $stats['success_rate']; ?>%</strong>
                                <span><?php _e('Success rate', 'samira-theme'); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="samira-card">
                        <h3><?php _e('Setup Guide', 'samira-theme'); ?></h3>
                        <h4>Mailchimp:</h4>
                        <ol>
                            <li>Create a Mailchimp account</li>
                            <li>Create a new Audience</li>
                            <li>Go to Account > Extras > API keys</li>
                            <li>Copy the API key here</li>
                            <li>Enter the Audience ID</li>
                        </ol>

                        <h4>Brevo:</h4>
                        <ol>
                            <li>Create a Brevo account</li>
                            <li>Create a new List</li>
                            <li>Go to Account > SMTP & API > API Keys</li>
                            <li>Create a new API key</li>
                            <li>Enter the list ID</li>
                        </ol>
                    </div>
                </div>
            </div>

            <div class="samira-submit">
                <?php submit_button(__('Save Newsletter Configuration', 'samira-theme'), 'primary'); ?>
            </div>
        </form>
    </div>

    <script>
    jQuery(document).ready(function($) {
        function updateProviderStatus(success, message) {
            var color = success ? 'green' : 'red';
            $('#provider-status').html('<span style="color: ' + color + ';">' + (success ? '<?php echo esc_js(__('Connected', 'samira-theme')); ?>' : '<?php echo esc_js(__('Disconnected', 'samira-theme')); ?>') + (message ? ': ' + message : '') + '</span>');
        }

        function testConnection(provider, apiKey, listId) {
            $('#test-result').html('<span><?php echo esc_js(__('Testing...', 'samira-theme')); ?></span>');

            $.post(ajaxurl, {
                action: 'samira_test_newsletter',
                nonce: '<?php echo wp_create_nonce('samira_nonce'); ?>',
                provider: provider,
                api_key: apiKey,
                list_id: listId
            }, function(response) {
                updateProviderStatus(response.success, response.data.message);
                if (response.success) {
                    $('#test-result').html('<span style="color: green;">✓ ' + response.data.message + '</span>');
                } else {
                    $('#test-result').html('<span style="color: red;">✗ ' + response.data.message + '</span>');
                }
            });
        }

        $('#test-connection').click(function() {
            var provider = $('#newsletter-provider').val();
            var apiKey = $('input[name="samira_newsletter_api_key"]').val();
            var listId = $('input[name="samira_newsletter_list_id"]').val();

            if (!provider || !apiKey || !listId) {
                $('#test-result').html('<span style="color: red;"><?php echo esc_js(__('Fill all fields before testing', 'samira-theme')); ?></span>');
                return;
            }

            testConnection(provider, apiKey, listId);
        });

        var initialProvider = $('#newsletter-provider').val();
        var initialApiKey = $('input[name="samira_newsletter_api_key"]').val();
        var initialListId = $('input[name="samira_newsletter_list_id"]').val();
        if (initialProvider && initialApiKey && initialListId) {
            testConnection(initialProvider, initialApiKey, initialListId);
        } else {
            updateProviderStatus(false, '<?php echo esc_js(__('Not configured', 'samira-theme')); ?>');
        }

        $('#load-lists').click(function() {
            var provider = $('#newsletter-provider').val();
            var apiKey = $('input[name="samira_newsletter_api_key"]').val();

            if (!provider || !apiKey) {
                alert('<?php echo esc_js(__('Select a provider and enter API key', 'samira-theme')); ?>');
                return;
            }

            $('#available-lists').html('<?php echo esc_js(__('Loading lists...', 'samira-theme')); ?>');

            $.post(ajaxurl, {
                action: 'samira_get_newsletter_lists',
                nonce: '<?php echo wp_create_nonce('samira_nonce'); ?>',
                provider: provider,
                api_key: apiKey
            }, function(response) {
                if (response.success && response.data.lists) {
                    var html = '<h4><?php echo esc_js(__('Available lists:', 'samira-theme')); ?></h4><ul>';
                    $.each(response.data.lists, function(id, list) {
                        html += '<li><strong>' + list.name + '</strong> (ID: ' + id + ', <?php echo esc_js(__('Subscribers', 'samira-theme')); ?>: ' + list.subscribers + ')';
                        html += '<button type="button" class="button button-small select-list" data-id="' + id + '"><?php echo esc_js(__('Select', 'samira-theme')); ?></button></li>';
                    });
                    html += '</ul>';
                    $('#available-lists').html(html);
                } else {
                    var errorMessage = response.data && response.data.message
                        ? response.data.message
                        : '<?php echo esc_js( __( 'No lists found or connection error', 'samira-theme' ) ); ?>';
                    $('#available-lists').html(errorMessage);
                }
            });
        });

        $(document).on('click', '.select-list', function() {
            var listId = $(this).data('id');
            $('input[name="samira_newsletter_list_id"]').val(listId);
            $(this).text('<?php echo esc_js(__('Selected', 'samira-theme')); ?>').prop('disabled', true);
        });
    });
    </script>

    <?php
}

/**
 * Statistics page
 */
function samira_stats_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (
        isset($_POST['samira_clear_logs']) &&
        check_admin_referer('samira_clear_logs', 'samira_clear_logs_nonce') &&
        current_user_can('manage_options')
    ) {
        samira_clear_newsletter_logs();
        echo '<div class="notice notice-success"><p>' . esc_html__('Newsletter logs cleared.', 'samira-theme') . '</p></div>';
    }

    $theme_stats      = samira_get_theme_stats();
    $newsletter_stats = samira_get_newsletter_stats();
    ?>

    <div class="wrap samira-admin">
        <h1><?php esc_html_e('Statistics', 'samira-theme'); ?></h1>

        <div class="samira-content">
            <div class="samira-main">
                <div class="samira-card">
                    <h2><?php esc_html_e('Site Overview', 'samira-theme'); ?></h2>
                    <div class="samira-stats">
                        <div class="stat-item">
                            <strong><?php echo esc_html($theme_stats['posts']); ?></strong>
                            <span><?php esc_html_e('Posts', 'samira-theme'); ?></span>
                        </div>
                        <div class="stat-item">
                            <strong><?php echo esc_html($theme_stats['portfolio']); ?></strong>
                            <span><?php esc_html_e('Portfolio Items', 'samira-theme'); ?></span>
                        </div>
                        <div class="stat-item">
                            <strong><?php echo esc_html($theme_stats['books']); ?></strong>
                            <span><?php esc_html_e('Books', 'samira-theme'); ?></span>
                        </div>
                        <div class="stat-item">
                            <strong><?php echo esc_html($theme_stats['social_links']); ?></strong>
                            <span><?php esc_html_e('Social Links', 'samira-theme'); ?></span>
                        </div>
                        <div class="stat-item">
                            <strong><?php echo esc_html($theme_stats['customization_percentage']); ?>%</strong>
                            <span><?php esc_html_e('Customization', 'samira-theme'); ?></span>
                        </div>
                    </div>
                </div>

                <div class="samira-card">
                    <h2><?php esc_html_e('Newsletter Activity', 'samira-theme'); ?></h2>
                    <div class="samira-stats">
                        <div class="stat-item">
                            <strong><?php echo esc_html($newsletter_stats['total_attempts']); ?></strong>
                            <span><?php esc_html_e('Total attempts', 'samira-theme'); ?></span>
                        </div>
                        <div class="stat-item">
                            <strong><?php echo esc_html($newsletter_stats['successful_subscriptions']); ?></strong>
                            <span><?php esc_html_e('Successful subscriptions', 'samira-theme'); ?></span>
                        </div>
                        <div class="stat-item">
                            <strong><?php echo esc_html($newsletter_stats['success_rate']); ?>%</strong>
                            <span><?php esc_html_e('Success rate', 'samira-theme'); ?></span>
                        </div>
                    </div>

                    <?php if (!empty($newsletter_stats['recent_activity'])) : ?>
                        <h3><?php esc_html_e('Recent Activity', 'samira-theme'); ?></h3>
                        <table class="widefat fixed">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Email', 'samira-theme'); ?></th>
                                    <th><?php esc_html_e('Status', 'samira-theme'); ?></th>
                                    <th><?php esc_html_e('Date', 'samira-theme'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($newsletter_stats['recent_activity'] as $log) : ?>
                                    <tr>
                                        <td><?php echo esc_html($log['email']); ?></td>
                                        <td>
                                            <?php
                                            echo $log['success']
                                                ? '<span style="color:green;">' . esc_html__('Success', 'samira-theme') . '</span>'
                                                : '<span style="color:red;">' . esc_html__('Failed', 'samira-theme') . '</span>';
                                            ?>
                                        </td>
                                        <td><?php echo esc_html($log['date']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php else : ?>
                        <p><?php esc_html_e('No recent activity.', 'samira-theme'); ?></p>
                    <?php endif; ?>

                    <form method="post" style="margin-top:20px;">
                        <?php wp_nonce_field('samira_clear_logs', 'samira_clear_logs_nonce'); ?>
                        <input type="submit" name="samira_clear_logs" class="button" value="<?php esc_attr_e('Clear Logs', 'samira-theme'); ?>" />
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
}

/**
 * Import/Export page
 */
function samira_import_export_page() {
    if (!current_user_can('manage_options')) {
        return;
    }

    if (isset($_POST['samira_export']) && check_admin_referer('samira_import_export', 'samira_ie_nonce')) {
        $options = samira_export_options();
        $json    = wp_json_encode($options);

        header('Content-Description: File Transfer');
        header('Content-Type: application/json; charset=utf-8');
        header('Content-Disposition: attachment; filename=samira-theme-settings-' . date('Y-m-d') . '.json');
        echo $json;
        exit;
    }

    if (isset($_POST['samira_import']) && check_admin_referer('samira_import_export', 'samira_ie_nonce')) {
        if (!empty($_FILES['samira_import_file']['tmp_name'])) {
            $file_contents = file_get_contents($_FILES['samira_import_file']['tmp_name']);
            $options       = json_decode($file_contents, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $result = samira_import_options($options);
                if (!is_wp_error($result)) {
                    wp_cache_flush();
                    echo '<div class="notice notice-success"><p>' . __('Settings imported successfully.', 'samira-theme') . '</p></div>';
                } else {
                    echo '<div class="notice notice-error"><p>' . esc_html($result->get_error_message()) . '</p></div>';
                }
            } else {
                echo '<div class="notice notice-error"><p>' . __('Invalid JSON file.', 'samira-theme') . '</p></div>';
            }
        } else {
            echo '<div class="notice notice-error"><p>' . __('Please upload a JSON file.', 'samira-theme') . '</p></div>';
        }
    }

    ?>
    <div class="wrap samira-admin">
        <h1><?php _e('Import/Export Settings', 'samira-theme'); ?></h1>

        <div class="samira-content">
            <div class="samira-main">
                <div class="samira-card">
                    <h2><?php _e('Download Settings', 'samira-theme'); ?></h2>
                    <form method="post" action="">
                        <?php wp_nonce_field('samira_import_export', 'samira_ie_nonce'); ?>
                        <p><?php _e('Download your current theme settings as a JSON file.', 'samira-theme'); ?></p>
                        <p><input type="submit" name="samira_export" class="button button-primary" value="<?php esc_attr_e('Download Settings', 'samira-theme'); ?>" /></p>
                    </form>
                </div>

                <div class="samira-card">
                    <h2><?php _e('Upload Settings', 'samira-theme'); ?></h2>
                    <form method="post" enctype="multipart/form-data">
                        <?php wp_nonce_field('samira_import_export', 'samira_ie_nonce'); ?>
                        <p><?php _e('Upload a JSON file to import theme settings.', 'samira-theme'); ?></p>
                        <input type="file" name="samira_import_file" accept="application/json" />
                        <p><input type="submit" name="samira_import" class="button button-primary" value="<?php esc_attr_e('Upload Settings', 'samira-theme'); ?>" /></p>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
}

/**
 * AJAX handler for reset options
 */
function samira_reset_options_ajax() {
    if (!wp_verify_nonce($_POST['nonce'], 'samira_reset') || !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __( 'Access denied', 'samira-theme' )));
    }

    samira_reset_options();
    wp_send_json_success(array('message' => __( 'Settings reset successfully!', 'samira-theme' )));
}
add_action('wp_ajax_samira_reset_options', 'samira_reset_options_ajax');
