<?php
/**
 * Template Part: FAQ Section (with Schema.org FAQPage markup)
 *
 * @package Ifende
 */

$faq_raw = get_theme_mod( 'ifende_faq_list', "What services do you offer?|I specialize in web development, project management, consulting, branding, and game development. Each service is tailored to your specific needs.\nHow long does a typical project take?|Project timelines vary based on scope. A standard website takes 2-4 weeks, while larger projects may take 6-8 weeks. I'll provide a detailed timeline during our initial consultation.\nDo you work with clients outside Nigeria?|Absolutely! I work with clients globally. All communication and project management is handled remotely using modern collaboration tools.\nWhat is your pricing structure?|Pricing depends on the project scope, complexity, and timeline. I offer both fixed-price projects and hourly consulting. Let's discuss your needs for an accurate quote." );

$faqs = array_filter( array_map( 'trim', explode( "\n", $faq_raw ) ) );

if ( empty( $faqs ) ) {
  return;
}

// Build structured data for Schema.org FAQPage
$schema_faqs = [];
foreach ( $faqs as $faq_line ) {
  $parts = array_map( 'trim', explode( '|', $faq_line, 2 ) );
  if ( count( $parts ) < 2 ) continue;
  $schema_faqs[] = [
    '@type'          => 'Question',
    'name'           => $parts[0],
    'acceptedAnswer' => [
      '@type' => 'Answer',
      'text'  => $parts[1],
    ],
  ];
}
?>

<?php if ( ! empty( $schema_faqs ) ) : ?>
<script type="application/ld+json">
<?php echo wp_json_encode( [
  '@context'   => 'https://schema.org',
  '@type'      => 'FAQPage',
  'mainEntity' => $schema_faqs,
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ); ?>
</script>
<?php endif; ?>

<section class="if-section dark" id="faq">
  <div class="section-label"><?php esc_html_e( 'FAQ', 'ifende' ); ?></div>
  <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'Frequently Asked<br><em>Questions</em>', 'ifende' ) ); ?></h2>
  <p class="section-sub reveal reveal-d1"><?php esc_html_e( 'Quick answers to common questions about working with me.', 'ifende' ); ?></p>

  <div class="faq-list reveal reveal-d2">
    <?php foreach ( $faqs as $faq_line ) :
      $parts = array_map( 'trim', explode( '|', $faq_line, 2 ) );
      if ( count( $parts ) < 2 ) continue;
      $question = $parts[0];
      $answer   = $parts[1];
    ?>
      <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
        <button class="faq-question" aria-expanded="false" itemprop="name">
          <?php echo esc_html( $question ); ?>
          <span class="faq-icon" aria-hidden="true"></span>
        </button>
        <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
          <p itemprop="text"><?php echo esc_html( $answer ); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
