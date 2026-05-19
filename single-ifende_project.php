<?php
/**
 * Single Project Template — Full case study layout.
 *
 * Displays: Hero image, project meta, content (challenge/solution/results),
 * tech stack tags, client testimonial, live URL button, and navigation.
 *
 * @package Ifende
 * @since   1.3.0
 */

get_header();
?>
<main id="main-content" tabindex="-1" class="if-section" style="padding-top:140px;">
  <?php while ( have_posts() ) : the_post();
    $client = get_post_meta( get_the_ID(), '_ifende_project_client', true );
    $url    = get_post_meta( get_the_ID(), '_ifende_project_url', true );
    $year   = get_post_meta( get_the_ID(), '_ifende_project_year', true );
    $tech   = get_post_meta( get_the_ID(), '_ifende_project_tech', true );
    $cats   = get_the_terms( get_the_ID(), 'project_category' );
  ?>
  <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-project-wrap' ); ?>>

    <!-- Project Header -->
    <div class="project-header">
      <div class="section-label"><?php esc_html_e( 'Case Study', 'ifende' ); ?></div>
      <h1 class="section-title" style="margin-bottom:16px;"><?php the_title(); ?></h1>

      <?php if ( $client || $year || ( $cats && ! is_wp_error( $cats ) ) ) : ?>
        <div class="project-meta">
          <?php if ( $client ) : ?>
            <div class="project-meta-item">
              <span class="project-meta-label"><?php esc_html_e( 'Client', 'ifende' ); ?></span>
              <span class="project-meta-value"><?php echo esc_html( $client ); ?></span>
            </div>
          <?php endif; ?>
          <?php if ( $year ) : ?>
            <div class="project-meta-item">
              <span class="project-meta-label"><?php esc_html_e( 'Year', 'ifende' ); ?></span>
              <span class="project-meta-value"><?php echo esc_html( $year ); ?></span>
            </div>
          <?php endif; ?>
          <?php if ( $cats && ! is_wp_error( $cats ) ) : ?>
            <div class="project-meta-item">
              <span class="project-meta-label"><?php esc_html_e( 'Category', 'ifende' ); ?></span>
              <span class="project-meta-value"><?php echo esc_html( implode( ', ', wp_list_pluck( $cats, 'name' ) ) ); ?></span>
            </div>
          <?php endif; ?>
          <?php if ( $url ) : ?>
            <div class="project-meta-item">
              <span class="project-meta-label"><?php esc_html_e( 'Live', 'ifende' ); ?></span>
              <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="project-meta-link"><?php echo esc_html( wp_parse_url( $url, PHP_URL_HOST ) ); ?> ↗</a>
            </div>
          <?php endif; ?>
        </div>
      <?php endif; ?>
    </div>

    <!-- Featured Image -->
    <?php if ( has_post_thumbnail() ) : ?>
      <div class="project-featured-image">
        <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'fetchpriority' => 'high' ] ); ?>
      </div>
    <?php endif; ?>

    <!-- Project Content (Challenge / Solution / Results) -->
    <div class="project-content post-content">
      <?php the_content(); ?>
    </div>

    <!-- Tech Stack -->
    <?php if ( $tech ) : ?>
      <div class="project-tech-section">
        <div class="section-label"><?php esc_html_e( 'Tech Stack', 'ifende' ); ?></div>
        <div class="project-tech-grid">
          <?php foreach ( explode( ',', $tech ) as $t ) : ?>
            <span class="tech-tag"><?php echo esc_html( trim( $t ) ); ?></span>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>

    <!-- CTA -->
    <div class="project-cta">
      <?php if ( $url ) : ?>
        <a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="btn-primary"><?php esc_html_e( 'View Live Project', 'ifende' ); ?> <span>↗</span></a>
      <?php endif; ?>
      <a href="<?php echo esc_url( get_post_type_archive_link( 'ifende_project' ) ); ?>" class="btn-secondary"><?php esc_html_e( 'All Projects', 'ifende' ); ?> &rarr;</a>
    </div>

    <!-- Project Navigation -->
    <nav class="project-navigation" aria-label="<?php esc_attr_e( 'Project navigation', 'ifende' ); ?>">
      <?php
      $prev = get_adjacent_post( false, '', true );
      $next = get_adjacent_post( false, '', false );
      ?>
      <div class="project-nav-item project-nav-prev">
        <?php if ( $prev ) : ?>
          <span class="project-nav-label"><?php esc_html_e( 'Previous Project', 'ifende' ); ?></span>
          <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="project-nav-link">&larr; <?php echo esc_html( get_the_title( $prev ) ); ?></a>
        <?php endif; ?>
      </div>
      <div class="project-nav-item project-nav-next">
        <?php if ( $next ) : ?>
          <span class="project-nav-label"><?php esc_html_e( 'Next Project', 'ifende' ); ?></span>
          <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="project-nav-link"><?php echo esc_html( get_the_title( $next ) ); ?> &rarr;</a>
        <?php endif; ?>
      </div>
    </nav>

  </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
