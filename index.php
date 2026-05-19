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
  <?php do_action( 'ifende_before_hero' ); ?>
  <?php get_template_part( 'template-parts/section', 'hero' ); ?>
  <?php do_action( 'ifende_after_hero' ); ?>
  <?php get_template_part( 'template-parts/section', 'marquee' ); ?>
  <?php do_action( 'ifende_before_about' ); ?>
  <?php get_template_part( 'template-parts/section', 'about' ); ?>
  <?php do_action( 'ifende_after_about' ); ?>
  <?php get_template_part( 'template-parts/section', 'services' ); ?>
  <?php do_action( 'ifende_after_services' ); ?>
  <?php get_template_part( 'template-parts/section', 'portfolio' ); ?>
  <?php do_action( 'ifende_after_portfolio' ); ?>
  <?php get_template_part( 'template-parts/section', 'clients' ); ?>
  <?php do_action( 'ifende_after_clients' ); ?>
  <?php get_template_part( 'template-parts/section', 'testimonials' ); ?>
  <?php do_action( 'ifende_after_testimonials' ); ?>
  <?php get_template_part( 'template-parts/section', 'blog' ); ?>
  <?php do_action( 'ifende_after_blog' ); ?>
  <?php get_template_part( 'template-parts/section', 'faq' ); ?>
  <?php do_action( 'ifende_after_faq' ); ?>
  <?php get_template_part( 'template-parts/section', 'newsletter' ); ?>
  <?php do_action( 'ifende_after_newsletter' ); ?>
  <?php get_template_part( 'template-parts/section', 'contact' ); ?>
  <?php do_action( 'ifende_after_contact' ); ?>
</main>

<?php get_footer(); ?>
