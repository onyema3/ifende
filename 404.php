<?php
/**
 * Ifende Portfolio — 404.php
 * Template for displaying 404 (Not Found) pages.
 *
 * @package Ifende
 */

get_header();
?>
<main id="main-content" class="if-section" style="min-height:60vh;display:flex;align-items:center;justify-content:center;text-align:center;">
  <div>
    <div class="section-label"><?php esc_html_e( 'Error 404', 'ifende' ); ?></div>
    <h1 class="section-title" style="margin-bottom:24px;"><?php esc_html_e( 'Page Not', 'ifende' ); ?> <em><?php esc_html_e( 'Found', 'ifende' ); ?></em></h1>
    <p class="section-sub" style="max-width:480px;margin:0 auto 40px;">
      <?php esc_html_e( "The page you're looking for doesn't exist or has been moved. Let's get you back on track.", 'ifende' ); ?>
    </p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Back to Home', 'ifende' ); ?> <span>&rarr;</span></a>
  </div>
</main>
<?php get_footer(); ?>
