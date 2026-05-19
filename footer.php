<?php
/**
 * Ifende Portfolio — footer.php
 *
 * @package Ifende
 */
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
<?php wp_footer(); ?>
</body>
</html>
