# AGENTS.md - Guida per Sviluppatori e Manutenzione

## 🔧 Guida Tecnica per Sviluppatori

Questo documento fornisce informazioni dettagliate per sviluppatori che vogliono contribuire, modificare o mantenere il **Samira Theme**.

## 🏗️ Architettura del Tema

### Struttura Generale
```
samira-theme/
├── style.css                    # Header tema + CSS principale  
├── functions.php                # Core functions e setup
├── index.php                    # Template main (homepage + fallback)
├── header.php                   # Header HTML structure
├── footer.php                   # Footer HTML structure
├── inc/                         # Include files
│   ├── theme-options.php        # Theme options management
│   ├── newsletter-integration.php # Newsletter APIs
│   └── customizer.php           # WordPress Customizer (future)
├── admin/                       # Admin panel
│   ├── theme-admin.php          # Admin interface
│   ├── admin-style.css          # Admin panel styling
│   └── admin-script.js          # Admin panel JavaScript
├── js/                          # Frontend JavaScript
│   ├── main.js                  # Core functionality
│   └── dark-mode.js             # Dark mode implementation
├── css/                         # Additional styles
├── images/                      # Theme assets
├── template-parts/              # Template partials
└── screenshot.png               # Theme screenshot (1200x900px)
```

### Design Pattern Utilizzati

#### 1. **MVC-like Separation**
- **Model**: `inc/theme-options.php` - Data management
- **View**: Template files (index.php, header.php, footer.php)  
- **Controller**: `functions.php` + `admin/theme-admin.php`

#### 2. **Hook-based Architecture**  
```php
// Actions
add_action('after_setup_theme', 'samira_theme_setup');
add_action('wp_enqueue_scripts', 'samira_theme_scripts');
add_action('admin_enqueue_scripts', 'samira_admin_scripts');

// Filters  
add_filter('samira_get_option', 'custom_option_filter', 10, 3);
add_filter('body_class', 'samira_body_classes');
```

#### 3. **Options API Integration**
```php
// Get option with fallback
$value = samira_get_option('option_name', 'default_value');

// Update option with validation
samira_update_option('option_name', $sanitized_value);
```

## 🎨 Frontend Development

### CSS Architecture

#### Variabili CSS Custom Properties
```css
:root {
  /* Light theme */
  --color-background: #fcfcf9;
  --color-surface: #ffffff;
  --color-text: #1f2121;
  --color-accent: #e26f8e;
  
  /* Spacing system */
  --spacing-xs: 0.5rem;    /* 8px */
  --spacing-sm: 1rem;      /* 16px */
  --spacing-md: 2rem;      /* 32px */
  --spacing-lg: 3rem;      /* 48px */
  --spacing-xl: 4rem;      /* 64px */
  
  /* Typography */
  --font-heading: 'Montserrat', sans-serif;
  --font-body: 'Playfair Display', serif;
}

/* Dark mode override */
.dark-mode {
  --color-background: #1a1a1a;
  --color-surface: #2a2a2a;
  --color-text: #f5f5f5;
}
```

#### Responsive Breakpoints
```css
/* Mobile first approach */
@media (max-width: 480px)  { /* Mobile */ }
@media (max-width: 768px)  { /* Tablet portrait */ }  
@media (max-width: 1024px) { /* Tablet landscape */ }
@media (max-width: 1200px) { /* Desktop small */ }
/* 1200px+ = Desktop large (default) */
```

#### Component-based CSS
```css
/* Component: Button */
.btn {
  /* Base styles */
}
.btn--primary { /* Modifier */ }
.btn--outline { /* Modifier */ }

/* Component: Card */  
.card {
  /* Base styles */
}
.card--elevated { /* Modifier */ }
```

### JavaScript Architecture

#### Main.js Structure
```javascript
(function($) {
    'use strict';
    
    // Module pattern
    const SamiraTheme = {
        // Configuration
        config: {
            selectors: {
                header: '.header',
                mobileToggle: '.mobile-menu-toggle'
            }
        },
        
        // Initialization
        init: function() {
            this.bindEvents();
            this.initComponents();
        },
        
        // Event bindings
        bindEvents: function() {
            $(document).ready(this.onDocumentReady.bind(this));
            $(window).on('scroll', this.onScroll.bind(this));
        },
        
        // Component initializations
        initComponents: function() {
            this.initSmoothScroll();
            this.initNewsletterForm();
            this.initAnimations();
        }
    };
    
    // Auto-initialize
    SamiraTheme.init();
    
})(jQuery);
```

#### Dark Mode Implementation  
```javascript
// dark-mode.js structure
const DarkMode = {
    init() {
        this.bindToggle();
        this.loadPreference();  
        this.watchSystemPreference();
    },
    
    toggle() {
        document.body.classList.toggle('dark-mode');
        this.savePreference();
        this.updateIcon();
    },
    
    savePreference() {
        const isDark = document.body.classList.contains('dark-mode');
        localStorage.setItem('samira-dark-mode', isDark);
    }
};
```

## 🛠️ Backend Development  

### Theme Options System

#### Core Functions (`inc/theme-options.php`)
```php
// Get option with default fallback and filters
function samira_get_option($option_name, $default = '') {
    $value = get_option($option_name, $default);
    return apply_filters('samira_get_option', $value, $option_name, $default);
}

// Update option with validation
function samira_update_option($option_name, $value) {
    $sanitized = samira_sanitize_option($value, $option_name);
    $updated = update_option($option_name, $sanitized);
    do_action('samira_option_updated', $option_name, $value, $updated);
    return $updated;
}

// Sanitization dispatcher  
function samira_sanitize_option($value, $option_name) {
    switch ($option_name) {
        case 'samira_hero_image':
            return esc_url_raw($value);
        case 'samira_contact_email':
            return sanitize_email($value);  
        case 'samira_accent_color':
            return sanitize_hex_color($value);
        default:
            return sanitize_text_field($value);
    }
}
```

#### Default Options Pattern
```php
function samira_get_default_options() {
    return array(
        'samira_hero_title' => 'Samira Mahmoodi',
        'samira_hero_subtitle' => 'Scrittura, Arte, Rinascita',
        'samira_accent_color' => '#e26f8e',
        // ... more defaults
    );
}
```

### Admin Panel Architecture

#### Tab System (`admin/theme-admin.php`)
```php  
function samira_admin_page() {
    $current_tab = $_GET['tab'] ?? 'general';
    
    // Render tabs navigation
    echo '<nav class="nav-tab-wrapper">';
    foreach ($tabs as $tab_key => $tab_label) {
        $active_class = $current_tab === $tab_key ? 'nav-tab-active' : '';
        echo '<a href="?page=samira-theme-settings&tab=' . $tab_key . '" 
                class="nav-tab ' . $active_class . '">' . $tab_label . '</a>';
    }
    echo '</nav>';
    
    // Render tab content
    switch ($current_tab) {
        case 'hero':
            samira_render_hero_tab();
            break;
        // ... other tabs
    }
}

function samira_render_hero_tab() {
    // Tab-specific form fields
}
```

#### AJAX Handlers Pattern
```php
function samira_ajax_handler() {
    // Security checks
    if (!wp_verify_nonce($_POST['nonce'], 'samira_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
    }
    
    if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Insufficient permissions']);  
    }
    
    // Process request
    $result = samira_process_ajax_request($_POST);
    
    // Send response
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);  
    }
}
add_action('wp_ajax_samira_action', 'samira_ajax_handler');
```

### Newsletter Integration

#### Provider Abstraction (`inc/newsletter-integration.php`)
```php
function samira_newsletter_subscribe($email, $name) {
    $provider = get_option('samira_newsletter_provider');
    $api_key = get_option('samira_newsletter_api_key');
    $list_id = get_option('samira_newsletter_list_id');
    
    switch ($provider) {
        case 'mailchimp':
            return samira_mailchimp_subscribe($email, $name, $api_key, $list_id);
        case 'brevo':
            return samira_brevo_subscribe($email, $name, $api_key, $list_id);
        default:
            return ['success' => false, 'message' => 'No provider configured'];
    }
}

function samira_mailchimp_subscribe($email, $name, $api_key, $list_id) {
    $datacenter = substr($api_key, strpos($api_key, '-') + 1);
    $url = "https://{$datacenter}.api.mailchimp.com/3.0/lists/{$list_id}/members/";
    
    $response = wp_remote_post($url, [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
            'Content-Type' => 'application/json'
        ],
        'body' => json_encode([
            'email_address' => $email,
            'status' => 'subscribed',
            'merge_fields' => ['FNAME' => $name]
        ])
    ]);
    
    return samira_process_newsletter_response($response);
}
```

## 🎯 Custom Post Types

### Implementation Pattern
```php
function samira_register_post_types() {
    // Portfolio/Art
    register_post_type('portfolio', [
        'labels' => [
            'name' => __('Portfolio', 'samira-theme'),
            'singular_name' => __('Opera', 'samira-theme'),
        ],
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
        'has_archive' => true,
        'rewrite' => ['slug' => 'portfolio'],
        'show_in_rest' => true, // Gutenberg support
    ]);
    
    // Books  
    register_post_type('books', [
        'labels' => [
            'name' => __('Libri', 'samira-theme'),
            'singular_name' => __('Libro', 'samira-theme'),
        ],
        'public' => true,
        'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'],
        'has_archive' => true,
    ]);
}
add_action('init', 'samira_register_post_types');
```

### Custom Fields Integration
```php
// Meta boxes for Books post type
function samira_add_book_meta_boxes() {
    add_meta_box(
        'book-details',
        __('Dettagli Libro', 'samira-theme'),
        'samira_book_meta_box_callback',
        'books'
    );
}

function samira_book_meta_box_callback($post) {
    wp_nonce_field('samira_book_meta', 'samira_book_meta_nonce');
    
    $year = get_post_meta($post->ID, 'book_year', true);
    $goodreads = get_post_meta($post->ID, 'goodreads_link', true);
    
    echo '<table class="form-table">';
    echo '<tr><th><label for="book_year">Anno Pubblicazione</label></th>';
    echo '<td><input type="text" id="book_year" name="book_year" value="' . esc_attr($year) . '" /></td></tr>';
    echo '</table>';
}
```

## 🧪 Testing e Quality Assurance

### Browser Testing Matrix
| Browser | Desktop | Mobile | Notes |
|---------|---------|---------|--------|
| Chrome | ✅ 90+ | ✅ 90+ | Primary target |
| Firefox | ✅ 88+ | ✅ 88+ | Full support |
| Safari | ✅ 14+ | ✅ 14+ | WebKit testing |
| Edge | ✅ 90+ | ✅ 90+ | Chromium-based |
| IE11 | ⚠️ Limited | ❌ No | Legacy fallbacks |

### PHP Compatibility Testing
```bash
# Test with different PHP versions
composer require --dev phpcompatibility/php-compatibility
./vendor/bin/phpcs --standard=PHPCompatibility --runtime-set testVersion 7.4-8.2 .
```

### WordPress Coding Standards
```bash
# Install WPCS
composer global require wp-coding-standards/wpcs

# Run checks  
phpcs --standard=WordPress samira-theme/
```

### JavaScript Testing
```javascript
// Example unit test (Jest)
describe('Dark Mode', () => {
    test('should toggle dark mode class', () => {
        const darkMode = new DarkMode();
        darkMode.toggle();
        expect(document.body.classList.contains('dark-mode')).toBe(true);
    });
});
```

## 📊 Performance Optimization  

### CSS Optimization
```css
/* Use efficient selectors */
.btn { } /* Good: class selector */
#header .nav ul li a { } /* Bad: overly specific */

/* Minimize reflows */
.element {
    transform: translateX(10px); /* Good: GPU acceleration */  
    left: 10px; /* Bad: triggers layout */
}

/* Use CSS custom properties for runtime changes */
:root {
    --dynamic-color: #e26f8e;
}
.element {
    color: var(--dynamic-color); /* Easy to update via JS */
}
```

### JavaScript Performance
```javascript
// Throttle/debounce expensive operations
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

// Use efficient DOM queries
const $elements = $('.class'); // Cache jQuery objects
const element = document.getElementById('id'); // Faster than querySelector

// Minimize DOM manipulation
const fragment = document.createDocumentFragment(); // Build offline
// ... add elements to fragment
container.appendChild(fragment); // Single DOM update
```

### Image Optimization Guidelines
```php
// Add image sizes  
function samira_image_sizes() {
    add_image_size('hero-image', 600, 600, true);
    add_image_size('book-cover', 400, 600, true);  
    add_image_size('portfolio-thumb', 800, 600, true);
}
add_action('after_setup_theme', 'samira_image_sizes');

// WebP support check
function samira_webp_support() {
    return function_exists('imagewebp') && 
           (imagetypes() & IMG_WEBP);
}
```

## 🔒 Security Best Practices

### Input Sanitization
```php  
// Sanitize based on expected data type
$text = sanitize_text_field($_POST['text']);
$email = sanitize_email($_POST['email']);  
$url = esc_url_raw($_POST['url']);
$html = wp_kses_post($_POST['content']); // Allow safe HTML
$int = absint($_POST['number']); // Positive integer
$slug = sanitize_key($_POST['slug']);
```

### Output Escaping
```php
// Escape based on context
echo esc_html($user_input); // HTML content
echo esc_attr($user_input); // HTML attributes  
echo esc_url($user_input);  // URLs
echo wp_kses_post($html_content); // Rich content
```

### Nonce Verification  
```php
// Generate nonce
wp_nonce_field('samira_action', 'samira_nonce');

// Verify nonce
if (!wp_verify_nonce($_POST['samira_nonce'], 'samira_action')) {
    wp_die('Security check failed');
}
```

### Capability Checks
```php
// Check user permissions
if (!current_user_can('manage_options')) {
    wp_send_json_error(['message' => 'Insufficient permissions']);
}

// Role-based access
if (!current_user_can('edit_posts')) {
    return;
}
```

## 🚀 Deployment e Release

### Version Management
```php
// functions.php
define('SAMIRA_THEME_VERSION', '1.0.0');

// Use version in enqueues for cache busting
wp_enqueue_style('samira-style', get_stylesheet_uri(), [], SAMIRA_THEME_VERSION);
```

### Build Process (Optional)
```json
{
  "scripts": {
    "build": "npm run build:css && npm run build:js",
    "build:css": "postcss src/css/style.css -o style.css",
    "build:js": "webpack --mode production",  
    "watch": "concurrently \"npm run watch:css\" \"npm run watch:js\"",
    "lint": "eslint js/ && stylelint css/"
  }
}
```

### Pre-release Checklist
- [ ] Code review completato
- [ ] Test cross-browser eseguiti
- [ ] Performance audit (PageSpeed, GTmetrix)  
- [ ] Accessibility audit (WAVE, axe)
- [ ] WordPress.org Theme Review Guidelines verificate
- [ ] Screenshot aggiornato (1200x900px)
- [ ] Changelog aggiornato
- [ ] Version bump in style.css e functions.php
- [ ] README e documentazione aggiornati

### Semantic Versioning
```
MAJOR.MINOR.PATCH

MAJOR: Breaking changes (es. 1.0.0 → 2.0.0)  
MINOR: New features, backward compatible (es. 1.0.0 → 1.1.0)
PATCH: Bug fixes, backward compatible (es. 1.0.0 → 1.0.1)
```

## 🔧 Debugging e Troubleshooting

### Debug Mode Setup
```php
// wp-config.php for development
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);  
define('WP_DEBUG_DISPLAY', false);
define('SCRIPT_DEBUG', true); // Use unminified scripts
```

### Custom Debug Functions  
```php
// Theme debug helper
function samira_debug($data, $die = false) {
    if (defined('WP_DEBUG') && WP_DEBUG) {
        echo '<pre style="background: #f0f0f0; padding: 10px; margin: 10px 0;">';
        print_r($data);
        echo '</pre>';
        if ($die) die();
    }
}

// Log to file
function samira_log($message, $level = 'info') {
    if (WP_DEBUG_LOG) {
        error_log("[SAMIRA-{$level}] " . $message);
    }
}
```

### Common Issues Debug

#### Newsletter Issues
```php
// Test newsletter connection
function samira_debug_newsletter() {
    $provider = get_option('samira_newsletter_provider');  
    $api_key = get_option('samira_newsletter_api_key');
    
    if (empty($api_key)) {
        samira_log('Newsletter: API key missing', 'error');
        return false;
    }
    
    $test_result = samira_test_newsletter_connection($provider, $api_key, 'test-list');
    samira_log("Newsletter test result: " . json_encode($test_result));
}
```

#### JavaScript Errors
```javascript
// Global error handler  
window.addEventListener('error', function(e) {
    if (window.console && console.error) {
        console.error('Samira Theme JS Error:', e.error);
    }
});

// Debug specific functions
const SamiraDebug = {
    log(message, data = null) {
        if (window.location.href.indexOf('debug=1') !== -1) {
            console.log(`[SAMIRA] ${message}`, data);
        }
    }
};
```

## 📋 Contributing Guidelines

### Code Style  
- **PHP**: WordPress Coding Standards
- **JavaScript**: ESLint with WordPress config  
- **CSS**: Stylelint with standard config
- **Indentation**: 4 spaces (PHP), 2 spaces (JS/CSS)

### Commit Message Format
```
type(scope): description

feat(newsletter): add Brevo integration
fix(dark-mode): resolve toggle persistence issue  
docs(readme): update installation instructions
style(css): improve button hover animations
refactor(admin): optimize options page performance
test(js): add unit tests for dark mode
```

### Pull Request Process
1. **Fork** repository
2. **Create feature branch** from `develop`
3. **Write tests** for new functionality  
4. **Update documentation** if needed
5. **Submit PR** with clear description
6. **Address review feedback**

### Issue Reporting Template
```markdown
## Bug Report

**Describe the bug**
Clear description of what the bug is.

**To Reproduce**  
Steps to reproduce the behavior:
1. Go to '...'
2. Click on '...'  
3. See error

**Expected behavior**
What you expected to happen.

**Screenshots**
If applicable, add screenshots.

**Environment:**
- WordPress version: 
- PHP version:
- Theme version:
- Browser:
```

## 📚 Resources e References

### WordPress Development
- [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/)
- [Theme Review Guidelines](https://make.wordpress.org/themes/handbook/review/)  
- [WordPress APIs](https://codex.wordpress.org/WordPress_APIs)

### Frontend Technologies
- [CSS Custom Properties](https://developer.mozilla.org/en-US/docs/Web/CSS/--*)
- [Intersection Observer](https://developer.mozilla.org/en-US/docs/Web/API/Intersection_Observer_API)
- [Web Accessibility Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)

### Tools
- **Testing**: [WAVE](https://wave.webaim.org/), [axe DevTools](https://www.deque.com/axe/)
- **Performance**: [PageSpeed Insights](https://pagespeed.web.dev/), [GTmetrix](https://gtmetrix.com/)  
- **Development**: [Local by Flywheel](https://localwp.com/), [XAMPP](https://www.apachefriends.org/)

---

## 📞 Support per Sviluppatori

### Technical Support
- **Email**: dev-support@samira-theme.com
- **Discord**: [Developer Community](https://discord.gg/samira-dev)  
- **Documentation**: [Developer Docs](https://docs.samira-theme.com/developers)

### Contribution Recognition  
Contributors significativi verranno:
- Aggiunti ai credits del tema
- Invitati al team di testing beta
- Riconosciuti nella community

---

**Happy coding!** 🚀

Questo documento è in continuo aggiornamento. Per suggerimenti o correzioni, apri una issue su GitHub.
