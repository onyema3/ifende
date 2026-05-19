<?php
/**
 * Ifende Portfolio — search.php
 * Template for displaying search results.
 *
 * @package Ifende
 */

get_header();
?>
<main id="main-content" tabindex="-1" class="if-section" style="padding-top:140px;">
  <div class="section-label"><?php esc_html_e( 'Search Results', 'ifende' ); ?></div>
  <h1 class="section-title" style="margin-bottom:48px;">
    <?php
    printf(
      /* translators: %s: search query */
      esc_html__( 'Results for: %s', 'ifende' ),
      '<em>' . esc_html( get_search_query() ) . '</em>'
    );
    ?>
  </h1>

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

    <nav class="archive-pagination" aria-label="<?php esc_attr_e( 'Search results pagination', 'ifende' ); ?>">
      <?php
      the_posts_pagination( [
        'prev_text' => '&larr; ' . esc_html__( 'Previous', 'ifende' ),
        'next_text' => esc_html__( 'Next', 'ifende' ) . ' &rarr;',
      ] );
      ?>
    </nav>
  <?php else : ?>
    <div style="text-align:center;padding:60px 0;">
      <p class="section-sub" style="margin-bottom:32px;"><?php esc_html_e( 'No results found. Try a different search term.', 'ifende' ); ?></p>
      <?php get_search_form(); ?>
    </div>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
