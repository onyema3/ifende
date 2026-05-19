<?php
/**
 * Template Part: FAQ Section (with Schema.org FAQPage markup)
 *
 * Pulls from ifende_faq CPT if entries exist, otherwise falls back to Customizer data.
 *
 * @package Ifende
 */

$use_cpt = function_exists( 'ifende_has_cpt_entries' ) && ifende_has_cpt_entries( 'ifende_faq' );

// Build FAQ data from either CPT or Customizer.
$faq_items = [];

if ( $use_cpt ) {
  $faq_query = new WP_Query( [
    'post_type'      => 'ifende_faq',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'post_status'    => 'publish',
  ] );
  while ( $faq_query->have_posts() ) {
    $faq_query->the_post();
    $faq_items[] = [
      'question' => get_the_title(),
      'answer'   => wp_strip_all_tags( get_the_content() ),
    ];
  }
  wp_reset_postdata();
} else {
  $faq_raw = get_theme_mod( 'ifende_faq_list', "What services do you offer?|I specialize in web development, project management, consulting, branding, and game development. Each service is tailored to your specific needs.\nHow long does a typical project take?|Project timelines vary based on scope. A standard website takes 2-4 weeks, while larger projects may take 6-8 weeks. I'll provide a detailed timeline during our initial consultation.\nDo you work with clients outside Nigeria?|Absolutely! I work with clients globally. All communication and project management is handled remotely using modern collaboration tools.\nWhat is your pricing structure?|Pricing depends on the project scope, complexity, and timeline. I offer both fixed-price projects and hourly consulting. Let's discuss your needs for an accurate quote." );
  $faqs = array_filter( array_map( 'trim', explode( "\n", $faq_raw ) ) );
  foreach ( $faqs as $faq_line ) {
    $parts = array_map( 'trim', explode( '|', $faq_line, 2 ) );
    if ( count( $parts ) < 2 ) continue;
    $faq_items[] = [
      'question' => $parts[0],
      'answer'   => $parts[1],
    ];
  }
}

if ( empty( $faq_items ) ) {
  return;
}

// Build structured data for Schema.org FAQPage.
$schema_faqs = [];
foreach ( $faq_items as $item ) {
  $schema_faqs[] = [
    '@type'          => 'Question',
    'name'           => $item['question'],
    'acceptedAnswer' => [
      '@type' => 'Answer',
      'text'  => $item['answer'],
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
    <?php foreach ( $faq_items as $item ) : ?>
      <div class="faq-item" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
        <button class="faq-question" aria-expanded="false" itemprop="name">
          <?php echo esc_html( $item['question'] ); ?>
          <span class="faq-icon" aria-hidden="true"></span>
        </button>
        <div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
          <p itemprop="text"><?php echo esc_html( $item['answer'] ); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</section>
