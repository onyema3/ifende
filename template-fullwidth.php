<?php
/**
 * Template Name: Full Width
 * Template Post Type: page, post
 *
 * Full-width template with header and footer but no max-width constraint.
 * Ideal for Elementor or block editor designs.
 *
 * @package Ifende
 */

get_header();
?>
<main id="main-content" tabindex="-1" class="fullwidth-content">
  <?php
  while ( have_posts() ) :
    the_post();
    ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="entry-content"><?php the_content(); ?></div>
    </article>
    <?php
  endwhile;
  ?>
</main>
<?php get_footer(); ?>
