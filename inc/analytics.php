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
 *
 * Gated behind ifende_consent_given(): if the GDPR banner is enabled and
 * the visitor hasn't clicked Accept, no GA script is rendered at all (no
 * IP leak, no client_id, nothing). Once the visitor accepts, a cookie is
 * set and GA renders on the *next* navigation.
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

  // GDPR: don't load GA until the visitor has consented.
  if ( function_exists( 'ifende_consent_given' ) && ! ifende_consent_given() ) {
    return;
  }
  ?>
  <?php // phpcs:disable WordPress.WP.EnqueuedResources.NonEnqueuedScript -- GA4 install snippet must be inlined per Google's instructions. ?>
  <script async src="https://www.googletagmanager.com/gtag/js?id=<?php echo esc_attr( $ga_id ); ?>"></script>
  <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', '<?php echo esc_js( $ga_id ); ?>');
  </script>
  <?php // phpcs:enable WordPress.WP.EnqueuedResources.NonEnqueuedScript ?>
  <?php
}
add_action( 'wp_head', 'ifende_google_analytics', 1 );

/**
 * Output custom head scripts.
 *
 * Same consent gating as GA — these scripts are typically tracking pixels
 * (Meta, Hotjar, etc.) and need the same legal protection.
 */
function ifende_custom_head_scripts() {
  $scripts = get_theme_mod( 'ifende_head_scripts', '' );
  if ( empty( $scripts ) ) {
    return;
  }

  // GDPR: gate custom head scripts on consent for the same reason as GA.
  if ( function_exists( 'ifende_consent_given' ) && ! ifende_consent_given() ) {
    return;
  }

  // Already sanitized via Customizer sanitize callback ifende_sanitize_scripts(),
  // which restricts to <script> tags for non-admins via wp_kses. Admins can save
  // arbitrary HTML, but only users with the unfiltered_html capability — the same
  // permission that gates editor HTML overall.
  echo $scripts; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', 'ifende_custom_head_scripts', 99 );
