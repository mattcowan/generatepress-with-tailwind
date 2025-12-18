<?php
/**
 * Debug script to test environment detection
 *
 * Location: wp-content/themes/generatepress_child/debug-env.php
 * Access via: http://yourdomain.com/wp-content/themes/generatepress_child/debug-env.php
 *
 * SECURITY: This script is only available when WP_DEBUG is enabled and user is logged in as admin.
 */

// Load WordPress - try multiple methods for robustness
if (!defined('ABSPATH')) {
    // Method 1: Standard WordPress theme path (works if file is in theme root)
    // Expected path: wp-content/themes/generatepress_child/debug-env.php
    $wp_load_path = dirname(dirname(dirname(__DIR__))) . '/wp-load.php';

    // Method 2: If Method 1 fails, try traversing up from current directory
    if (!file_exists($wp_load_path)) {
        $wp_load_path = dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-load.php';
    }

    // Method 3: Last resort - check if we're in a standard WordPress install
    if (!file_exists($wp_load_path)) {
        // Try going up directories until we find wp-load.php (max 10 levels)
        $current_dir = __DIR__;
        for ($i = 0; $i < 10; $i++) {
            $current_dir = dirname($current_dir);
            if (file_exists($current_dir . '/wp-load.php')) {
                $wp_load_path = $current_dir . '/wp-load.php';
                break;
            }
        }
    }

    if (!file_exists($wp_load_path)) {
        die('Error: Unable to locate wp-load.php. Please ensure this file is in the correct theme directory.');
    }

    require_once $wp_load_path;
}

// Security check: Only allow access if WP_DEBUG is enabled and user is admin
if (!defined('WP_DEBUG') || !WP_DEBUG) {
    wp_die('Debug mode is disabled. Enable WP_DEBUG in wp-config.php to access this script.', 'Debug Access Denied', array('response' => 403));
}

if (!current_user_can('manage_options')) {
    wp_die('You do not have permission to access this script.', 'Access Denied', array('response' => 403));
}

// Simulate WordPress environment
$_SERVER['SERVER_NAME'] = $_SERVER['SERVER_NAME'] ?? 'wplayground';
$_SERVER['HTTP_HOST'] = $_SERVER['HTTP_HOST'] ?? 'wplayground';

echo "<h1>Environment Detection Debug</h1>";
echo "<h2>Server Variables</h2>";
echo "<pre>";
echo "SERVER_NAME: " . ($_SERVER['SERVER_NAME'] ?? 'not set') . "\n";
echo "HTTP_HOST: " . ($_SERVER['HTTP_HOST'] ?? 'not set') . "\n";
echo "</pre>";

echo "<h2>Hostname Resolution</h2>";
echo "<pre>";
$server_name = $_SERVER['SERVER_NAME'] ?? '';
$http_host = $_SERVER['HTTP_HOST'] ?? '';
$http_host_clean = preg_replace('/:\d+$/', '', $http_host);

echo "HTTP_HOST clean: " . $http_host_clean . "\n";

if ($server_name && !str_contains($server_name, ':')) {
    $resolved = gethostbyname($server_name);
    echo "SERVER_NAME ($server_name) resolves to: " . $resolved . "\n";
    echo "Is localhost: " . (($resolved !== $server_name && $resolved === '127.0.0.1') ? 'YES' : 'NO') . "\n";
}

if ($http_host_clean && !str_contains($http_host_clean, ':')) {
    $resolved = gethostbyname($http_host_clean);
    echo "HTTP_HOST ($http_host_clean) resolves to: " . $resolved . "\n";
    echo "Is localhost: " . (($resolved !== $http_host_clean && $resolved === '127.0.0.1') ? 'YES' : 'NO') . "\n";
}
echo "</pre>";

echo "<h2>Dev Server Detection</h2>";
echo "<pre>";
$host = '127.0.0.1';
$ports = [3000, 3001, 3002, 3003, 3004, 3005];

foreach ($ports as $port) {
    set_error_handler(function () {});
    $connection = fsockopen($host, $port, $errno, $errstr, 1);
    restore_error_handler();

    if ($connection) {
        fclose($connection);
        echo "Port $port: OPEN ✓\n";
    } else {
        echo "Port $port: closed ($errno: $errstr)\n";
    }
}
echo "</pre>";
