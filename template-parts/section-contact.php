<?php
/**
 * Template Part: Contact Section
 *
 * @package Ifende
 */

$location    = ifende_opt( 'about_location', 'Global — Based in Nigeria' );
$instagram   = ifende_opt( 'instagram_url', 'https://instagram.com/onyema.ifende' );
$twitter_url = ifende_opt( 'twitter_url', 'https://twitter.com/ifende' );
?>
<section class="if-section" id="contact">
  <div class="section-label"><?php esc_html_e( 'Get In Touch', 'ifende' ); ?></div>
  <div class="contact-grid">
    <div>
      <h2 class="section-title reveal"><?php echo wp_kses_post( __( "Let's Build<br>Something <em>Great</em>", 'ifende' ) ); ?></h2>
      <p class="section-sub reveal reveal-d1" style="margin-top:24px;margin-bottom:48px;"><?php esc_html_e( "Have a project in mind? Looking for a consultant, developer, or creative partner? I'd love to hear from you.", 'ifende' ); ?></p>
      <div class="reveal reveal-d2">
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">📍</div><div><div class="contact-label"><?php esc_html_e( 'Location', 'ifende' ); ?></div><div class="contact-val"><?php echo esc_html( $location ); ?></div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">💼</div><div><div class="contact-label"><?php esc_html_e( 'Availability', 'ifende' ); ?></div><div class="contact-val" style="color:var(--green);"><?php esc_html_e( 'Open for Freelance & Consulting', 'ifende' ); ?></div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">🌐</div><div><div class="contact-label"><?php esc_html_e( 'Website', 'ifende' ); ?></div><div class="contact-val"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:var(--white);text-decoration:none;"><?php echo esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ); ?></a></div></div></div>
      </div>
      <div style="margin-top:40px;">
        <div class="contact-label" style="margin-bottom:14px;"><?php esc_html_e( 'Follow Me', 'ifende' ); ?></div>
        <div class="socials">
          <?php if ( $twitter_url ) : ?><a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e( 'Twitter / X', 'ifende' ); ?>">𝕏</a><?php endif; ?>
          <?php if ( $instagram ) : ?><a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e( 'Instagram', 'ifende' ); ?>">📷</a><?php endif; ?>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e( 'Website', 'ifende' ); ?>">🌐</a>
        </div>
      </div>
    </div>
    <div class="reveal reveal-d2">
      <form class="contact-form" id="contactForm" aria-label="<?php esc_attr_e( 'Contact form', 'ifende' ); ?>">
        <div class="form-row">
          <div class="form-group"><label for="fname"><?php esc_html_e( 'First Name', 'ifende' ); ?></label><input type="text" id="fname" name="fname" placeholder="<?php esc_attr_e( 'Amaka', 'ifende' ); ?>" autocomplete="given-name" required></div>
          <div class="form-group"><label for="lname"><?php esc_html_e( 'Last Name', 'ifende' ); ?></label><input type="text" id="lname" name="lname" placeholder="<?php esc_attr_e( 'Okafor', 'ifende' ); ?>" autocomplete="family-name" required></div>
        </div>
        <div class="form-group"><label for="femail"><?php esc_html_e( 'Email Address', 'ifende' ); ?></label><input type="email" id="femail" name="email" placeholder="<?php esc_attr_e( 'you@example.com', 'ifende' ); ?>" autocomplete="email" required></div>
        <div class="form-group"><label for="fsubject"><?php esc_html_e( 'Subject', 'ifende' ); ?></label><input type="text" id="fsubject" name="subject" placeholder="<?php esc_attr_e( 'Web development project...', 'ifende' ); ?>"></div>
        <div class="form-group"><label for="fmessage"><?php esc_html_e( 'Your Message', 'ifende' ); ?></label><textarea id="fmessage" name="message" placeholder="<?php esc_attr_e( 'Tell me about your project...', 'ifende' ); ?>" required></textarea></div>
        <button type="submit" class="btn-submit" id="submitBtn" aria-busy="false"><?php esc_html_e( 'Send Message', 'ifende' ); ?> &rarr;</button>
        <div id="formMsg" role="status" aria-live="polite" style="display:none;font-family:'DM Mono',monospace;font-size:0.72rem;letter-spacing:1px;color:var(--green);margin-top:8px;"></div>
      </form>
    </div>
  </div>
</section>
