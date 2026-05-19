<?php
/**
 * Analytics & Tracking Scripts
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Output Google Analytics (GA4) script in <head>.
 */
function ifende_google_analytics() {
  $ga_id = get_theme_mod( 'ifende_ga_measurement_id', '' );
  if ( empty( $ga_id ) ) {
    return;
  }

  // Don't track logged-in admins.
  if ( current_user_can( 'manage_options' ) ) {
    return;
  }
  ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo esc_js( $ga_id ); ?>');
  </script>
  <?php
}
add_action( 'wp_head', 'ifende_google_analytics', 1 );

/**
 * Output custom head scripts.
 */
function ifende_custom_head_scripts() {
  $scripts = get_theme_mod( 'ifende_head_scripts', '' );
  if ( ! empty( $scripts ) ) {
    echo $scripts; // Already sanitized via Customizer sanitize callback.
  }
}
add_action( 'wp_head', 'ifende_custom_head_scripts', 99 );
