<?php
/**
 * Ifende Portfolio — footer.php
 */
?>
<footer class="site-footer">
  <div class="footer-logo"><?php echo esc_html(get_bloginfo('name')||'Onyemechi'); ?><em>.</em></div>
  <div class="footer-copy">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?> &middot; All rights reserved</div>
  <div class="footer-links">
    <a href="#home">Home</a>
    <a href="#about">About</a>
    <a href="#services">Services</a>
    <a href="#contact">Contact</a>
  </div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
