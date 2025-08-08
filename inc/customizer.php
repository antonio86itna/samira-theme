<?php
/**
 * WordPress Customizer Integration
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add customizer settings (complementary to admin panel)
 */
function samira_customize_register($wp_customize) {

    // Quick settings panel (for basic users)
    $wp_customize->add_panel('samira_quick_settings', array(
        'title' => __('Samira Quick Settings', 'samira-theme'),
        'description' => __('Quick settings for the Samira Theme', 'samira-theme'),
        'priority' => 30,
    ));

    // Colors section
    $wp_customize->add_section('samira_colors', array(
        'title' => __('Colors', 'samira-theme'),
        'panel' => 'samira_quick_settings',
        'priority' => 10,
    ));

    // Accent color
    $wp_customize->add_setting('samira_accent_color', array(
        'default' => '#D4A574',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport' => 'postMessage',
    ));

    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'samira_accent_color', array(
        'label' => __('Accent Color', 'samira-theme'),
        'section' => 'samira_colors',
    )));

    // Typography section (future enhancement)
    $wp_customize->add_section('samira_typography', array(
        'title' => __('Typography', 'samira-theme'),
        'panel' => 'samira_quick_settings',
        'priority' => 20,
    ));

    // Link to full admin panel
    $wp_customize->add_section('samira_admin_link', array(
        'title' => __('Full Settings', 'samira-theme'),
        'priority' => 200,
        'description' => sprintf(
            __('For advanced settings, go to %s', 'samira-theme'),
            '<a href="' . admin_url('admin.php?page=samira-theme-settings') . '">' . __('Samira Theme Panel', 'samira-theme') . '</a>'
        ),
    ));
}
add_action('customize_register', 'samira_customize_register');

/**
 * Live preview JavaScript
 */
function samira_customizer_live_preview() {
    wp_enqueue_script('samira-customizer-preview', 
        SAMIRA_THEME_URI . '/js/customizer-preview.js', 
        array('customize-preview'), 
        SAMIRA_THEME_VERSION, 
        true
    );
}
add_action('customize_preview_init', 'samira_customizer_live_preview');
