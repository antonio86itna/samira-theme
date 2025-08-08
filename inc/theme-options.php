<?php
/**
 * Theme Options and Settings
 *
 * @package Samira_Theme
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get theme option with default fallback
 */
function samira_get_option($option_name, $default = '') {
    $option_value = get_option($option_name, $default);
    return apply_filters('samira_get_option', $option_value, $option_name, $default);
}

/**
 * Update theme option
 */
function samira_update_option($option_name, $option_value) {
    $updated = update_option($option_name, $option_value);
    do_action('samira_option_updated', $option_name, $option_value, $updated);
    return $updated;
}

/**
 * Default theme options
 */
function samira_get_default_options() {
    return array(
        // Hero Section
        'samira_hero_title'       => __( 'Samira Mahmoodi', 'samira-theme' ),
        'samira_hero_subtitle'    => __( 'Writing, Art, Rebirth', 'samira-theme' ),
        'samira_hero_image'       => '',

        // About Section
        'samira_about_title'      => __( 'About Me', 'samira-theme' ),
        'samira_about_content'    => __(
            'Samira Mahmoodi began writing shortly after graduating from college. In 2016, she received a Bachelor of Science in Nursing. Unable to suppress her despair at that time, journaling her feelings led her to rediscover her love for art and literature. She buried this integral part of herself after convincing her 14-year-old self she would be unsuccessful doing what she loved. In 2019, she released her first book, "To Water Her Garden: A journey of self-discovery". It was in this space that she unveiled the reasons behind her sadness, and where she also discovered her greatest power: herself.',
            'samira-theme'
        ),

        // Writing Section
        'samira_book_title'       => __( 'To Water Her Garden: A journey of self-discovery', 'samira-theme' ),
        'samira_book_year'        => __( '2019', 'samira-theme' ),
        'samira_book_description' => __(
            'Within this space I revealed the reasons behind my sadness and where I also discovered my greatest power: myself.',
            'samira-theme'
        ),
        'samira_book_cover'       => '',
        'samira_book_link_amazon'  => '',
        'samira_book_link_bam'     => '',
        'samira_book_link_bookshop' => '',
        'samira_book_link_bn'      => '',

        // Art Section
        'samira_art_title'        => __( 'My Art', 'samira-theme' ),
        'samira_art_description'  => __(
            'Art has spoken to me and understood me better than anyone else. It has always been my safe haven for self-expression. Creating my art empowers me beyond explanation.',
            'samira-theme'
        ),

        // Newsletter Section
        'samira_newsletter_title'       => __( 'Stay Connected', 'samira-theme' ),
        'samira_newsletter_description' => __( 'Subscribe to receive my thoughts, updates on current and future releases.', 'samira-theme' ),
        'samira_newsletter_provider'    => 'mailchimp',
        'samira_newsletter_api_key'     => '',
        'samira_newsletter_list_id'     => '',

        // Social Media
        'samira_social_instagram' => '',
        'samira_social_goodreads' => '',
        'samira_social_linkedin'  => '',
        'samira_social_twitter'   => '',
        'samira_social_facebook'  => '',

        // Branding
        'samira_logo_text'        => __( 'SM', 'samira-theme' ),
        'samira_accent_color'     => '#D4A574',
        'samira_enable_dark_mode' => false,

        // Footer
        'samira_footer_text'    => __( 'Writer and artist. Art is my safe haven for self-expression.', 'samira-theme' ),
        'samira_copyright_name' => __( 'Samira Mahmoodi', 'samira-theme' ),
        'samira_terms_page'     => '',

        // Performance
        'samira_disable_emojis' => true,
        'samira_clean_head'     => true,
        'samira_lazy_loading'   => true,

        // Contact
        'samira_contact_email'   => '',
        'samira_contact_phone'   => '',
        'samira_contact_address' => '',
    );
}

/**
 * Initialize default options on theme activation
 */
function samira_set_default_options() {
    $defaults = samira_get_default_options();

    foreach ($defaults as $option_name => $default_value) {
        if (get_option($option_name) === false) {
            add_option($option_name, $default_value);
        }
    }
}

/**
 * Sanitize theme options
 */
function samira_sanitize_option($value, $option_name) {
    switch ($option_name) {
        case 'samira_hero_image':
        case 'samira_book_cover':
        case 'samira_book_link_amazon':
        case 'samira_book_link_bam':
        case 'samira_book_link_bookshop':
        case 'samira_book_link_bn':
            return esc_url_raw($value);

        case 'samira_social_instagram':
        case 'samira_social_goodreads':
        case 'samira_social_linkedin':
        case 'samira_social_twitter':
        case 'samira_social_facebook':
            return esc_url_raw($value);

        case 'samira_accent_color':
            return sanitize_hex_color($value);

        case 'samira_enable_dark_mode':
            return (bool) $value;

        default:
            return sanitize_text_field($value);
    }
}

/**
 * Validate theme option value
 */
function samira_validate_option($value, $option_name) {
    $errors = array();

    if (empty($value)) {
        return $errors;
    }

    switch ($option_name) {
        case 'samira_accent_color':
            if (!preg_match('/^#[a-f0-9]{6}$/i', $value)) {
                $errors[] = __( 'Invalid color format', 'samira-theme' );
            }
            break;

        case 'samira_newsletter_provider':
            $valid_providers = array('mailchimp', 'brevo');
            if (!in_array($value, $valid_providers, true)) {
                $errors[] = __( 'Invalid newsletter provider', 'samira-theme' );
            }
            break;

        case 'samira_contact_email':
            if (!is_email($value)) {
                $errors[] = __( 'Invalid email address', 'samira-theme' );
            }
            break;
    }

    return $errors;
}

/**
 * Export theme options
 */
function samira_export_options() {
    $defaults = samira_get_default_options();
    $options  = array();

    foreach ($defaults as $option_name => $default_value) {
        $options[$option_name] = get_option($option_name, $default_value);
    }

    return $options;
}

/**
 * Import theme options
 */
function samira_import_options($options) {
    if (!is_array($options)) {
        return new WP_Error('invalid_options', __( 'Invalid options format', 'samira-theme' ));
    }

    $defaults = samira_get_default_options();
    $imported_count = 0;

    foreach ($options as $option_name => $option_value) {
        if (array_key_exists($option_name, $defaults)) {
            $sanitized_value   = samira_sanitize_option($option_value, $option_name);
            $validation_errors = samira_validate_option($sanitized_value, $option_name);

            if (empty($validation_errors)) {
                update_option($option_name, $sanitized_value);
                $imported_count++;
            }
        }
    }

    do_action('samira_options_imported', $imported_count);

    return $imported_count;
}

/**
 * Get theme statistics
 */
function samira_get_theme_stats() {
    $stats = array();

    // Posts count
    $posts_count       = wp_count_posts();
    $stats['posts']    = $posts_count->publish;

    // Portfolio count
    $portfolio_count   = wp_count_posts('portfolio');
    $stats['portfolio'] = isset($portfolio_count->publish) ? $portfolio_count->publish : 0;

    // Books count
    $books_count     = wp_count_posts('books');
    $stats['books']  = isset($books_count->publish) ? $books_count->publish : 0;

    // Newsletter subscribers (if available)
    $stats['newsletter_provider'] = get_option('samira_newsletter_provider', 'none');

    // Social media links
    $social_platforms = array('instagram', 'goodreads', 'linkedin', 'twitter', 'facebook');
    $social_count     = 0;

    foreach ($social_platforms as $platform) {
        if (get_option('samira_social_' . $platform)) {
            $social_count++;
        }
    }

    $stats['social_links'] = $social_count;

    // Theme customization level
    $defaults           = samira_get_default_options();
    $customized_options = 0;

    foreach ($defaults as $option_name => $default_value) {
        $current_value = get_option($option_name, $default_value);
        if ($current_value != $default_value) {
            $customized_options++;
        }
    }

    $stats['customization_percentage'] = round(($customized_options / count($defaults)) * 100);

    return $stats;
}

/**
 * Helper function to check if newsletter is configured
 */
function samira_is_newsletter_configured() {
    $provider = get_option('samira_newsletter_provider');
    $api_key  = get_option('samira_newsletter_api_key');
    $list_id  = get_option('samira_newsletter_list_id');

    return !empty($provider) && !empty($api_key) && !empty($list_id);
}

/**
 * Helper function to get social media links
 */
function samira_get_social_links() {
    $social_platforms = array(
        'instagram' => __( 'Instagram', 'samira-theme' ),
        'goodreads' => __( 'Goodreads', 'samira-theme' ),
        'linkedin'  => __( 'LinkedIn', 'samira-theme' ),
        'twitter'   => __( 'Twitter', 'samira-theme' ),
        'facebook'  => __( 'Facebook', 'samira-theme' ),
    );

    $social_links = array();

    foreach ($social_platforms as $platform => $label) {
        $url = get_option('samira_social_' . $platform);
        if ($url) {
            $social_links[$platform] = array(
                'label' => $label,
                'url'   => $url,
            );
        }
    }

    return $social_links;
}

/**
 * Helper function to generate accent color CSS
 */
function samira_get_accent_color_css() {
    $accent_color = get_option('samira_accent_color', '#D4A574');

    // Generate hover color (slightly darker)
    $rgb        = sscanf($accent_color, '#%02x%02x%02x');
    $hover_color = sprintf(
        '#%02x%02x%02x',
        max(0, $rgb[0] - 20),
        max(0, $rgb[1] - 20),
        max(0, $rgb[2] - 20)
    );

    return "
        :root {
            --color-accent: {$accent_color};
            --color-accent-hover: {$hover_color};
        }
    ";
}

