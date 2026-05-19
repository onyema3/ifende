<?php
/**
 * Ifende Portfolio — single.php
 * Template for displaying individual blog posts.
 * Compatible with Elementor and block editor.
 *
 * @package Ifende
 */

get_header();
?>
<main id="main-content" tabindex="-1" class="if-section" style="padding-top:140px;">
  <div class="single-post-wrap <?php echo ifende_is_built_with_elementor() ? 'elementor-page' : ''; ?>">
    <?php while ( have_posts() ) : the_post(); ?>
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

        <?php if ( ! ifende_is_built_with_elementor() ) : ?>
          <div class="section-label"><?php echo esc_html( get_the_date() ); ?></div>
          <h1 class="section-title" style="margin-bottom:16px;"><?php the_title(); ?></h1>

          <div class="post-meta" style="margin-bottom:40px;">
            <span class="post-meta-item">
              <?php
              printf(
                /* translators: %s: author name */
                esc_html__( 'By %s', 'ifende' ),
                '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '" style="color:var(--green);text-decoration:none;">' . esc_html( get_the_author() ) . '</a>'
              );
              ?>
            </span>
            <?php if ( has_category() ) : ?>
              <span class="post-meta-item"> &middot; <?php the_category( ', ' ); ?></span>
            <?php endif; ?>
            <?php if ( function_exists( 'ifende_reading_time_badge' ) ) : ?>
              <span class="post-meta-item"> &middot; <?php ifende_reading_time_badge(); ?></span>
            <?php endif; ?>
          </div>

          <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-featured-image" style="margin-bottom:48px;border-radius:4px;overflow:hidden;">
              <?php the_post_thumbnail( 'large', [ 'style' => 'width:100%;height:auto;display:block;', 'loading' => 'lazy' ] ); ?>
            </div>
          <?php endif; ?>
        <?php endif; ?>

        <div class="post-content">
          <?php the_content(); ?>
        </div>

        <?php if ( ! ifende_is_built_with_elementor() ) : ?>
          <?php
          wp_link_pages( [
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'ifende' ),
            'after'  => '</div>',
          ] );
          ?>

          <div class="post-tags" style="margin-top:48px;padding-top:32px;border-top:1px solid var(--border);">
            <?php the_tags( '<div class="section-label" style="margin-bottom:12px;">' . esc_html__( 'Tags', 'ifende' ) . '</div><div class="skills-grid" style="grid-template-columns:repeat(auto-fill,minmax(120px,1fr));">', '', '</div>' ); ?>
          </div>

          <?php if ( function_exists( 'ifende_share_buttons' ) ) : ?>
            <div class="post-share" style="margin-top:48px;padding-top:32px;border-top:1px solid var(--border);">
              <?php ifende_share_buttons(); ?>
            </div>
          <?php endif; ?>

          <nav class="post-navigation" style="margin-top:48px;display:flex;justify-content:space-between;gap:24px;flex-wrap:wrap;" aria-label="<?php esc_attr_e( 'Post navigation', 'ifende' ); ?>">
            <div>
              <?php
              $prev = get_previous_post();
              if ( $prev ) :
              ?>
                <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="btn-secondary">&larr; <?php echo esc_html( get_the_title( $prev ) ); ?></a>
              <?php endif; ?>
            </div>
            <div>
              <?php
              $next = get_next_post();
              if ( $next ) :
              ?>
                <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="btn-secondary"><?php echo esc_html( get_the_title( $next ) ); ?> &rarr;</a>
              <?php endif; ?>
            </div>
          </nav>

          <?php if ( function_exists( 'ifende_related_posts_section' ) ) : ?>
            <?php ifende_related_posts_section(); ?>
          <?php endif; ?>
        <?php endif; ?>
      </article>

      <?php
      if ( ! ifende_is_built_with_elementor() && ( comments_open() || get_comments_number() ) ) {
        comments_template();
      }
      ?>
    <?php endwhile; ?>
  </div>
</main>
<?php get_footer(); ?>
