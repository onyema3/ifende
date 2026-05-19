<?php
/**
 * Template Part: Testimonials Section
 *
 * Pulls from ifende_testimonial CPT if entries exist, otherwise falls back to Customizer data.
 *
 * @package Ifende
 */

$use_cpt = function_exists( 'ifende_has_cpt_entries' ) && ifende_has_cpt_entries( 'ifende_testimonial' );

// If no CPT and no Customizer data, bail early.
if ( ! $use_cpt ) {
  $testimonials_raw = get_theme_mod( 'ifende_testimonials_list', "Chidi Okafor|CEO, Leadetics|Working with Onyemechi was a game-changer. He delivered our web platform on time and exceeded our expectations in every way.\nAmara Nwosu|Director, Fort Solutions|His project management skills are top-notch. He kept our team aligned and the project moving forward effortlessly.\nEmeka Eze|Founder, Liberty Mall|From branding to web development, Onyemechi brings a rare combination of creativity and technical excellence." );
  $testimonials = array_filter( array_map( 'trim', explode( "\n", $testimonials_raw ) ) );
  if ( empty( $testimonials ) ) {
    return;
  }
}
?>
<section class="if-section" id="testimonials">
  <div class="section-label"><?php esc_html_e( 'Testimonials', 'ifende' ); ?></div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'What People<br><em>Say</em>', 'ifende' ) ); ?></h2>
    <p class="section-sub reveal reveal-d1"><?php esc_html_e( 'Hear from clients and partners who have experienced working with me firsthand.', 'ifende' ); ?></p>
  </div>
  <div class="testimonials-grid reveal">
    <?php if ( $use_cpt ) : ?>
      <?php
      $testimonials_query = new WP_Query( [
        'post_type'      => 'ifende_testimonial',
        'posts_per_page' => -1,
        'orderby'        => 'menu_order',
        'order'          => 'ASC',
        'post_status'    => 'publish',
      ] );
      while ( $testimonials_query->have_posts() ) : $testimonials_query->the_post();
        $t_name  = get_the_title();
        $t_role  = get_post_meta( get_the_ID(), '_ifende_testimonial_role', true );
        $t_quote = wp_strip_all_tags( get_the_content() );
      ?>
        <blockquote class="testimonial-card">
          <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
          <p class="testimonial-text"><?php echo esc_html( $t_quote ); ?></p>
          <footer class="testimonial-author">
            <div class="testimonial-avatar" aria-hidden="true"><?php echo esc_html( strtoupper( substr( $t_name, 0, 1 ) ) ); ?></div>
            <div>
              <cite class="testimonial-name"><?php echo esc_html( $t_name ); ?></cite>
              <span class="testimonial-role"><?php echo esc_html( $t_role ); ?></span>
            </div>
          </footer>
        </blockquote>
      <?php endwhile; wp_reset_postdata(); ?>
    <?php else : ?>
      <?php
      foreach ( $testimonials as $testimonial_line ) :
        $parts = array_map( 'trim', explode( '|', $testimonial_line ) );
        if ( count( $parts ) < 3 ) continue;
        $t_name  = $parts[0];
        $t_role  = $parts[1];
        $t_quote = $parts[2];
      ?>
        <blockquote class="testimonial-card">
          <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
          <p class="testimonial-text"><?php echo esc_html( $t_quote ); ?></p>
          <footer class="testimonial-author">
            <div class="testimonial-avatar" aria-hidden="true"><?php echo esc_html( strtoupper( substr( $t_name, 0, 1 ) ) ); ?></div>
            <div>
              <cite class="testimonial-name"><?php echo esc_html( $t_name ); ?></cite>
              <span class="testimonial-role"><?php echo esc_html( $t_role ); ?></span>
            </div>
          </footer>
        </blockquote>
      <?php endforeach; ?>
    <?php endif; ?>
  </div>
</section>
