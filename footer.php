<?php
/**
 * Ifende Portfolio — footer.php
 *
 * @package Ifende
 */

// Allow Elementor Pro Theme Builder to override the footer.
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'footer' ) ) {
  // Elementor Pro handles the footer — skip default.
} else {

  // Collect the active footer widget areas so we only render the row when needed.
  $ifende_footer_columns = [];
  for ( $i = 1; $i <= 4; $i++ ) {
    if ( is_active_sidebar( 'ifende-footer-' . $i ) ) {
      $ifende_footer_columns[] = $i;
    }
  }
  $ifende_col_count = count( $ifende_footer_columns );
?>
<?php if ( $ifende_col_count ) : ?>
<div class="footer-widgets footer-widgets--cols-<?php echo (int) $ifende_col_count; ?>">
  <?php foreach ( $ifende_footer_columns as $i ) : ?>
    <div class="footer-widget-col footer-widget-col--<?php echo (int) $i; ?>">
      <?php dynamic_sidebar( 'ifende-footer-' . $i ); ?>
    </div>
  <?php endforeach; ?>
</div>
<?php endif; ?>

<footer class="site-footer" role="contentinfo">
  <div class="footer-logo"><?php echo esc_html( get_bloginfo( 'name' ) ); ?><em>.</em></div>
  <div class="footer-copy"><?php echo esc_html( ifende_footer_copyright_text() ); ?></div>
  <nav class="footer-links" aria-label="<?php esc_attr_e( 'Footer navigation', 'ifende' ); ?>">
    <?php
    if ( has_nav_menu( 'footer' ) ) {
      wp_nav_menu( [
        'theme_location' => 'footer',
        'container'      => false,
        'menu_class'     => 'footer-menu',
        'depth'          => 1,
        'fallback_cb'    => false,
      ] );
    } else {
      // Auto-link Privacy / Terms when those pages exist, otherwise fall back to anchors.
      $privacy_url = function_exists( 'get_privacy_policy_url' ) ? get_privacy_policy_url() : '';
      $terms_id    = (int) get_option( 'ifende_terms_page_id' );
      $terms_url   = ( $terms_id && get_post_status( $terms_id ) === 'publish' ) ? get_permalink( $terms_id ) : '';

      if ( $privacy_url || $terms_url ) {
        echo '<ul class="footer-menu">';
        echo '<li><a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'ifende' ) . '</a></li>';
        if ( $privacy_url ) {
          echo '<li><a href="' . esc_url( $privacy_url ) . '">' . esc_html__( 'Privacy', 'ifende' ) . '</a></li>';
        }
        if ( $terms_url ) {
          echo '<li><a href="' . esc_url( $terms_url ) . '">' . esc_html__( 'Terms', 'ifende' ) . '</a></li>';
        }
        echo '</ul>';
      } else {
        // Final fallback: section anchors. On the homepage we use bare hashes
        // (in-page smooth scroll); on every other route we prepend the home
        // URL so clicking, say, "About" from a blog post navigates to the
        // homepage and scrolls to that section instead of dead-clicking.
        $base = is_front_page() ? '' : esc_url( home_url( '/' ) );
        ?>
        <a href="<?php echo $base; ?>#home"><?php esc_html_e( 'Home', 'ifende' ); ?></a>
        <a href="<?php echo $base; ?>#about"><?php esc_html_e( 'About', 'ifende' ); ?></a>
        <a href="<?php echo $base; ?>#services"><?php esc_html_e( 'Services', 'ifende' ); ?></a>
        <a href="<?php echo $base; ?>#contact"><?php esc_html_e( 'Contact', 'ifende' ); ?></a>
        <?php
      }
    }
    ?>
  </nav>
  <?php if ( function_exists( 'ifende_render_social_menu' ) && has_nav_menu( 'social' ) ) : ?>
    <div class="footer-social">
      <?php ifende_render_social_menu(); ?>
    </div>
  <?php endif; ?>
</footer>
<?php } ?>

<?php do_action( 'ifende_before_footer_end' ); ?>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" aria-label="<?php esc_attr_e( 'Back to top', 'ifende' ); ?>" title="<?php esc_attr_e( 'Back to top', 'ifende' ); ?>">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
    <path d="M8 14V2M8 2L2 8M8 2L14 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
