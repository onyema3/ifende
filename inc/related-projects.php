<?php
/**
 * Related Projects — Show similar projects on single project pages.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display related projects based on shared project_category.
 *
 * @param int $count Number of related projects to show.
 */
function ifende_related_projects( $count = 3 ) {
	if ( ! is_singular( 'ifende_project' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$cats    = get_the_terms( $post_id, 'project_category' );

	$query_args = [
		'post_type'      => 'ifende_project',
		'posts_per_page' => $count,
		'post__not_in'   => [ $post_id ],
		'orderby'        => 'rand',
		'post_status'    => 'publish',
	];

	if ( $cats && ! is_wp_error( $cats ) ) {
		$query_args['tax_query'] = [ [
			'taxonomy' => 'project_category',
			'field'    => 'term_id',
			'terms'    => wp_list_pluck( $cats, 'term_id' ),
		] ];
	}

	$related = new WP_Query( $query_args );

	if ( ! $related->have_posts() ) {
		return;
	}
	?>
	<section class="related-projects">
		<div class="section-label"><?php esc_html_e( 'More Work', 'ifende' ); ?></div>
		<h2 class="related-projects-title"><?php esc_html_e( 'Related Projects', 'ifende' ); ?></h2>
		<div class="related-projects-grid">
			<?php while ( $related->have_posts() ) : $related->the_post(); ?>
				<a href="<?php the_permalink(); ?>" class="related-project-card">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="related-project-image">
							<?php the_post_thumbnail( 'medium', [ 'loading' => 'lazy' ] ); ?>
						</div>
					<?php endif; ?>
					<div class="related-project-info">
						<h3 class="related-project-name"><?php the_title(); ?></h3>
						<?php $client = get_post_meta( get_the_ID(), '_ifende_project_client', true ); ?>
						<?php if ( $client ) : ?>
							<span class="related-project-client"><?php echo esc_html( $client ); ?></span>
						<?php endif; ?>
					</div>
				</a>
			<?php endwhile; ?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}
