<?php
/**
 * Title: Testimonials Section
 * Slug: ifende/testimonials
 * Categories: ifende
 * Description: Auto-fitting grid of testimonial quote cards with avatar initial, name, and role.
 * Keywords: testimonials, quotes, reviews, social proof
 * Inserter: yes
 *
 * Mirrors template-parts/section-testimonials.php.
 */
?>
<!-- wp:html -->
<section class="if-section" id="testimonials">
  <div class="section-label">Testimonials</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title">What People<br><em>Say</em></h2>
    <p class="section-sub">Hear from clients and partners who have experienced working with me firsthand.</p>
  </div>
  <div class="testimonials-grid">
    <blockquote class="testimonial-card">
      <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
      <p class="testimonial-text">Working with this team was a game-changer. They delivered our web platform on time and exceeded our expectations in every way.</p>
      <footer class="testimonial-author">
        <div class="testimonial-avatar" aria-hidden="true">C</div>
        <div>
          <cite class="testimonial-name">Client Name</cite>
          <span class="testimonial-role">CEO, Example Co.</span>
        </div>
      </footer>
    </blockquote>
    <blockquote class="testimonial-card">
      <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
      <p class="testimonial-text">Top-notch project management. Kept our team aligned and the project moving forward effortlessly through every milestone.</p>
      <footer class="testimonial-author">
        <div class="testimonial-avatar" aria-hidden="true">A</div>
        <div>
          <cite class="testimonial-name">Another Name</cite>
          <span class="testimonial-role">Director, Sample Studio</span>
        </div>
      </footer>
    </blockquote>
    <blockquote class="testimonial-card">
      <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
      <p class="testimonial-text">From branding to web development, a rare combination of creativity and technical excellence. Highly recommended.</p>
      <footer class="testimonial-author">
        <div class="testimonial-avatar" aria-hidden="true">B</div>
        <div>
          <cite class="testimonial-name">Brand Lead</cite>
          <span class="testimonial-role">Founder, Demo Brand</span>
        </div>
      </footer>
    </blockquote>
  </div>
</section>
<!-- /wp:html -->
