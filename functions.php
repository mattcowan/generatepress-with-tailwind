<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

/**
 * Filter out the child theme's style.css from being loaded.
 *
 * We use Vite-compiled CSS instead of the default child theme stylesheet.
 *
 * @since 0.1
 * @param string $html   The link tag for the enqueued style.
 * @param string $handle The style's registered handle.
 * @param string $href   The stylesheet's source URL.
 * @param string $media  The stylesheet's media attribute.
 * @return string Empty string for child theme stylesheet, original tag otherwise.
 */
function generatepress_child_filter_style_tag( $html, $handle, $href, $media ) {
    if ( 'generate-child' === $handle ) {
        return '';
    }
    return $html;
}
add_filter( 'style_loader_tag', 'generatepress_child_filter_style_tag', 10, 4 );

/**
 * Load production asset functions (always loaded)
 */
require_once get_stylesheet_directory() . '/functions/prod-assets.php';

/**
 * Load development asset functions (only in debug/non-production environments)
 *
 * These functions enable Vite dev server detection and Hot Module Replacement.
 * They are excluded in production for performance and security.
 *
 * Dev mode is enabled when ANY of these conditions are true:
 * - WP_DEBUG is true
 * - WP_LOCAL_DEV is true
 * - WP_ENVIRONMENT_TYPE is set and not 'production'
 * - Running on localhost/127.0.0.1
 * - Running on common local dev domains (.local, .test, .dev, .localhost)
 * - Hostname resolves to 127.0.0.1 or ::1
 * - 'generatepress_child_is_dev_environment' filter returns true
 */
function generatepress_child_is_dev_environment() {
    // Check WP_DEBUG
    if (defined('WP_DEBUG') && WP_DEBUG) {
        return true;
    }

    // Check WP_LOCAL_DEV (common in local development setups)
    if (defined('WP_LOCAL_DEV') && WP_LOCAL_DEV) {
        return true;
    }

    // Check WP_ENVIRONMENT_TYPE
    if (defined('WP_ENVIRONMENT_TYPE') && WP_ENVIRONMENT_TYPE !== 'production') {
        return true;
    }

    // Check if running on localhost or local IP
    $server_name = isset($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : '';
    $http_host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '';

    // Strip port from HTTP_HOST if present (e.g., "wplayground:8080" -> "wplayground")
    $http_host_clean = preg_replace('/:\d+$/', '', $http_host);

    // Check for localhost variants
    if (in_array($server_name, ['localhost', '127.0.0.1', '::1'], true) ||
        in_array($http_host_clean, ['localhost', '127.0.0.1', '::1'], true)) {
        return true;
    }

    // Check for common local development TLDs
    $local_tlds = ['.local', '.test', '.dev', '.localhost', '.invalid'];
    foreach ($local_tlds as $tld) {
        if (substr($server_name, -strlen($tld)) === $tld ||
            substr($http_host_clean, -strlen($tld)) === $tld) {
            return true;
        }
    }

    // Check for local IP ranges (192.168.x.x, 10.x.x.x, 172.16-31.x.x)
    if (preg_match('/^(192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\.)/i', $server_name) ||
        preg_match('/^(192\.168\.|10\.|172\.(1[6-9]|2[0-9]|3[01])\.)/i', $http_host_clean)) {
        return true;
    }

    // Check if hostname resolves to localhost (handles custom hosts file entries)
    // gethostbyname() returns the hostname unchanged if resolution fails (safe fallback)
    if ($server_name && $server_name !== '::1') {
        $resolved_ip = gethostbyname($server_name);
        // If resolution succeeded and points to localhost
        if ($resolved_ip !== $server_name && $resolved_ip === '127.0.0.1') {
            return true;
        }
    }

    if ($http_host_clean && $http_host_clean !== $server_name && $http_host_clean !== '::1') {
        $resolved_ip = gethostbyname($http_host_clean);
        // If resolution succeeded and points to localhost
        if ($resolved_ip !== $http_host_clean && $resolved_ip === '127.0.0.1') {
            return true;
        }
    }

    // Allow filtering for custom dev environment detection
    // Usage: add_filter('generatepress_child_is_dev_environment', '__return_true');
    return apply_filters('generatepress_child_is_dev_environment', false);
}

if (generatepress_child_is_dev_environment()) {
    require_once get_stylesheet_directory() . '/functions/dev-assets.php';
}
