<?php
/**
 * Title: Contact Section
 * Slug: ifende/contact
 * Categories: ifende
 * Description: Two-column contact section with location/availability/website, social links, and a name/email/subject/message form.
 * Keywords: contact, form, get in touch, email
 * Inserter: yes
 *
 * Mirrors template-parts/section-contact.php. The form has unique IDs
 * (suffixed with "-pattern") so it can coexist with the live section's
 * form on the same page if both are inserted. Theme JS hooks the contact
 * form by `#contactForm` &mdash; only one such ID may exist per page;
 * rename or remove the duplicate as needed.
 */
?>
<!-- wp:html -->
<section class="if-section" id="contact">
  <div class="section-label">Get In Touch</div>
  <div class="contact-grid">
    <div>
      <h2 class="section-title">Let's Build<br>Something <em>Great</em></h2>
      <p class="section-sub" style="margin-top:24px;margin-bottom:48px;">Have a project in mind? Looking for a consultant, developer, or creative partner? I'd love to hear from you.</p>
      <div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">&#128205;</div><div><div class="contact-label">Location</div><div class="contact-val">Global &mdash; Based Anywhere</div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">&#128188;</div><div><div class="contact-label">Availability</div><div class="contact-val" style="color:var(--green);">Open for Freelance &amp; Consulting</div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">&#127760;</div><div><div class="contact-label">Website</div><div class="contact-val"><a href="#" style="color:var(--white);text-decoration:none;">your-website.com</a></div></div></div>
      </div>
      <div style="margin-top:40px;">
        <div class="contact-label" style="margin-bottom:14px;">Follow Me</div>
        <div class="socials">
          <a href="#" target="_blank" rel="noopener" class="social-link" aria-label="Twitter / X">&#x1D54F;</a>
          <a href="#" target="_blank" rel="noopener" class="social-link" aria-label="Instagram">&#128247;</a>
          <a href="#" target="_blank" rel="noopener" class="social-link" aria-label="Website">&#127760;</a>
        </div>
      </div>
    </div>
    <div>
      <form class="contact-form" aria-label="Contact form">
        <div class="form-row">
          <div class="form-group"><label for="contact-fname-pattern">First Name</label><input type="text" id="contact-fname-pattern" name="fname" placeholder="Jane" autocomplete="given-name" required></div>
          <div class="form-group"><label for="contact-lname-pattern">Last Name</label><input type="text" id="contact-lname-pattern" name="lname" placeholder="Doe" autocomplete="family-name" required></div>
        </div>
        <div class="form-group"><label for="contact-email-pattern">Email Address</label><input type="email" id="contact-email-pattern" name="email" placeholder="you@example.com" autocomplete="email" required></div>
        <div class="form-group"><label for="contact-subject-pattern">Subject</label><input type="text" id="contact-subject-pattern" name="subject" placeholder="Web development project..."></div>
        <div class="form-group"><label for="contact-message-pattern">Your Message</label><textarea id="contact-message-pattern" name="message" placeholder="Tell me about your project..." required></textarea></div>
        <button type="submit" class="btn-submit">Send Message &rarr;</button>
      </form>
    </div>
  </div>
</section>
<!-- /wp:html -->
