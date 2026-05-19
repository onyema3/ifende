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
 * Whether the visitor has consented to tracking cookies.
 *
 * Returns true if:
 *   - The cookie banner is disabled in the Customizer (the site owner has
 *     opted out of the consent UI — assume they handle compliance another
 *     way, or the site is not in a jurisdiction that requires it), OR
 *   - The visitor explicitly clicked Accept (a cookie 'ifende-consent' with
 *     value 'accepted' is set; see the closeCookieBanner() handler in
 *     assets/js/main.js).
 *
 * Returns false if the banner is enabled and the cookie is missing or set
 * to anything else (e.g. 'dismissed'). Callers that load tracking scripts
 * (analytics, pixels, visitor counts) MUST gate on this before rendering.
 *
 * @return bool
 */
function ifende_consent_given() {
  // If the site owner disabled the banner, gating off would prevent
  // analytics from ever firing — fall back to "consent assumed".
  if ( ! get_theme_mod( 'ifende_cookie_notice_enabled', true ) ) {
    return true;
  }

  $cookie = isset( $_COOKIE['ifende-consent'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['ifende-consent'] ) ) : '';
  return 'accepted' === $cookie;
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
