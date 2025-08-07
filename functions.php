<?php
/**
 * Samira Theme Functions
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

// Impedisce accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

// Costanti del tema
define('SAMIRA_THEME_VERSION', '1.0.0');
define('SAMIRA_THEME_DIR', get_template_directory());
define('SAMIRA_THEME_URI', get_template_directory_uri());

/**
 * Setup del tema
 */
function samira_theme_setup() {
    // Supporto per le caratteristiche del tema
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    
    // Menu di navigazione
    register_nav_menus(array(
        'primary' => __('Menu Principale', 'samira-theme'),
        'footer'  => __('Menu Footer', 'samira-theme'),
    ));
    
    // Formati post
    add_theme_support('post-formats', array(
        'aside',
        'image',
        'video',
        'quote',
        'link',
        'gallery',
    ));
}
add_action('after_setup_theme', 'samira_theme_setup');

/**
 * Enqueue degli script e stili
 */
function samira_theme_scripts() {
    // CSS principale
    wp_enqueue_style('samira-style', get_stylesheet_uri(), array(), SAMIRA_THEME_VERSION);
    
    // Google Fonts
    wp_enqueue_style('samira-fonts', 
        'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&family=Playfair+Display:wght@400;500;600;700&display=swap', 
        array(), 
        null
    );
    
    // JavaScript principale
    wp_enqueue_script('samira-main', 
        SAMIRA_THEME_URI . '/js/main.js', 
        array('jquery'), 
        SAMIRA_THEME_VERSION, 
        true
    );
    
    // Dark mode script
    wp_enqueue_script('samira-dark-mode',
        SAMIRA_THEME_URI . '/js/dark-mode.js',
        array('jquery'),
        SAMIRA_THEME_VERSION,
        true
    );

    wp_localize_script(
        'samira-dark-mode',
        'samira_dark_mode',
        array(
            'default_on' => get_option('samira_enable_dark_mode'),
        )
    );

    // Localizzazione per AJAX - SINTASSI CORRETTA
    wp_localize_script('samira-main', 'samira_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce'    => wp_create_nonce('samira_nonce'),
        'strings'  => array(
            'loading'       => esc_html__('Caricamento...', 'samira-theme'),
            'success'       => esc_html__('Operazione completata!', 'samira-theme'),
            'error'         => esc_html__('Errore durante la operazione', 'samira-theme'),
            'required'      => esc_html__('Campo obbligatorio', 'samira-theme'),
            'email_invalid' => esc_html__('Email non valida', 'samira-theme'),
        )
    ));
    
    // Comments reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'samira_theme_scripts');

/**
 * Enqueue admin scripts
 */
function samira_admin_scripts($hook) {
    // Solo nelle pagine del tema
    if (strpos($hook, 'samira-theme') !== false) {
        wp_enqueue_style('samira-admin-style', 
            SAMIRA_THEME_URI . '/admin/admin-style.css', 
            array(), 
            SAMIRA_THEME_VERSION
        );
        wp_enqueue_script('samira-admin-script', 
            SAMIRA_THEME_URI . '/admin/admin-script.js', 
            array('jquery', 'wp-color-picker'), 
            SAMIRA_THEME_VERSION, 
            true
        );
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_media(); // Per media uploader
        
        // Localizzazione admin
        wp_localize_script('samira-admin-script', 'samira_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce'    => wp_create_nonce('samira_admin_nonce'),
        ));
    }
}
add_action('admin_enqueue_scripts', 'samira_admin_scripts');

/**
 * Widget areas
 */
function samira_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'samira-theme'),
        'id'            => 'footer-widget-area',
        'description'   => __('Widget area nel footer', 'samira-theme'),
        'before_widget' => '<div class="footer-widget %2$s" id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
    
    register_sidebar(array(
        'name'          => __('Sidebar Blog', 'samira-theme'),
        'id'            => 'blog-sidebar',
        'description'   => __('Sidebar per gli articoli del blog', 'samira-theme'),
        'before_widget' => '<div class="widget %2$s" id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="widget-title">',
        'after_title'   => '</h3>',
    ));
}
add_action('widgets_init', 'samira_widgets_init');

/**
 * Custom Post Types
 */
function samira_custom_post_types() {
    // Portfolio/Arte
    register_post_type('portfolio', array(
        'labels' => array(
            'name'               => __('Portfolio', 'samira-theme'),
            'singular_name'      => __('Opera', 'samira-theme'),
            'menu_name'          => __('Portfolio', 'samira-theme'),
            'add_new'            => __('Aggiungi Opera', 'samira-theme'),
            'add_new_item'       => __('Aggiungi Nuova Opera', 'samira-theme'),
            'edit_item'          => __('Modifica Opera', 'samira-theme'),
            'new_item'           => __('Nuova Opera', 'samira-theme'),
            'view_item'          => __('Visualizza Opera', 'samira-theme'),
            'search_items'       => __('Cerca Opere', 'samira-theme'),
            'not_found'          => __('Nessuna opera trovata', 'samira-theme'),
            'not_found_in_trash' => __('Nessuna opera nel cestino', 'samira-theme'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 25,
        'menu_icon' => 'dashicons-art',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'portfolio'),
        'show_in_rest' => true,
    ));
    
    // Libri
    register_post_type('books', array(
        'labels' => array(
            'name'               => __('Libri', 'samira-theme'),
            'singular_name'      => __('Libro', 'samira-theme'),
            'menu_name'          => __('I Miei Libri', 'samira-theme'),
            'add_new'            => __('Aggiungi Libro', 'samira-theme'),
            'add_new_item'       => __('Aggiungi Nuovo Libro', 'samira-theme'),
            'edit_item'          => __('Modifica Libro', 'samira-theme'),
        ),
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 26,
        'menu_icon' => 'dashicons-book',
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
        'has_archive' => true,
        'rewrite' => array('slug' => 'libri'),
        'show_in_rest' => true,
    ));
}
add_action('init', 'samira_custom_post_types');

/**
 * Include dei file necessari con controlli
 */
function samira_include_files() {
    $includes = array(
        '/inc/theme-options.php',
        '/inc/newsletter-integration.php', 
        '/inc/customizer.php',
        '/admin/theme-admin.php',
    );
    
    foreach ($includes as $file) {
        $filepath = SAMIRA_THEME_DIR . $file;
        if (file_exists($filepath)) {
            require_once $filepath;
        } else {
            error_log("Samira Theme: File mancante - " . $filepath);
        }
    }
}
add_action('after_setup_theme', 'samira_include_files', 20);

/**
 * Gestione degli errori JavaScript
 */
function samira_javascript_detection() {
    echo "<script>(function(html){html.className = html.className.replace(/\\bno-js\\b/,'js')})(document.documentElement);</script>\n";
}
add_action('wp_head', 'samira_javascript_detection', 0);

/**
 * Aggiungi classe body per tema
 */
function samira_body_classes($classes) {
    // Aggiungi classe per il tema
    $classes[] = 'samira-theme';
    
    // Aggiungi classe per homepage personalizzata
    if (is_front_page()) {
        $classes[] = 'samira-homepage';
    }
    
    // Aggiungi classe per dark mode (se abilitato di default)
    if (get_option('samira_enable_dark_mode', false)) {
        $classes[] = 'dark-mode';
    }
    
    return $classes;
}
add_filter('body_class', 'samira_body_classes');

/**
 * Personalizza excerpt length
 */
function samira_excerpt_length($length) {
    return 25;
}
add_filter('excerpt_length', 'samira_excerpt_length');

/**
 * Personalizza excerpt more
 */
function samira_excerpt_more($more) {
    return '...';
}
add_filter('excerpt_more', 'samira_excerpt_more');

/**
 * Aggiungi meta tags personalizzati
 */
function samira_head_meta() {
    echo '<meta name="theme-color" content="#D4A574">' . "\n";
    echo '<meta name="msapplication-TileColor" content="#D4A574">' . "\n";
    echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">' . "\n";
}
add_action('wp_head', 'samira_head_meta');

/**
 * Disabilita emoji se non necessari
 */
function samira_disable_emojis() {
    if (get_option('samira_disable_emojis', false)) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('admin_print_scripts', 'print_emoji_detection_script');
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('admin_print_styles', 'print_emoji_styles');
        remove_filter('the_content_feed', 'wp_staticize_emoji');
        remove_filter('comment_text_rss', 'wp_staticize_emoji');
        remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
        add_filter('tiny_mce_plugins', 'samira_disable_emojis_tinymce');
        add_filter('wp_resource_hints', 'samira_disable_emojis_dns_prefetch', 10, 2);
    }
}
add_action('init', 'samira_disable_emojis');

function samira_disable_emojis_tinymce($plugins) {
    if (is_array($plugins)) {
        return array_diff($plugins, array('wpemoji'));
    } else {
        return array();
    }
}

function samira_disable_emojis_dns_prefetch($urls, $relation_type) {
    if ('dns-prefetch' == $relation_type) {
        $emoji_svg_url = apply_filters('emoji_svg_url', 'https://s.w.org/images/core/emoji/2/svg/');
        $urls = array_diff($urls, array($emoji_svg_url));
    }
    return $urls;
}

/**
 * Aggiungi supporto per SVG upload (solo per admin)
 */
function samira_mime_types($mimes) {
    if (current_user_can('manage_options')) {
        $mimes['svg'] = 'image/svg+xml';
    }
    return $mimes;
}
add_filter('upload_mimes', 'samira_mime_types');

/**
 * Sicurezza SVG upload
 */
function samira_check_svg($file) {
    if ($file['type'] === 'image/svg+xml') {
        if (!current_user_can('manage_options')) {
            $file['error'] = __('Non hai i permessi per caricare file SVG.', 'samira-theme');
        }
    }
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'samira_check_svg');

/**
 * Pulizia wp_head
 */
function samira_clean_head() {
    if (get_option('samira_clean_head', true)) {
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
        remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10);
    }
}
add_action('init', 'samira_clean_head');

/**
 * Debug helper function
 */
if (!function_exists('samira_debug')) {
    function samira_debug($data, $die = false) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            echo '<pre style="background: #f0f0f0; padding: 10px; margin: 10px 0; border: 1px solid #ddd;">';
            print_r($data);
            echo '</pre>';
            if ($die) die();
        }
    }
}

/**
 * Inizializzazione opzioni tema alla attivazione
 */
function samira_theme_activation() {
    // Imposta opzioni di default
    $defaults = array(
        'samira_hero_title' => 'Samira Mahmoodi',
        'samira_hero_subtitle' => 'Scrittura, Arte, Rinascita',
        'samira_about_title' => 'Chi sono',
        'samira_about_content' => 'Samira Mahmoodi began writing shortly after graduating from college. In 2016, she received a Bachelor of Science in Nursing. Unable to suppress her despair at that time, journaling her feelings led her to re-discover her love for art and literature.',
        'samira_book_title' => 'To Water Her Garden: A journey of self-discovery',
        'samira_book_year' => '2019',
        'samira_book_description' => 'In questo spazio ho svelato le ragioni dietro la mia tristezza, e dove ho anche scoperto il mio più grande potere: me stessa.',
        'samira_newsletter_title' => 'Resta in contatto',
        'samira_newsletter_description' => 'Iscriviti per ricevere i miei pensieri, aggiornamenti su uscite attuali e future.',
        'samira_accent_color' => '#D4A574',
        'samira_enable_dark_mode' => false,
        'samira_logo_text' => 'SM',
        'samira_footer_text' => 'Scrittrice e artista. L\'arte è il mio rifugio sicuro per l\'espressione di sé.',
        'samira_copyright_name' => 'Samira Mahmoodi',
    );
    
    foreach ($defaults as $option => $value) {
        if (get_option($option) === false) {
            add_option($option, $value);
        }
    }
    
    // Flush rewrite rules per custom post types
    flush_rewrite_rules();
    
    // Log attivazione tema
    error_log('Samira Theme attivato con successo');
}
add_action('after_switch_theme', 'samira_theme_activation');

/**
 * Pulizia alla disattivazione del tema
 */
function samira_theme_deactivation() {
    // Flush rewrite rules
    flush_rewrite_rules();
}
add_action('switch_theme', 'samira_theme_deactivation');

/**
 * Aggiungi CSS inline per accent color
 */
function samira_accent_color_css() {
    $accent_color = get_option('samira_accent_color', '#D4A574');
    
    // Genera colore hover (più scuro)
    $rgb = sscanf($accent_color, "#%02x%02x%02x");
    if ($rgb && count($rgb) === 3) {
        $hover_color = sprintf("#%02x%02x%02x", 
            max(0, min(255, $rgb[0] - 20)), 
            max(0, min(255, $rgb[1] - 20)), 
            max(0, min(255, $rgb[2] - 20))
        );
        
        echo "<style id='samira-accent-color'>:root { --color-accent: {$accent_color}; --color-accent-hover: {$hover_color}; }</style>\n";
    }
}
add_action('wp_head', 'samira_accent_color_css', 20);

/**
 * Controlla requisiti tema
 */
function samira_check_requirements() {
    $wp_version = get_bloginfo('version');
    $php_version = PHP_VERSION;
    
    if (version_compare($wp_version, '5.0', '<')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Samira Theme:</strong> Richiede WordPress 5.0 o superiore. Versione attuale: ' . get_bloginfo('version') . '</p></div>';
        });
    }
    
    if (version_compare($php_version, '7.4', '<')) {
        add_action('admin_notices', function() {
            echo '<div class="notice notice-error"><p><strong>Samira Theme:</strong> Richiede PHP 7.4 o superiore. Versione attuale: ' . PHP_VERSION . '</p></div>';
        });
    }
}
add_action('admin_init', 'samira_check_requirements');
