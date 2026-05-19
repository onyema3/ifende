<?php
/**
 * Ifende Portfolio — index.php
 * Main homepage template using modular template parts.
 *
 * @package Ifende
 */

get_header();
?>

<main id="main-content">
  <?php get_template_part( 'template-parts/section', 'hero' ); ?>
  <?php get_template_part( 'template-parts/section', 'marquee' ); ?>
  <?php get_template_part( 'template-parts/section', 'about' ); ?>
  <?php get_template_part( 'template-parts/section', 'services' ); ?>
  <?php get_template_part( 'template-parts/section', 'portfolio' ); ?>
  <?php get_template_part( 'template-parts/section', 'clients' ); ?>
  <?php get_template_part( 'template-parts/section', 'testimonials' ); ?>
  <?php get_template_part( 'template-parts/section', 'blog' ); ?>
  <?php get_template_part( 'template-parts/section', 'faq' ); ?>
  <?php get_template_part( 'template-parts/section', 'newsletter' ); ?>
  <?php get_template_part( 'template-parts/section', 'contact' ); ?>
</main>

<?php get_footer(); ?>
