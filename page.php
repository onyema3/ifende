<?php
/**
 * Ifende Portfolio — page.php
 * Template for displaying static pages.
 * Fully compatible with Elementor, Gutenberg, and other page builders.
 *
 * @package Ifende
 */

get_header();
?>
<main id="main-content" class="page-content-wrap <?php echo ifende_is_built_with_elementor() ? 'elementor-page' : ''; ?>">
  <?php while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <?php
      // Only show the title if NOT built with Elementor (Elementor handles its own titles).
      if ( ! ifende_is_built_with_elementor() ) :
      ?>
        <h1><?php the_title(); ?></h1>
      <?php endif; ?>
      <div class="entry-content">
        <?php the_content(); ?>
        <?php
        wp_link_pages( [
          'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ifende' ),
          'after'  => '</div>',
        ] );
        ?>
      </div>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
