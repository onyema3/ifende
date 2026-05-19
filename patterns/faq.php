<?php
/**
 * Title: FAQ Section
 * Slug: ifende/faq
 * Categories: ifende
 * Description: Accordion-style frequently-asked-questions list. The live homepage section adds Schema.org FAQPage markup automatically.
 * Keywords: faq, questions, help, accordion
 * Inserter: yes
 *
 * Mirrors template-parts/section-faq.php (without the JSON-LD schema script;
 * that's only emitted by the live template).
 */
?>
<!-- wp:html -->
<section class="if-section dark" id="faq">
  <div class="section-label">FAQ</div>
  <h2 class="section-title">Frequently Asked<br><em>Questions</em></h2>
  <p class="section-sub">Quick answers to common questions about working together.</p>
  <div class="faq-list">
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        What services do you offer?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>I specialise in web development, project management, consulting, branding, and game development. Each engagement is tailored to your specific needs.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        How long does a typical project take?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>Project timelines vary based on scope. A standard website takes 2&ndash;4 weeks; larger builds run 6&ndash;8 weeks. You'll get a detailed timeline at the consultation.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        Do you work with international clients?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>Yes &mdash; I work with clients globally. All communication and project management happens remotely using modern collaboration tools.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        What is your pricing structure?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>Pricing depends on scope, complexity, and timeline. I offer fixed-price projects and hourly consulting. Get in touch for an accurate quote.</p>
      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
