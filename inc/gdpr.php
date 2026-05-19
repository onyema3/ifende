<?php
/**
 * GDPR Cookie Consent Banner
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Output cookie consent banner in the footer.
 */
function ifende_cookie_banner() {
  $enabled = get_theme_mod( 'ifende_cookie_notice_enabled', true );
  if ( ! $enabled ) {
    return;
  }

  $message    = get_theme_mod( 'ifende_cookie_message', 'This site uses cookies and third-party services (like Google Fonts) to enhance your experience. By continuing to browse, you consent to their use.' );
  $policy_url = get_theme_mod( 'ifende_cookie_policy_url', '' );
  ?>
  <div class="cookie-banner" id="cookieBanner" role="alert" aria-live="polite" style="display:none;">
    <div class="cookie-banner-inner">
      <p class="cookie-banner-text">
        <?php echo esc_html( $message ); ?>
        <?php if ( $policy_url ) : ?>
          <a href="<?php echo esc_url( $policy_url ); ?>" class="cookie-policy-link"><?php esc_html_e( 'Privacy Policy', 'ifende' ); ?></a>
        <?php endif; ?>
      </p>
      <div class="cookie-banner-actions">
        <button class="cookie-btn cookie-btn--accept" id="cookieAccept"><?php esc_html_e( 'Accept', 'ifende' ); ?></button>
        <button class="cookie-btn cookie-btn--dismiss" id="cookieDismiss"><?php esc_html_e( 'Dismiss', 'ifende' ); ?></button>
      </div>
    </div>
  </div>
  <?php
}
add_action( 'wp_footer', 'ifende_cookie_banner', 5 );
