<?php
/**
 * Template Part: Newsletter Signup Section — with optional Lead Magnet.
 *
 * When the lead magnet is enabled in Customizer, shows the resource name
 * as an incentive before subscribing, and reveals a download link after
 * the form is submitted (via JS — the form still posts to the email
 * provider as before, but the download appears instantly client-side).
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

// Lead magnet settings.
$lm_enabled  = get_theme_mod( 'ifende_newsletter_lead_magnet_enabled', false );
$lm_title    = get_theme_mod( 'ifende_newsletter_lead_magnet_title', 'Free Project Planning Checklist' );
$lm_url      = get_theme_mod( 'ifende_newsletter_lead_magnet_url', '' );
$lm_desc     = get_theme_mod( 'ifende_newsletter_lead_magnet_desc', 'Subscribe to get instant access to our free resource.' );
?>
<section class="if-section newsletter-section" id="newsletter">
  <div class="newsletter-wrap reveal">
    <div class="newsletter-content">
      <div class="section-label"><?php esc_html_e( 'Newsletter', 'ifende' ); ?></div>
      <h2 class="section-title"><?php echo esc_html( $heading ); ?></h2>
      <?php if ( $lm_enabled && $lm_title ) : ?>
        <p class="section-sub"><?php echo esc_html( $lm_desc ); ?></p>
        <p class="newsletter-lead-magnet-preview" style="margin-top:12px;display:flex;align-items:center;gap:8px;color:var(--green);font-size:0.82rem;font-weight:600;">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          <?php echo esc_html( $lm_title ); ?>
        </p>
      <?php else : ?>
        <p class="section-sub"><?php echo esc_html( $description ); ?></p>
      <?php endif; ?>
    </div>
    <div class="newsletter-form-wrap">
      <form class="newsletter-form" id="ifendeNewsletterForm" action="<?php echo esc_url( $action_url ); ?>" method="POST" target="_blank" rel="noopener">
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
      <?php if ( $lm_enabled && $lm_url ) : ?>
        <div class="newsletter-lead-magnet-download" id="newsletterLeadMagnet" style="display:none;margin-top:20px;padding:20px;background:rgba(33,161,78,0.08);border:1px solid var(--green);border-radius:4px;text-align:center;">
          <p style="font-size:0.82rem;color:var(--white);margin-bottom:12px;font-weight:600;"><?php esc_html_e( 'Thank you! Your download is ready:', 'ifende' ); ?></p>
          <a href="<?php echo esc_url( $lm_url ); ?>" class="btn-primary" download style="display:inline-flex;">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            <?php echo esc_html( $lm_title ); ?>
          </a>
        </div>
        <script>
        (function(){
          var form = document.getElementById('ifendeNewsletterForm');
          var download = document.getElementById('newsletterLeadMagnet');
          if (!form || !download) return;
          form.addEventListener('submit', function() {
            // Show download immediately (form still submits to provider in new tab)
            setTimeout(function() {
              form.style.display = 'none';
              download.style.display = 'block';
            }, 500);
          });
        })();
        </script>
      <?php endif; ?>
    </div>
  </div>
</section>
