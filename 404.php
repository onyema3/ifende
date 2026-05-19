<?php
/**
 * Ifende Portfolio — 404.php
 * Enhanced 404 page with search and animated background.
 *
 * @package Ifende
 */

get_header();
?>
<main id="main-content" class="error-404-wrap">
  <div class="error-404-bg" aria-hidden="true">
    <span class="error-404-big">404</span>
  </div>
  <div class="error-404-content">
    <div class="section-label"><?php esc_html_e( 'Page Not Found', 'ifende' ); ?></div>
    <h1 class="section-title"><?php esc_html_e( 'Oops!', 'ifende' ); ?> <em><?php esc_html_e( 'Lost?', 'ifende' ); ?></em></h1>
    <p class="section-sub">
      <?php esc_html_e( "The page you're looking for doesn't exist or has been moved. Try searching or head back home.", 'ifende' ); ?>
    </p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary"><?php esc_html_e( 'Back to Home', 'ifende' ); ?> <span>&rarr;</span></a>
    <form class="error-404-search" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <input type="search" name="s" placeholder="<?php esc_attr_e( 'Search the site...', 'ifende' ); ?>" aria-label="<?php esc_attr_e( 'Search', 'ifende' ); ?>">
      <button type="submit"><?php esc_html_e( 'Search', 'ifende' ); ?></button>
    </form>
  </div>
</main>
<?php get_footer(); ?>
