<?php
/**
 * GeneratePress child theme functions and definitions.
 *
 * Add your custom PHP in this file.
 * Only edit this file if you have direct access to it on your server (to fix errors if they happen).
 */

/**
 * Load Composer autoloader
 */
if ( file_exists( get_stylesheet_directory() . '/vendor/autoload.php' ) ) {
	require_once get_stylesheet_directory() . '/vendor/autoload.php';
}

/**
 * Load theme configuration (load first, before other functions)
 */
require_once get_stylesheet_directory() . '/config.php';

/**
 * Load environment detection functions
 *
 * These functions determine if we're in a development environment.
 * Loaded early as other modules depend on this.
 */
require_once get_stylesheet_directory() . '/functions/environment.php';

/**
 * Load theme setup functions
 *
 * Core theme setup and configuration.
 */
require_once get_stylesheet_directory() . '/functions/theme-setup.php';

/**
 * Load production asset functions (always loaded)
 */
require_once get_stylesheet_directory() . '/functions/prod-assets.php';

/**
 * Load development asset functions (only in debug/non-production environments)
 *
 * These functions enable Vite dev server detection and Hot Module Replacement.
 * They are excluded in production for performance and security.
 */
if (generatepress_child_is_dev_environment()) {
    require_once get_stylesheet_directory() . '/functions/dev-assets.php';
}

/**
 * Load SVG support (only if the required sanitizer library is available)
 */
if ( class_exists( 'enshrined\svgSanitize\Sanitizer' ) ) {
	require_once get_stylesheet_directory() . '/functions/svg-support.php';
} else {
	// Show admin notice if vendor directory is missing
	add_action( 'admin_notices', function() {
		if ( ! file_exists( get_stylesheet_directory() . '/vendor/autoload.php' ) ) {
			echo '<div class="notice notice-warning is-dismissible"><p>';
			echo '<strong>GeneratePress Child Theme:</strong> SVG upload support is disabled. ';
			echo 'Run <code>composer install</code> in the theme directory to enable it.';
			echo '</p></div>';
		}
	} );
}