<?php
/**
 * Template Name: Canvas (Full Width, No Header/Footer)
 * Template Post Type: page, post
 *
 * A blank canvas template for Elementor or block editor.
 * Provides full control over the page layout.
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class( 'ifende-canvas' ); ?>>
<?php wp_body_open(); ?>

<?php
while ( have_posts() ) :
  the_post();
  the_content();
endwhile;
?>

<?php wp_footer(); ?>
</body>
</html>
