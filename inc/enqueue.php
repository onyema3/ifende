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
  // Google Fonts — loaded with font-display=optional for performance.
  // This prevents layout shift by using local fallback until fonts are cached.
  wp_enqueue_style(
    'ifende-google-fonts',
    'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;0,700;1,300;1,600&family=DM+Mono:wght@300;400;500&family=Syne:wght@400;600;700;800&display=optional',
    [],
    null
  );

  // Use minified assets in production, unminified in debug mode.
  $suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';

  // No-flash theme detection — must run synchronously in <head> BEFORE
  // first paint, so the page is never rendered in the wrong color scheme.
  // Loaded as a static asset (instead of an inline <script>) so it works
  // under a strict Content-Security-Policy without needing a nonce.
  wp_enqueue_script( 'ifende-no-flash', IFENDE_URI . '/assets/js/no-flash.js', [], IFENDE_VERSION, false );

  // Main stylesheet.
  wp_enqueue_style( 'ifende-main', IFENDE_URI . '/assets/css/main' . $suffix . '.css', [ 'ifende-google-fonts' ], IFENDE_VERSION );

  // Main script.
  wp_enqueue_script( 'ifende-main', IFENDE_URI . '/assets/js/main' . $suffix . '.js', [], IFENDE_VERSION, true );

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
 * Make Google Fonts non-render-blocking via media swap technique.
 * Also adds crossorigin for proper font loading.
 *
 * @param string $tag    The link tag.
 * @param string $handle The stylesheet handle.
 * @return string
 */
function ifende_font_display_swap( $tag, $handle ) {
  if ( 'ifende-google-fonts' === $handle ) {
    // Load as print, then swap to all on load — eliminates render blocking.
    $tag = str_replace( "media='all'", "media='print' onload=\"this.media='all'\" crossorigin='anonymous'", $tag );
    // Add noscript fallback.
    $tag .= '<noscript>' . str_replace( " onload=\"this.media='all'\"", '', str_replace( "media='print'", "media='all'", $tag ) ) . '</noscript>';
  }
  return $tag;
}
add_filter( 'style_loader_tag', 'ifende_font_display_swap', 10, 2 );
