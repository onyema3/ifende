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
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

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
