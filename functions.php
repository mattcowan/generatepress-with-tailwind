<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

/**
 * Dequeue the default style.css (we use Vite-compiled CSS instead)
 */
function generatepress_child_dequeue_default_style() {
    wp_dequeue_style('generatepress-child');
}
add_action('wp_enqueue_scripts', 'generatepress_child_dequeue_default_style', 15);

/**
 * Get Vite manifest with caching
 */
function generatepress_child_get_manifest() {
    $manifest_path = get_stylesheet_directory() . '/dist/.vite/manifest.json';

    // Use manifest file modification time as cache key for auto-invalidation
    if (file_exists($manifest_path)) {
        $cache_key = 'vite_manifest_' . filemtime($manifest_path);
    } else {
        $cache_key = 'vite_manifest_missing';
    }

    // Try to get cached manifest
    $manifest = get_transient($cache_key);

    if (false === $manifest) {
        // Cache miss - read and decode manifest
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);
            if ($manifest) {
                // Cache for 1 hour (manifest rarely changes in production)
                set_transient($cache_key, $manifest, HOUR_IN_SECONDS);
            }
        }
    }

    return $manifest;
}

/**
 * Enqueue compiled Vite assets (CSS and JS)
 */
function generatepress_child_enqueue_assets() {
    $manifest = generatepress_child_get_manifest();

    if (!$manifest) {
        error_log('Vite manifest not found. Run "npm run build" or "npm run dev" in the theme directory.');
        return;
    }

    // Enqueue the main JavaScript file
    if (isset($manifest['src/js/main.js'])) {
        $js_file = $manifest['src/js/main.js']['file'];
        wp_enqueue_script(
            'generatepress-child-main',
            get_stylesheet_directory_uri() . '/dist/' . $js_file,
            array(),
            null,
            true
        );
    }

    // Enqueue the main CSS file
    if (isset($manifest['src/css/main.css'])) {
        $css_file = $manifest['src/css/main.css']['file'];
        wp_enqueue_style(
            'generatepress-child-main',
            get_stylesheet_directory_uri() . '/dist/' . $css_file,
            array(),
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'generatepress_child_enqueue_assets', 20);
