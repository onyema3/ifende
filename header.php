<?php
/**
 * Ifende Portfolio — header.php
 *
 * @package Ifende
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
<script>
// Prevent flash of wrong theme — runs before paint.
(function(){
  var t = localStorage.getItem('ifende-theme');
  if (!t) t = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
  document.documentElement.setAttribute('data-theme', t);
})();
</script>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Scroll Progress Indicator -->
<div class="scroll-progress" id="scrollProgress" aria-hidden="true"></div>

<!-- Page Preloader -->
<div class="site-preloader" id="sitePreloader" aria-hidden="true">
  <div class="preloader-spinner"></div>
</div>

<!-- Skip to content link for screen readers / keyboard users -->
<a class="skip-link screen-reader-text" href="#main-content"><?php esc_html_e( 'Skip to content', 'ifende' ); ?></a>

<div class="cursor" id="cursor" aria-hidden="true"></div>
<div class="cursor-ring" id="cursorRing" aria-hidden="true"></div>

<?php
// Allow Elementor Pro Theme Builder to override the header.
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'header' ) ) {
  // Elementor Pro handles the header — skip default.
} else {
?>
<nav class="site-nav" id="siteNav" aria-label="<?php esc_attr_e( 'Primary navigation', 'ifende' ); ?>">
  <a class="nav-logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
    <?php if ( has_custom_logo() ) :
      $lid  = get_theme_mod( 'custom_logo' );
      $lurl = wp_get_attachment_image_url( $lid, 'full' );
      echo '<img src="' . esc_url( $lurl ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" style="height:40px;width:auto;">';
    else :
      echo esc_html( get_bloginfo( 'name' ) ?: 'Onyemechi' ) . '<em>.</em>';
    endif; ?>
  </a>
  <?php wp_nav_menu( [
    'theme_location' => 'primary',
    'container'      => false,
    'items_wrap'     => '<ul class="nav-links" role="menubar">%3$s</ul>',
    'fallback_cb'    => function() { ?>
      <ul class="nav-links" role="menubar">
        <li role="none"><a href="#about" role="menuitem"><?php esc_html_e( 'About', 'ifende' ); ?></a></li>
        <li role="none"><a href="#services" role="menuitem"><?php esc_html_e( 'Services', 'ifende' ); ?></a></li>
        <li role="none"><a href="#clients" role="menuitem"><?php esc_html_e( 'Clients', 'ifende' ); ?></a></li>
        <li role="none"><a href="#contact" role="menuitem" class="btn-nav-cta"><?php esc_html_e( 'Get In Touch', 'ifende' ); ?></a></li>
      </ul>
    <?php }
  ] ); ?>
  <button
    class="theme-toggle"
    id="themeToggle"
    aria-label="<?php esc_attr_e( 'Toggle dark/light mode', 'ifende' ); ?>"
    title="<?php esc_attr_e( 'Toggle dark/light mode', 'ifende' ); ?>"
  >
    <svg class="theme-icon theme-icon--sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
    <svg class="theme-icon theme-icon--moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
  </button>
  <button
    class="hamburger"
    id="hamburger"
    onclick="toggleDrawer()"
    aria-label="<?php esc_attr_e( 'Toggle mobile menu', 'ifende' ); ?>"
    aria-expanded="false"
    aria-controls="mobileDrawer"
  >
    <span></span><span></span><span></span>
  </button>
</nav>

<div class="mobile-drawer" id="mobileDrawer" role="dialog" aria-label="<?php esc_attr_e( 'Mobile navigation', 'ifende' ); ?>" aria-hidden="true">
  <a href="#about"    onclick="toggleDrawer()"><?php esc_html_e( 'About', 'ifende' ); ?></a>
  <a href="#services" onclick="toggleDrawer()"><?php esc_html_e( 'Services', 'ifende' ); ?></a>
  <a href="#clients"  onclick="toggleDrawer()"><?php esc_html_e( 'Clients', 'ifende' ); ?></a>
  <a href="#contact"  onclick="toggleDrawer()"><?php esc_html_e( 'Contact', 'ifende' ); ?></a>
</div>
<?php } // End Elementor header check. ?>

<?php do_action( 'ifende_after_header' ); ?>
