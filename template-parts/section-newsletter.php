<?php
/**
 * Template Part: Newsletter Signup Section
 *
 * @package Ifende
 */

$action_url  = get_theme_mod( 'ifende_newsletter_action_url', '' );

// Only show if a form action URL is configured.
if ( empty( $action_url ) ) {
  return;
}

$heading     = get_theme_mod( 'ifende_newsletter_heading', 'Stay in the Loop' );
$description = get_theme_mod( 'ifende_newsletter_desc', 'Get occasional updates on new projects, insights, and opportunities. No spam, unsubscribe anytime.' );
$email_field = get_theme_mod( 'ifende_newsletter_email_field', 'EMAIL' );
?>
<section class="if-section newsletter-section" id="newsletter">
  <div class="newsletter-wrap reveal">
    <div class="newsletter-content">
      <div class="section-label"><?php esc_html_e( 'Newsletter', 'ifende' ); ?></div>
      <h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
      <p class="section-sub"><?php echo esc_html( $description ); ?></p>
    </div>
    <form class="newsletter-form" action="<?php echo esc_url( $action_url ); ?>" method="POST" target="_blank" rel="noopener">
      <div class="newsletter-input-wrap">
        <label for="newsletter-email" class="screen-reader-text"><?php esc_html_e( 'Email address', 'ifende' ); ?></label>
        <input
          type="email"
          id="newsletter-email"
          name="<?php echo esc_attr( $email_field ); ?>"
          placeholder="<?php esc_attr_e( 'Enter your email', 'ifende' ); ?>"
          required
          autocomplete="email"
        >
        <button type="submit" class="newsletter-btn"><?php esc_html_e( 'Subscribe', 'ifende' ); ?> &rarr;</button>
      </div>
      <p class="newsletter-disclaimer"><?php esc_html_e( 'No spam. Unsubscribe anytime.', 'ifende' ); ?></p>
    </form>
  </div>
</section>
