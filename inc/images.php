<?php
/**
 * WebP Image Support — Helper functions for responsive <picture> elements.
 *
 * Provides ifende_picture() to output <picture> tags with WebP source
 * and a fallback <img> for older browsers.
 *
 * @package Ifende
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output a <picture> element with WebP source and fallback.
 *
 * Accepts an attachment ID or a direct image URL. When given an attachment ID,
 * it checks if a .webp version exists in the uploads directory. If the WebP
 * file is not found, it gracefully falls back to a standard <img> tag.
 *
 * @param array $args {
 *     Arguments for the picture element.
 *
 *     @type int    $attachment_id  WordPress attachment ID (preferred).
 *     @type string $url            Direct image URL (used if no attachment_id).
 *     @type string $size           WordPress image size. Default 'large'.
 *     @type string $alt            Alt text. Default: attachment alt or empty.
 *     @type string $class          CSS class(es) for the <img> tag. Default ''.
 *     @type string $loading        Loading attribute. Default 'lazy'.
 *     @type array  $attrs          Additional HTML attributes for <img>.
 * }
 * @param bool  $echo Whether to echo or return the markup. Default true.
 * @return string|void HTML markup if $echo is false.
 */
function ifende_picture( $args = [], $echo = true ) {
	$defaults = [
		'attachment_id' => 0,
		'url'           => '',
		'size'          => 'large',
		'alt'           => '',
		'class'         => '',
		'loading'       => 'lazy',
		'attrs'         => [],
	];

	$args = wp_parse_args( $args, $defaults );

	// Determine the image URL and WebP URL.
	$img_url  = '';
	$webp_url = '';

	if ( $args['attachment_id'] ) {
		$img_src = wp_get_attachment_image_src( $args['attachment_id'], $args['size'] );
		if ( $img_src ) {
			$img_url = $img_src[0];
		}

		// Try to find a WebP version.
		$webp_url = ifende_get_webp_url( $args['attachment_id'], $args['size'] );

		// Get alt text from attachment if not provided.
		if ( empty( $args['alt'] ) ) {
			$args['alt'] = get_post_meta( $args['attachment_id'], '_wp_attachment_image_alt', true );
		}
	} elseif ( $args['url'] ) {
		$img_url = $args['url'];

		// Attempt WebP URL by replacing extension.
		$webp_url = ifende_url_to_webp( $args['url'] );
	}

	// If we have no image URL at all, bail.
	if ( empty( $img_url ) ) {
		return '';
	}

	// Build <img> attributes.
	$img_attrs = [
		'src'     => esc_url( $img_url ),
		'alt'     => esc_attr( $args['alt'] ),
		'loading' => esc_attr( $args['loading'] ),
	];

	if ( ! empty( $args['class'] ) ) {
		$img_attrs['class'] = esc_attr( $args['class'] );
	}

	// Merge additional attributes.
	foreach ( $args['attrs'] as $key => $value ) {
		$img_attrs[ sanitize_key( $key ) ] = esc_attr( $value );
	}

	// Build attribute string.
	$attr_string = '';
	foreach ( $img_attrs as $key => $value ) {
		$attr_string .= sprintf( ' %s="%s"', $key, $value );
	}

	// Build the markup.
	if ( ! empty( $webp_url ) ) {
		$html  = '<picture>' . "\n";
		$html .= sprintf( '  <source srcset="%s" type="image/webp">', esc_url( $webp_url ) ) . "\n";
		$html .= sprintf( '  <img%s>', $attr_string ) . "\n";
		$html .= '</picture>';
	} else {
		$html = sprintf( '<img%s>', $attr_string );
	}

	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- All values escaped above.
		return;
	}

	return $html;
}

/**
 * Get the WebP URL for a given attachment.
 *
 * Checks if a .webp file exists in the same directory as the original.
 *
 * @param int    $attachment_id Attachment post ID.
 * @param string $size          Image size.
 * @return string WebP URL or empty string if not found.
 */
function ifende_get_webp_url( $attachment_id, $size = 'large' ) {
	$img_src = wp_get_attachment_image_src( $attachment_id, $size );
	if ( ! $img_src ) {
		return '';
	}

	$img_url = $img_src[0];

	return ifende_url_to_webp( $img_url );
}

/**
 * Convert an image URL to its WebP counterpart and verify it exists.
 *
 * @param string $url Original image URL.
 * @return string WebP URL or empty string if file doesn't exist.
 */
function ifende_url_to_webp( $url ) {
	if ( empty( $url ) ) {
		return '';
	}

	// Replace extension with .webp.
	$webp_url = preg_replace( '/\.(jpe?g|png|gif)$/i', '.webp', $url );

	// If the URL didn't change (no valid extension found), bail.
	if ( $webp_url === $url ) {
		return '';
	}

	// Convert URL to file path and check if the file exists.
	$upload_dir = wp_get_upload_dir();
	$base_url   = $upload_dir['baseurl'];
	$base_dir   = $upload_dir['basedir'];

	// Only check local uploads.
	if ( strpos( $webp_url, $base_url ) === 0 ) {
		$relative_path = str_replace( $base_url, '', $webp_url );
		$file_path     = $base_dir . $relative_path;

		if ( file_exists( $file_path ) ) {
			return $webp_url;
		}
	}

	return '';
}

/**
 * Add WebP to the list of allowed upload MIME types.
 *
 * @param array $mimes Existing MIME types.
 * @return array Modified MIME types.
 */
function ifende_allow_webp_upload( $mimes ) {
	$mimes['webp'] = 'image/webp';
	return $mimes;
}
add_filter( 'mime_types', 'ifende_allow_webp_upload' );
