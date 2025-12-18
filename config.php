<?php
/**
 * Theme Configuration
 *
 * Central configuration file for the GeneratePress Child theme.
 * Developers can customize these settings for their local environment.
 *
 * @package GeneratePress_Child
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Vite Development Server Configuration
 *
 * These settings control the connection to the Vite dev server for Hot Module Replacement (HMR).
 * Adjust these values to match your local development environment.
 *
 * Common scenarios:
 * - WAMP/XAMPP (Windows): localhost:3000
 * - MAMP (Mac): localhost:3000 or localhost:8888
 * - Local by Flywheel: localhost:3000
 * - Laravel Valet: localhost:3000
 * - Custom setup: Change host/port as needed
 *
 * You can override these via WordPress filters:
 * add_filter('generatepress_child_vite_dev_host', function() { return '127.0.0.1'; });
 * add_filter('generatepress_child_vite_dev_port', function() { return 3001; });
 *
 * Or define constants in wp-config.php:
 * define('VITE_DEV_SERVER_HOST', 'localhost');
 * define('VITE_DEV_SERVER_PORT', 3001);
 */

// Dev server host
if (!defined('VITE_DEV_SERVER_HOST')) {
    define('VITE_DEV_SERVER_HOST', apply_filters('generatepress_child_vite_dev_host', 'localhost'));
}

// Dev server port
if (!defined('VITE_DEV_SERVER_PORT')) {
    define('VITE_DEV_SERVER_PORT', apply_filters('generatepress_child_vite_dev_port', 3000));
}

// Dev server protocol (http or https)
if (!defined('VITE_DEV_SERVER_PROTOCOL')) {
    define('VITE_DEV_SERVER_PROTOCOL', apply_filters('generatepress_child_vite_dev_protocol', 'http'));
}

/**
 * Development Server Port Range
 *
 * If the primary port is busy, the system will check these additional ports.
 * Vite's strictPort: false setting will use the next available port if 3000 is taken.
 *
 * This range is checked automatically during dev server detection.
 */
if (!defined('VITE_DEV_SERVER_PORT_RANGE')) {
    define('VITE_DEV_SERVER_PORT_RANGE', apply_filters('generatepress_child_vite_port_range', [3000, 3001, 3002, 3003, 3004, 3005]));
}

/**
 * Get the full Vite dev server URL
 *
 * @since 1.2.0
 * @param int|null $port Optional. Specific port to use. If not provided, uses VITE_DEV_SERVER_PORT.
 * @return string The full dev server URL (e.g., http://localhost:3000)
 */
function generatepress_child_get_vite_url($port = null) {
    $port = $port !== null ? $port : VITE_DEV_SERVER_PORT;
    $url = VITE_DEV_SERVER_PROTOCOL . '://' . VITE_DEV_SERVER_HOST . ':' . $port;
    return apply_filters('generatepress_child_vite_dev_url', $url, $port);
}
