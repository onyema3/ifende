<?php
/**
 * Enqueue scripts and styles.
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Add preconnect hints for Google Fonts (loaded before stylesheets).
 */
function ifende_resource_hints( $urls, $relation_type ) {
  if ( 'preconnect' === $relation_type ) {
    $urls[] = [
      'href'        => 'https://fonts.googleapis.com',
      'crossorigin' => 'anonymous',
    ];
    $urls[] = [
      'href'        => 'https://fonts.gstatic.com',
      'crossorigin' => 'anonymous',
    ];
  }
  return $urls;
}
add_filter( 'wp_resource_hints', 'ifende_resource_hints', 10, 2 );

/**
 * Enqueue front-end styles and scripts.
 */
function ifende_enqueue() {
  // Google Fonts — loaded as a proper stylesheet for caching & non-render-blocking.
  wp_enqueue_style(
    'ifende-google-fonts',
    'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,600&family=DM+Mono:wght@300;400;500&family=Syne:wght@400;600;700;800&display=swap',
    [],
    null
  );

  // Main stylesheet.
  wp_enqueue_style( 'ifende-main', IFENDE_URI . '/assets/css/main.css', [ 'ifende-google-fonts' ], IFENDE_VERSION );

  // Main script.
  wp_enqueue_script( 'ifende-main', IFENDE_URI . '/assets/js/main.js', [], IFENDE_VERSION, true );

  // Localize script data — email NOT exposed directly (security).
  // The JS mailto fallback will use the AJAX endpoint to retrieve it only when needed.
  wp_localize_script( 'ifende-main', 'ifendeData', [
    'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
    'nonce'     => wp_create_nonce( 'ifende_nonce' ),
    'formspree' => get_theme_mod( 'ifende_formspree_id', '' ),
    'web3forms' => get_theme_mod( 'ifende_web3forms_key', '' ),
  ] );
}
add_action( 'wp_enqueue_scripts', 'ifende_enqueue' );

/**
 * Add font-display: swap to Google Fonts (performance optimization).
 *
 * @param string $tag    The link tag.
 * @param string $handle The stylesheet handle.
 * @return string
 */
function ifende_font_display_swap( $tag, $handle ) {
  if ( 'ifende-google-fonts' === $handle ) {
    $tag = str_replace( "media='all'", "media='all' crossorigin='anonymous'", $tag );
  }
  return $tag;
}
add_filter( 'style_loader_tag', 'ifende_font_display_swap', 10, 2 );
