<?php
/**
 * SVG Upload Support
 *
 * @package    GeneratePress_Child
 * @subpackage Functions
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use enshrined\svgSanitize\Sanitizer;

/**
 * Enable SVG uploads
 *
 * Allows SVG files to be uploaded to the media library.
 * Note: SVG files are sanitized on upload for security.
 *
 * @param array $mimes Allowed MIME types.
 * @return array Modified MIME types array.
 */
function generatepress_child_allow_svg_uploads( $mimes ) {
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'generatepress_child_allow_svg_uploads' );

/**
 * Check SVG file type
 *
 * WordPress file type checking for SVG uploads.
 *
 * @param array  $wp_check_filetype_and_ext File data array.
 * @param string $file Full path to the file.
 * @param string $filename The name of the file.
 * @param array  $mimes Array of allowed MIME types.
 * @return array Modified file data array.
 */
function generatepress_child_check_svg_filetype( $wp_check_filetype_and_ext, $file, $filename, $mimes ) {
	if ( ! $wp_check_filetype_and_ext['type'] ) {
		$check_filetype  = wp_check_filetype( $filename, $mimes );
		$ext             = $check_filetype['ext'];
		$type            = $check_filetype['type'];
		$proper_filename = $filename;

		if ( $type && 0 === strpos( $type, 'image/' ) && 'svg' === $ext ) {
			$wp_check_filetype_and_ext['ext']             = $ext;
			$wp_check_filetype_and_ext['type']            = $type;
			$wp_check_filetype_and_ext['proper_filename'] = $proper_filename;
		}
	}

	return $wp_check_filetype_and_ext;
}
add_filter( 'wp_check_filetype_and_ext', 'generatepress_child_check_svg_filetype', 10, 4 );

/**
 * Sanitize SVG uploads
 *
 * Uses the enshrined/svg-sanitize library for robust SVG sanitization.
 * Restricted to users with unfiltered_html capability by default (filterable).
 *
 * @param array $file The uploaded file array.
 * @return array The file array.
 */
function generatepress_child_sanitize_svg_upload( $file ) {
	// Only process SVG files
	if ( ! isset( $file['type'] ) || 0 !== strpos( $file['type'], 'image/svg' ) ) {
		return $file;
	}

	// Restrict SVG uploads to users with specific capability (default: unfiltered_html)
	// Filter allows customization: add_filter('generatepress_child_svg_upload_capability', function() { return 'edit_posts'; });
	$required_capability = apply_filters( 'generatepress_child_svg_upload_capability', 'unfiltered_html' );

	if ( ! current_user_can( $required_capability ) ) {
		$file['error'] = __( 'You do not have permission to upload SVG files.', 'generatepress_child' );
		@unlink( $file['tmp_name'] );
		return $file;
	}

	// Verify sanitizer library is available
	if ( ! class_exists( 'enshrined\svgSanitize\Sanitizer' ) ) {
		$file['error'] = __( 'SVG sanitization library is not available. Please contact the site administrator.', 'generatepress_child' );
		@unlink( $file['tmp_name'] );
		return $file;
	}

	// Read file contents
	$svg_content = file_get_contents( $file['tmp_name'] );

	if ( false === $svg_content ) {
		$file['error'] = __( 'Unable to read SVG file.', 'generatepress_child' );
		// Delete temp file on read failure to prevent processing unsanitized content
		@unlink( $file['tmp_name'] );
		return $file;
	}

	// Sanitize using enshrined/svg-sanitize library
	$sanitizer = new Sanitizer();
	$sanitized_svg = $sanitizer->sanitize( $svg_content );

	if ( false === $sanitized_svg ) {
		$file['error'] = __( 'Invalid or potentially malicious SVG file.', 'generatepress_child' );
		// Delete temp file on sanitization failure to prevent processing malicious content
		@unlink( $file['tmp_name'] );
		return $file;
	}

	// Write sanitized content back
	$bytes_written = file_put_contents( $file['tmp_name'], $sanitized_svg );

	if ( false === $bytes_written || 0 === $bytes_written ) {
		$file['error'] = __( 'Unable to save sanitized SVG file.', 'generatepress_child' );
		// Delete temp file on write failure to prevent processing potentially corrupt file
		@unlink( $file['tmp_name'] );
		return $file;
	}

	return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'generatepress_child_sanitize_svg_upload' );

/**
 * Display SVG thumbnails in media library
 *
 * @param array  $response   The response data.
 * @param object $attachment The attachment object.
 * @return array Modified response data.
 */
function generatepress_child_svg_media_thumbnails( $response, $attachment ) {
	if ( isset( $response['mime'] ) && 'image/svg+xml' === $response['mime'] ) {
		$response['image'] = array(
			'src' => $response['url'],
		);
	}

	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', 'generatepress_child_svg_media_thumbnails', 10, 2 );
