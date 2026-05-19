<?php
/**
 * Template Part: Portfolio / Projects Section
 *
 * Displays a filterable grid of projects from the ifende_project CPT.
 *
 * @package Ifende
 */

$projects = new WP_Query( [
	'post_type'      => 'ifende_project',
	'posts_per_page' => 6,
	'orderby'        => 'menu_order',
	'order'          => 'ASC',
	'post_status'    => 'publish',
] );

if ( ! $projects->have_posts() ) {
	return;
}

// Get all categories for the filter buttons.
$categories = get_terms( [
	'taxonomy'   => 'project_category',
	'hide_empty' => true,
] );
?>
<section class="if-section" id="portfolio">
	<div class="section-label"><?php esc_html_e( 'Portfolio', 'ifende' ); ?></div>
	<div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:48px;">
		<h2 class="section-title reveal"><?php echo wp_kses_post( __( 'Selected<br><em>Work</em>', 'ifende' ) ); ?></h2>
		<p class="section-sub reveal reveal-d1"><?php esc_html_e( 'A selection of projects that showcase my approach to solving problems through design and code.', 'ifende' ); ?></p>
	</div>

	<?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
		<div class="portfolio-filters reveal" aria-label="<?php esc_attr_e( 'Filter projects', 'ifende' ); ?>">
			<button class="portfolio-filter active" data-filter="all"><?php esc_html_e( 'All', 'ifende' ); ?></button>
			<?php foreach ( $categories as $cat ) : ?>
				<button class="portfolio-filter" data-filter="<?php echo esc_attr( $cat->slug ); ?>"><?php echo esc_html( $cat->name ); ?></button>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="portfolio-grid reveal">
		<?php while ( $projects->have_posts() ) : $projects->the_post();
			$client = get_post_meta( get_the_ID(), '_ifende_project_client', true );
			$url    = get_post_meta( get_the_ID(), '_ifende_project_url', true );
			$year   = get_post_meta( get_the_ID(), '_ifende_project_year', true );
			$tech   = get_post_meta( get_the_ID(), '_ifende_project_tech', true );
			$cats   = get_the_terms( get_the_ID(), 'project_category' );
			$cat_slugs = ( $cats && ! is_wp_error( $cats ) ) ? implode( ' ', wp_list_pluck( $cats, 'slug' ) ) : '';
		?>
			<article class="portfolio-card" data-categories="<?php echo esc_attr( $cat_slugs ); ?>">
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="portfolio-card-image">
						<?php the_post_thumbnail( 'medium_large', [ 'loading' => 'lazy' ] ); ?>
						<div class="portfolio-card-overlay">
							<?php if ( $url ) : ?>
								<a href="<?php echo esc_url( $url ); ?>" target="_blank" rel="noopener" class="portfolio-card-link" aria-label="<?php esc_attr_e( 'View live site', 'ifende' ); ?>">↗</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endif; ?>
				<div class="portfolio-card-content">
					<div class="portfolio-card-meta">
						<?php if ( $client ) : ?><span><?php echo esc_html( $client ); ?></span><?php endif; ?>
						<?php if ( $year ) : ?><span><?php echo esc_html( $year ); ?></span><?php endif; ?>
					</div>
					<h3 class="portfolio-card-title"><?php the_title(); ?></h3>
					<?php if ( has_excerpt() ) : ?>
						<p class="portfolio-card-excerpt"><?php echo esc_html( get_the_excerpt() ); ?></p>
					<?php endif; ?>
					<?php if ( $tech ) : ?>
						<div class="portfolio-card-tech">
							<?php foreach ( explode( ',', $tech ) as $t ) : ?>
								<span class="tech-tag"><?php echo esc_html( trim( $t ) ); ?></span>
							<?php endforeach; ?>
						</div>
					<?php endif; ?>
				</div>
			</article>
		<?php endwhile; wp_reset_postdata(); ?>
	</div>

	<?php if ( $projects->found_posts > 6 ) : ?>
		<div style="text-align:center;margin-top:48px;">
			<a href="<?php echo esc_url( get_post_type_archive_link( 'ifende_project' ) ); ?>" class="btn-secondary"><?php esc_html_e( 'View All Projects', 'ifende' ); ?> &rarr;</a>
		</div>
	<?php endif; ?>
</section>
