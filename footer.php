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
?>
<footer class="site-footer" role="contentinfo">
  <div class="footer-logo"><?php echo esc_html( get_bloginfo( 'name' ) ?: 'Onyemechi' ); ?><em>.</em></div>
  <div class="footer-copy">&copy; <?php echo esc_html( wp_date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?> &middot; <?php esc_html_e( 'All rights reserved', 'ifende' ); ?></div>
  <nav class="footer-links" aria-label="<?php esc_attr_e( 'Footer navigation', 'ifende' ); ?>">
    <a href="#home"><?php esc_html_e( 'Home', 'ifende' ); ?></a>
    <a href="#about"><?php esc_html_e( 'About', 'ifende' ); ?></a>
    <a href="#services"><?php esc_html_e( 'Services', 'ifende' ); ?></a>
    <a href="#contact"><?php esc_html_e( 'Contact', 'ifende' ); ?></a>
  </nav>
</footer>
<?php } ?>

<!-- Back to Top Button -->
<button class="back-to-top" id="backToTop" aria-label="<?php esc_attr_e( 'Back to top', 'ifende' ); ?>" title="<?php esc_attr_e( 'Back to top', 'ifende' ); ?>">
  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true">
    <path d="M8 14V2M8 2L2 8M8 2L14 8" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
</button>

<?php wp_footer(); ?>
</body>
</html>
