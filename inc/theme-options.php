<?php
/**
 * Theme Options and Settings
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

// Impedisce accesso diretto
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
        'samira_hero_title' => 'Samira Mahmoodi',
        'samira_hero_subtitle' => 'Scrittura, Arte, Rinascita',
        'samira_hero_image' => '',
        
        // About Section
        'samira_about_title' => 'Chi sono',
        'samira_about_content' => 'Samira Mahmoodi began writing shortly after graduating from college. In 2016, she received a Bachelor of Science in Nursing. Unable to suppress her despair at that time, journaling her feelings led her to re-discover her love for art and literature. She buried this integral part of herself after convincing her 14-year-old self she would be unsuccessful doing what she loved. In 2019, she released her first book, "To Water Her Garden: A journey of self-discovery". It was in this space that she unveiled the reasons behind her sadness, and where she also discovered her greatest power: herself.',
        
        // Writing Section
        'samira_book_title' => 'To Water Her Garden: A journey of self-discovery',
        'samira_book_year' => '2019',
        'samira_book_description' => 'In questo spazio ho svelato le ragioni dietro la mia tristezza, e dove ho anche scoperto il mio più grande potere: me stessa.',
        'samira_book_cover' => '',
        
        // Art Section
        'samira_art_title' => 'La Mia Arte',
        'samira_art_description' => 'L\'arte mi ha parlato e mi ha capita meglio di chiunque altro. È sempre stata il mio rifugio sicuro per l\'espressione di sé. Creare la mia arte mi dà potere oltre ogni spiegazione.',
        
        // Newsletter Section
        'samira_newsletter_title' => 'Resta in contatto',
        'samira_newsletter_description' => 'Iscriviti per ricevere i miei pensieri, aggiornamenti su uscite attuali e future.',
        'samira_newsletter_provider' => 'mailchimp',
        'samira_newsletter_api_key' => '',
        'samira_newsletter_list_id' => '',
        
        // Social Media
        'samira_social_instagram' => '',
        'samira_social_goodreads' => '',
        'samira_social_linkedin' => '',
        'samira_social_twitter' => '',
        'samira_social_facebook' => '',
        
        // Branding
        'samira_logo_text' => 'SM',
        'samira_accent_color' => '#D4A574',
        'samira_enable_dark_mode' => false,
        
        // Footer
        'samira_footer_text' => 'Scrittrice e artista. L\'arte è il mio rifugio sicuro per l\'espressione di sé.',
        'samira_copyright_name' => 'Samira Mahmoodi',
        'samira_terms_page' => '',
        
        // Performance
        'samira_disable_emojis' => true,
        'samira_clean_head' => true,
        'samira_lazy_loading' => true,
        
        // Contact
        'samira_contact_email' => '',
        'samira_contact_phone' => '',
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
            return esc_url_raw($value);
            
        case 'samira_social_instagram':
        case 'samira_social_goodreads':
        case 'samira_social_linkedin':
        case 'samira_social_twitter':
        case 'samira_social_facebook':
        case 'samira_terms_page':
            return esc_url_raw($value);
            
        case 'samira_contact_email':
            return sanitize_email($value);
            
        case 'samira_accent_color':
            return sanitize_hex_color($value);
            
        case 'samira_enable_dark_mode':
        case 'samira_disable_emojis':
        case 'samira_clean_head':
        case 'samira_lazy_loading':
            return (bool) $value;
            
        case 'samira_about_content':
        case 'samira_art_description':
        case 'samira_book_description':
            return wp_kses_post($value);
            
        case 'samira_newsletter_api_key':
            return sanitize_text_field($value);
            
        default:
            return sanitize_text_field($value);
    }
}

/**
 * Validate theme options
 */
function samira_validate_option($value, $option_name) {
    $errors = array();
    
    switch ($option_name) {
        case 'samira_contact_email':
            if ($value && !is_email($value)) {
                $errors[] = __('Indirizzo email non valido', 'samira-theme');
            }
            break;
            
        case 'samira_accent_color':
            if ($value && !preg_match('/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/', $value)) {
                $errors[] = __('Colore non valido. Usa il formato esadecimale (es. #D4A574)', 'samira-theme');
            }
            break;
            
        case 'samira_newsletter_api_key':
            if ($value && strlen($value) < 10) {
                $errors[] = __('API Key troppo corta', 'samira-theme');
            }
            break;
            
        case 'samira_social_instagram':
        case 'samira_social_goodreads':
        case 'samira_social_linkedin':
        case 'samira_social_twitter':
        case 'samira_social_facebook':
            if ($value && !filter_var($value, FILTER_VALIDATE_URL)) {
                $errors[] = sprintf(__('URL %s non valido', 'samira-theme'), str_replace('samira_social_', '', $option_name));
            }
            break;
    }
    
    return $errors;
}

/**
 * Reset theme options to defaults
 */
function samira_reset_options() {
    $defaults = samira_get_default_options();
    
    foreach ($defaults as $option_name => $default_value) {
        update_option($option_name, $default_value);
    }
    
    do_action('samira_options_reset');
}

/**
 * Export theme options
 */
function samira_export_options() {
    $options = array();
    $defaults = samira_get_default_options();
    
    foreach ($defaults as $option_name => $default_value) {
        $options[$option_name] = get_option($option_name, $default_value);
    }
    
    $export_data = array(
        'version' => SAMIRA_THEME_VERSION,
        'date' => current_time('mysql'),
        'options' => $options
    );
    
    return json_encode($export_data, JSON_PRETTY_PRINT);
}

/**
 * Import theme options
 */
function samira_import_options($json_data) {
    $data = json_decode($json_data, true);
    
    if (!$data || !isset($data['options'])) {
        return new WP_Error('invalid_data', __('Dati di importazione non validi', 'samira-theme'));
    }
    
    $imported_options = $data['options'];
    $defaults = samira_get_default_options();
    $imported_count = 0;
    
    foreach ($imported_options as $option_name => $option_value) {
        if (array_key_exists($option_name, $defaults)) {
            $sanitized_value = samira_sanitize_option($option_value, $option_name);
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
    $posts_count = wp_count_posts();
    $stats['posts'] = $posts_count->publish;
    
    // Portfolio count
    $portfolio_count = wp_count_posts('portfolio');
    $stats['portfolio'] = isset($portfolio_count->publish) ? $portfolio_count->publish : 0;
    
    // Books count
    $books_count = wp_count_posts('books');
    $stats['books'] = isset($books_count->publish) ? $books_count->publish : 0;
    
    // Newsletter subscribers (if available)
    $stats['newsletter_provider'] = get_option('samira_newsletter_provider', 'none');
    
    // Social media links
    $social_platforms = array('instagram', 'goodreads', 'linkedin', 'twitter', 'facebook');
    $social_count = 0;
    
    foreach ($social_platforms as $platform) {
        if (get_option('samira_social_' . $platform)) {
            $social_count++;
        }
    }
    
    $stats['social_links'] = $social_count;
    
    // Theme customization level
    $defaults = samira_get_default_options();
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
    $api_key = get_option('samira_newsletter_api_key');
    $list_id = get_option('samira_newsletter_list_id');
    
    return !empty($provider) && !empty($api_key) && !empty($list_id);
}

/**
 * Helper function to get social media links
 */
function samira_get_social_links() {
    $social_platforms = array(
        'instagram' => __('Instagram', 'samira-theme'),
        'goodreads' => __('Goodreads', 'samira-theme'),
        'linkedin' => __('LinkedIn', 'samira-theme'),
        'twitter' => __('Twitter', 'samira-theme'),
        'facebook' => __('Facebook', 'samira-theme'),
    );
    
    $social_links = array();
    
    foreach ($social_platforms as $platform => $label) {
        $url = get_option('samira_social_' . $platform);
        if ($url) {
            $social_links[$platform] = array(
                'label' => $label,
                'url' => $url
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
    $rgb = sscanf($accent_color, "#%02x%02x%02x");
    $hover_color = sprintf("#%02x%02x%02x", 
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
