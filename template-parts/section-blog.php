<?php
/**
 * Template Part: Blog / Latest Posts Section
 *
 * @package Ifende
 */

$blog_posts = new WP_Query( [
  'posts_per_page'      => 3,
  'post_status'         => 'publish',
  'ignore_sticky_posts' => true,
] );

if ( ! $blog_posts->have_posts() ) {
  return;
}
?>
<section class="if-section dark" id="blog">
  <div class="section-label"><?php esc_html_e( 'Latest Posts', 'ifende' ); ?></div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'From the<br><em>Blog</em>', 'ifende' ) ); ?></h2>
    <p class="section-sub reveal reveal-d1"><?php esc_html_e( 'Thoughts, insights, and updates on web development, project management, and the digital landscape.', 'ifende' ); ?></p>
  </div>
  <div class="blog-grid reveal">
    <?php while ( $blog_posts->have_posts() ) : $blog_posts->the_post(); ?>
      <article class="blog-card">
        <?php if ( has_post_thumbnail() ) : ?>
          <a href="<?php the_permalink(); ?>" class="blog-card-image">
            <?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy' ] ); ?>
          </a>
        <?php endif; ?>
        <div class="blog-card-content">
          <time class="blog-card-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
          <h3 class="blog-card-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
          <p class="blog-card-excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 18 ) ); ?></p>
          <a href="<?php the_permalink(); ?>" class="blog-card-link"><?php esc_html_e( 'Read More', 'ifende' ); ?> &rarr;</a>
        </div>
      </article>
    <?php endwhile; ?>
  </div>
  <?php wp_reset_postdata(); ?>
  <div style="text-align:center;margin-top:48px;" class="reveal reveal-d2">
    <a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="btn-secondary"><?php esc_html_e( 'View All Posts', 'ifende' ); ?> &rarr;</a>
  </div>
</section>
