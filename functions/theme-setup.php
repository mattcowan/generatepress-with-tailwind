<?php
/**
 * Theme Setup Functions
 *
 * Core theme setup and configuration functions.
 *
 * @package GeneratePress_Child
 * @since 1.0.0
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

// Disable theme and plugin file editors
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}

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
