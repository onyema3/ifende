<?php
/**
 * Ifende Portfolio — header.php
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
<div class="cursor" id="cursor"></div>
<div class="cursor-ring" id="cursorRing"></div>
<nav class="site-nav" id="siteNav">
  <a class="nav-logo" href="<?php echo esc_url(home_url('/')); ?>">
    <?php if(has_custom_logo()):
      $lid=get_theme_mod('custom_logo');
      $lurl=wp_get_attachment_image_url($lid,'full');
      echo '<img src="'.esc_url($lurl).'" alt="'.get_bloginfo('name').'" style="height:40px;width:auto;">';
    else:
      echo esc_html(get_bloginfo('name')||'Onyemechi').'<em>.</em>';
    endif; ?>
  </a>
  <?php wp_nav_menu([
    'theme_location'=>'primary','container'=>false,
    'items_wrap'=>'<ul class="nav-links">%3$s</ul>',
    'fallback_cb'=>function(){ ?>
      <ul class="nav-links">
        <li><a href="#about">About</a></li>
        <li><a href="#services">Services</a></li>
        <li><a href="#clients">Clients</a></li>
        <li><a href="#contact" class="btn-nav-cta">Get In Touch</a></li>
      </ul>
    <?php }
  ]); ?>
  <button class="hamburger" id="hamburger" onclick="toggleDrawer()">
    <span></span><span></span><span></span>
  </button>
</nav>
<div class="mobile-drawer" id="mobileDrawer">
  <a href="#about"    onclick="toggleDrawer()">About</a>
  <a href="#services" onclick="toggleDrawer()">Services</a>
  <a href="#clients"  onclick="toggleDrawer()">Clients</a>
  <a href="#contact"  onclick="toggleDrawer()">Contact</a>
</div>
