<?php
/**
 * Title: Newsletter Signup
 * Slug: ifende/newsletter
 * Categories: ifende
 * Description: Newsletter signup section with heading, description, and inline email form. Set the form action to your Mailchimp or ConvertKit URL before publishing.
 * Keywords: newsletter, email, signup, subscribe
 * Inserter: yes
 *
 * Mirrors template-parts/section-newsletter.php. The form action is left
 * as '#' so editors must wire it to their own Mailchimp/ConvertKit
 * endpoint before publishing.
 */
?>
<!-- wp:html -->
<section class="if-section newsletter-section" id="newsletter">
  <div class="newsletter-wrap">
    <div class="newsletter-content">
      <div class="section-label">Newsletter</div>
      <h2 class="section-title">Stay in the <em>Loop</em></h2>
      <p class="section-sub">Get occasional updates on new projects, insights, and opportunities. No spam, unsubscribe anytime.</p>
    </div>
    <form class="newsletter-form" action="#" method="POST" target="_blank" rel="noopener">
      <div class="newsletter-input-wrap">
        <label for="newsletter-email-pattern" class="screen-reader-text">Email address</label>
        <input type="email" id="newsletter-email-pattern" name="EMAIL" placeholder="Enter your email" required autocomplete="email">
        <button type="submit" class="newsletter-btn">Subscribe &rarr;</button>
      </div>
      <p class="newsletter-disclaimer">No spam. Unsubscribe anytime.</p>
    </form>
  </div>
</section>
<!-- /wp:html -->
