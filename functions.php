<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

/**
 * Enqueue compiled Vite assets (CSS and JS)
 */
function generatepress_child_enqueue_assets() {
    $theme_version = wp_get_theme()->get('Version');

    // Get the manifest file to find the compiled asset names with hashes
    $manifest_path = get_stylesheet_directory() . '/dist/.vite/manifest.json';

    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        if (!$manifest) {
            error_log('Invalid manifest JSON in ' . $manifest_path);
            return;
        }

        // Enqueue the main JavaScript file
        if (isset($manifest['src/js/main.js'])) {
            $js_file = $manifest['src/js/main.js']['file'];
            wp_enqueue_script(
                'generatepress-child-main',
                get_stylesheet_directory_uri() . '/dist/' . $js_file,
                array(), // dependencies
                null, // version is in filename hash
                true // load in footer
            );
        }

        // Enqueue the main CSS file
        if (isset($manifest['src/css/main.css'])) {
            $css_file = $manifest['src/css/main.css']['file'];
            wp_enqueue_style(
                'generatepress-child-main',
                get_stylesheet_directory_uri() . '/dist/' . $css_file,
                array(), // dependencies
                null // version is in filename hash
            );
        }
    } else {
        // Fallback for development - load non-hashed files if manifest doesn't exist
        // Note: Run 'npm run build' to generate the manifest
        error_log('Vite manifest not found. Please run "npm run build" in the theme directory.');
    }
}
add_action('wp_enqueue_scripts', 'generatepress_child_enqueue_assets', 20);
