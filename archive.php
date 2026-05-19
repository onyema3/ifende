<?php
/**
 * Ifende Portfolio — archive.php
 * Template for displaying archive pages (categories, tags, dates, authors).
 *
 * @package Ifende
 */

get_header();
?>
<main id="main-content" class="if-section" style="padding-top:140px;">
  <div class="section-label">
    <?php
    if ( is_category() ) {
      esc_html_e( 'Category', 'ifende' );
    } elseif ( is_tag() ) {
      esc_html_e( 'Tag', 'ifende' );
    } elseif ( is_author() ) {
      esc_html_e( 'Author', 'ifende' );
    } elseif ( is_date() ) {
      esc_html_e( 'Archive', 'ifende' );
    } else {
      esc_html_e( 'Archive', 'ifende' );
    }
    ?>
  </div>
  <h1 class="section-title" style="margin-bottom:48px;"><?php the_archive_title(); ?></h1>

  <?php if ( have_posts() ) : ?>
    <div class="archive-grid">
      <?php while ( have_posts() ) : the_post(); ?>
        <article class="archive-card" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php if ( has_post_thumbnail() ) : ?>
            <a href="<?php the_permalink(); ?>" class="archive-card-image">
              <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy' ] ); ?>
            </a>
          <?php endif; ?>
          <div class="archive-card-content">
            <time class="archive-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
            <h2 class="archive-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <p class="archive-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
            <a href="<?php the_permalink(); ?>" class="archive-card-link"><?php esc_html_e( 'Read More', 'ifende' ); ?> &rarr;</a>
          </div>
        </article>
      <?php endwhile; ?>
    </div>

    <nav class="archive-pagination" aria-label="<?php esc_attr_e( 'Archive pagination', 'ifende' ); ?>">
      <?php
      the_posts_pagination( [
        'prev_text' => '&larr; ' . esc_html__( 'Previous', 'ifende' ),
        'next_text' => esc_html__( 'Next', 'ifende' ) . ' &rarr;',
      ] );
      ?>
    </nav>
  <?php else : ?>
    <p class="section-sub"><?php esc_html_e( 'No posts found in this archive.', 'ifende' ); ?></p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
