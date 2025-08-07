<?php
/**
 * Newsletter Integration (Mailchimp & Brevo)
 * 
 * @package Samira_Theme
 * @version 1.0.0
 */

// Impedisce accesso diretto
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Handler AJAX per newsletter signup
 */
function samira_newsletter_signup() {
    // Verifica nonce per sicurezza
    if (!wp_verify_nonce($_POST['nonce'], 'samira_nonce')) {
        wp_send_json_error(array('message' => __( 'Security error', 'samira-theme' )));
    }
    
    // Sanitize e validate input
    $email = sanitize_email($_POST['email'] ?? '');
    $name = sanitize_text_field($_POST['name'] ?? '');
    
    if (empty($email) || !is_email($email)) {
        wp_send_json_error(array('message' => __( 'Invalid email address', 'samira-theme' )));
    }
    
    if (empty($name)) {
        wp_send_json_error(array('message' => __( 'Name is required', 'samira-theme' )));
    }
    
    // Get provider settings
    $provider = get_option('samira_newsletter_provider', 'mailchimp');
    $api_key = get_option('samira_newsletter_api_key', '');
    $list_id = get_option('samira_newsletter_list_id', '');
    
    if (empty($api_key) || empty($list_id)) {
        wp_send_json_error(array('message' => __( 'Newsletter not configured. Contact administrator.', 'samira-theme' )));
    }
    
    // Subscribe based on provider
    if ($provider === 'mailchimp') {
        $result = samira_mailchimp_subscribe($email, $name, $api_key, $list_id);
    } elseif ($provider === 'brevo') {
        $result = samira_brevo_subscribe($email, $name, $api_key, $list_id);
    } else {
        wp_send_json_error(array('message' => __( 'Newsletter provider not supported', 'samira-theme' )));
    }
    
    // Log subscription attempt
    samira_log_newsletter_subscription($email, $name, $provider, $result);
    
    // Send response
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_samira_newsletter_signup', 'samira_newsletter_signup');
add_action('wp_ajax_nopriv_samira_newsletter_signup', 'samira_newsletter_signup');

/**
 * Mailchimp subscription
 */
function samira_mailchimp_subscribe($email, $name, $api_key, $list_id) {
    // Extract datacenter from API key
    $datacenter = substr($api_key, strpos($api_key, '-') + 1);
    
    if (empty($datacenter)) {
        return array('success' => false, 'message' => __( 'Invalid Mailchimp API key', 'samira-theme' ));
    }
    
    $url = "https://{$datacenter}.api.mailchimp.com/3.0/lists/{$list_id}/members/";
    
    // Prepare data
    $member_data = array(
        'email_address' => $email,
        'status' => 'subscribed',
        'merge_fields' => array(
            'FNAME' => $name,
        ),
        'tags' => array('samira-website'),
        'timestamp_signup' => current_time('c'),
    );
    
    // Make API request
    $response = wp_remote_post($url, array(
        'timeout' => 30,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
            'Content-Type' => 'application/json',
        ),
        'body' => wp_json_encode($member_data)
    ));
    
    // Handle response
    if (is_wp_error($response)) {
        return array(
            'success' => false, 
            'message' => __( 'Mailchimp connection error', 'samira-theme' ),
            'error_details' => $response->get_error_message()
        );
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if ($response_code === 200) {
        return array(
            'success' => true, 
            'message' => __( 'Subscription successful!', 'samira-theme' )
        );
    } elseif ($response_code === 400 && isset($body['title']) && $body['title'] === 'Member Exists') {
        return array(
            'success' => false, 
            'message' => __( 'You are already subscribed to the newsletter!', 'samira-theme' )
        );
    } else {
        $error_message = isset($body['detail']) ? $body['detail'] : __( 'Unknown error', 'samira-theme' );
        return array(
            'success' => false, 
            'message' => $error_message,
            'error_code' => $response_code
        );
    }
}

/**
 * Brevo (SendinBlue) subscription
 */
function samira_brevo_subscribe($email, $name, $api_key, $list_id) {
    $url = "https://api.brevo.com/v3/contacts";
    
    // Prepare data
    $contact_data = array(
        'email' => $email,
        'attributes' => array(
            'FIRSTNAME' => $name,
            'SOURCE' => 'samira-website',
            'SIGNUP_DATE' => current_time('Y-m-d')
        ),
        'listIds' => array((int)$list_id),
        'updateEnabled' => true
    );
    
    // Make API request
    $response = wp_remote_post($url, array(
        'timeout' => 30,
        'headers' => array(
            'api-key' => $api_key,
            'Content-Type' => 'application/json',
        ),
        'body' => wp_json_encode($contact_data)
    ));
    
    // Handle response
    if (is_wp_error($response)) {
        return array(
            'success' => false, 
            'message' => __( 'Brevo connection error', 'samira-theme' ),
            'error_details' => $response->get_error_message()
        );
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    $body = json_decode(wp_remote_retrieve_body($response), true);
    
    if ($response_code === 201) {
        return array(
            'success' => true, 
            'message' => __( 'Subscription successful!', 'samira-theme' )
        );
    } elseif ($response_code === 204) {
        return array(
            'success' => true, 
            'message' => __( 'Profile updated successfully!', 'samira-theme' )
        );
    } else {
        $error_message = isset($body['message']) ? $body['message'] : __( 'Unknown error', 'samira-theme' );
        return array(
            'success' => false, 
            'message' => $error_message,
            'error_code' => $response_code
        );
    }
}

/**
 * Test newsletter connection
 */
function samira_test_newsletter_connection($provider, $api_key, $list_id) {
    if ($provider === 'mailchimp') {
        return samira_test_mailchimp_connection($api_key, $list_id);
    } elseif ($provider === 'brevo') {
        return samira_test_brevo_connection($api_key, $list_id);
    }
    
    return array('success' => false, 'message' => __( 'Provider not supported', 'samira-theme' ));
}

/**
 * Test Mailchimp connection
 */
function samira_test_mailchimp_connection($api_key, $list_id) {
    $datacenter = substr($api_key, strpos($api_key, '-') + 1);
    
    if (empty($datacenter)) {
        return array('success' => false, 'message' => __( 'Invalid API key', 'samira-theme' ));
    }
    
    $url = "https://{$datacenter}.api.mailchimp.com/3.0/lists/{$list_id}";
    
    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
        ),
    ));
    
    if (is_wp_error($response)) {
        return array('success' => false, 'message' => __( 'Connection error', 'samira-theme' ));
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    
    if ($response_code === 200) {
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return array(
            'success' => true, 
            'message' => __( 'Connection successful', 'samira-theme' ),
            'list_name' => $body['name'] ?? __( 'Unnamed list', 'samira-theme' ),
            'subscriber_count' => $body['stats']['member_count'] ?? 0
        );
    }
    
    return array('success' => false, 'message' => __( 'Invalid credentials', 'samira-theme' ));
}

/**
 * Test Brevo connection
 */
function samira_test_brevo_connection($api_key, $list_id) {
    $url = "https://api.brevo.com/v3/contacts/lists/{$list_id}";
    
    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'headers' => array(
            'api-key' => $api_key,
        ),
    ));
    
    if (is_wp_error($response)) {
        return array('success' => false, 'message' => __( 'Connection error', 'samira-theme' ));
    }
    
    $response_code = wp_remote_retrieve_response_code($response);
    
    if ($response_code === 200) {
        $body = json_decode(wp_remote_retrieve_body($response), true);
        return array(
            'success' => true, 
            'message' => __( 'Connection successful', 'samira-theme' ),
            'list_name' => $body['name'] ?? __( 'Unnamed list', 'samira-theme' ),
            'subscriber_count' => $body['totalSubscribers'] ?? 0
        );
    }
    
    return array('success' => false, 'message' => __( 'Invalid credentials', 'samira-theme' ));
}

/**
 * Get newsletter lists
 */
function samira_get_newsletter_lists($provider, $api_key) {
    if ($provider === 'mailchimp') {
        return samira_get_mailchimp_lists($api_key);
    } elseif ($provider === 'brevo') {
        return samira_get_brevo_lists($api_key);
    }
    
    return array();
}

/**
 * Get Mailchimp lists
 */
function samira_get_mailchimp_lists($api_key) {
    $datacenter = substr($api_key, strpos($api_key, '-') + 1);
    
    if (empty($datacenter)) {
        return array();
    }
    
    $url = "https://{$datacenter}.api.mailchimp.com/3.0/lists?count=50";
    
    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'headers' => array(
            'Authorization' => 'Basic ' . base64_encode('user:' . $api_key),
        ),
    ));
    
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return array();
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    $lists = array();
    
    if (isset($body['lists'])) {
        foreach ($body['lists'] as $list) {
            $lists[$list['id']] = array(
                'name' => $list['name'],
                'subscribers' => $list['stats']['member_count']
            );
        }
    }
    
    return $lists;
}

/**
 * Get Brevo lists
 */
function samira_get_brevo_lists($api_key) {
    $url = "https://api.brevo.com/v3/contacts/lists?limit=50";
    
    $response = wp_remote_get($url, array(
        'timeout' => 15,
        'headers' => array(
            'api-key' => $api_key,
        ),
    ));
    
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) !== 200) {
        return array();
    }
    
    $body = json_decode(wp_remote_retrieve_body($response), true);
    $lists = array();
    
    if (isset($body['lists'])) {
        foreach ($body['lists'] as $list) {
            $lists[$list['id']] = array(
                'name' => $list['name'],
                'subscribers' => $list['totalSubscribers']
            );
        }
    }
    
    return $lists;
}

/**
 * Log newsletter subscription attempts
 */
function samira_log_newsletter_subscription($email, $name, $provider, $result) {
    if (!get_option('samira_log_newsletter', false)) {
        return;
    }
    
    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'email' => $email,
        'name' => $name,
        'provider' => $provider,
        'success' => $result['success'],
        'message' => $result['message'],
        'ip' => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
        'user_agent' => sanitize_text_field($_SERVER['HTTP_USER_AGENT'] ?? '')
    );
    
    $logs = get_option('samira_newsletter_logs', array());
    $logs[] = $log_entry;
    
    // Keep only last 100 entries
    if (count($logs) > 100) {
        $logs = array_slice($logs, -100);
    }
    
    update_option('samira_newsletter_logs', $logs);
}

/**
 * Get newsletter subscription statistics
 */
function samira_get_newsletter_stats() {
    $logs = get_option('samira_newsletter_logs', array());
    
    if (empty($logs)) {
        return array(
            'total_attempts' => 0,
            'successful_subscriptions' => 0,
            'success_rate' => 0,
            'recent_activity' => array()
        );
    }
    
    $total_attempts = count($logs);
    $successful_subscriptions = count(array_filter($logs, function($log) {
        return $log['success'];
    }));
    
    $success_rate = $total_attempts > 0 ? round(($successful_subscriptions / $total_attempts) * 100, 1) : 0;
    
    // Get recent activity (last 10 entries)
    $recent_activity = array_slice($logs, -10);
    $recent_activity = array_reverse($recent_activity);
    
    return array(
        'total_attempts' => $total_attempts,
        'successful_subscriptions' => $successful_subscriptions,
        'success_rate' => $success_rate,
        'recent_activity' => $recent_activity
    );
}

/**
 * Clear newsletter logs
 */
function samira_clear_newsletter_logs() {
    delete_option('samira_newsletter_logs');
}

/**
 * AJAX handler for testing newsletter connection
 */
function samira_test_newsletter_ajax() {
    if (!wp_verify_nonce($_POST['nonce'], 'samira_nonce') || !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __( 'Access denied', 'samira-theme' )));
    }
    
    $provider = sanitize_text_field($_POST['provider'] ?? '');
    $api_key = sanitize_text_field($_POST['api_key'] ?? '');
    $list_id = sanitize_text_field($_POST['list_id'] ?? '');
    
    $result = samira_test_newsletter_connection($provider, $api_key, $list_id);
    
    if ($result['success']) {
        wp_send_json_success($result);
    } else {
        wp_send_json_error($result);
    }
}
add_action('wp_ajax_samira_test_newsletter', 'samira_test_newsletter_ajax');

/**
 * AJAX handler for getting newsletter lists
 */
function samira_get_newsletter_lists_ajax() {
    if (!wp_verify_nonce($_POST['nonce'], 'samira_nonce') || !current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __( 'Access denied', 'samira-theme' )));
    }
    
    $provider = sanitize_text_field($_POST['provider'] ?? '');
    $api_key = sanitize_text_field($_POST['api_key'] ?? '');
    
    $lists = samira_get_newsletter_lists($provider, $api_key);
    
    wp_send_json_success(array('lists' => $lists));
}
add_action('wp_ajax_samira_get_newsletter_lists', 'samira_get_newsletter_lists_ajax');
