<?php
/**
 * Blog Enhancements — Reading time, related posts, and share buttons.
 *
 * @package Ifende
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Calculate estimated reading time for a post.
 *
 * @param int|null $post_id Post ID. Default: current post.
 * @param int      $wpm     Words per minute. Default: 200.
 * @return int Estimated reading time in minutes (minimum 1).
 */
function ifende_reading_time( $post_id = null, $wpm = 200 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$content    = get_post_field( 'post_content', $post_id );
	$content    = wp_strip_all_tags( strip_shortcodes( $content ) );
	$word_count = str_word_count( $content );
	$minutes    = max( 1, (int) ceil( $word_count / $wpm ) );

	return $minutes;
}

/**
 * Display formatted reading time badge.
 *
 * @param int|null $post_id Post ID. Default: current post.
 * @param bool     $echo    Whether to echo or return. Default true.
 * @return string|void
 */
function ifende_reading_time_badge( $post_id = null, $echo = true ) {
	$minutes = ifende_reading_time( $post_id );

	$html = sprintf(
		'<span class="reading-time" aria-label="%s">%s %s</span>',
		/* translators: %d: number of minutes */
		esc_attr( sprintf( _n( '%d minute read', '%d minutes read', $minutes, 'ifende' ), $minutes ) ),
		'<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
		/* translators: %d: number of minutes */
		esc_html( sprintf( _n( '%d min read', '%d min read', $minutes, 'ifende' ), $minutes ) )
	);

	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	return $html;
}

/**
 * Get related posts based on shared categories/tags.
 *
 * @param int|null $post_id Post ID. Default: current post.
 * @param int      $count   Number of related posts. Default: 3.
 * @return WP_Post[] Array of related post objects.
 */
function ifende_related_posts( $post_id = null, $count = 3 ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$categories = wp_get_post_categories( $post_id, [ 'fields' => 'ids' ] );
	$tags       = wp_get_post_tags( $post_id, [ 'fields' => 'ids' ] );

	// Build tax_query: posts sharing categories OR tags.
	$tax_query = [ 'relation' => 'OR' ];

	if ( ! empty( $categories ) ) {
		$tax_query[] = [
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => $categories,
		];
	}

	if ( ! empty( $tags ) ) {
		$tax_query[] = [
			'taxonomy' => 'post_tag',
			'field'    => 'term_id',
			'terms'    => $tags,
		];
	}

	// If no taxonomy terms, fall back to recent posts.
	$query_args = [
		'post_type'      => 'post',
		'posts_per_page' => $count,
		'post__not_in'   => [ $post_id ],
		'orderby'        => 'rand',
		'post_status'    => 'publish',
	];

	if ( count( $tax_query ) > 1 ) {
		$query_args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
	}

	$query = new WP_Query( $query_args );

	return $query->posts;
}

/**
 * Display related posts section.
 *
 * @param int|null $post_id Post ID. Default: current post.
 * @param int      $count   Number of related posts. Default: 3.
 */
function ifende_related_posts_section( $post_id = null, $count = 3 ) {
	$related = ifende_related_posts( $post_id, $count );

	if ( empty( $related ) ) {
		return;
	}
	?>
	<section class="related-posts" aria-labelledby="related-posts-title">
		<h2 id="related-posts-title" class="section-label"><?php esc_html_e( 'Related Posts', 'ifende' ); ?></h2>
		<div class="related-posts-grid">
			<?php foreach ( $related as $post ) : ?>
				<article class="related-post-card">
					<?php if ( has_post_thumbnail( $post ) ) : ?>
						<a href="<?php echo esc_url( get_permalink( $post ) ); ?>" class="related-post-thumbnail" aria-hidden="true" tabindex="-1">
							<?php echo get_the_post_thumbnail( $post, 'medium', [ 'loading' => 'lazy' ] ); ?>
						</a>
					<?php endif; ?>
					<div class="related-post-content">
						<time class="related-post-date" datetime="<?php echo esc_attr( get_the_date( 'c', $post ) ); ?>">
							<?php echo esc_html( get_the_date( '', $post ) ); ?>
						</time>
						<h3 class="related-post-title">
							<a href="<?php echo esc_url( get_permalink( $post ) ); ?>"><?php echo esc_html( get_the_title( $post ) ); ?></a>
						</h3>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</section>
	<?php
	wp_reset_postdata();
}

/**
 * Display social share buttons for the current post.
 *
 * @param int|null $post_id Post ID. Default: current post.
 * @param bool     $echo    Whether to echo or return. Default true.
 * @return string|void
 */
function ifende_share_buttons( $post_id = null, $echo = true ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	$permalink = rawurlencode( get_permalink( $post_id ) );
	$title     = rawurlencode( get_the_title( $post_id ) );

	$networks = [
		'twitter'  => [
			'label' => __( 'Share on X (Twitter)', 'ifende' ),
			'url'   => "https://twitter.com/intent/tweet?url={$permalink}&text={$title}",
			'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
		],
		'linkedin' => [
			'label' => __( 'Share on LinkedIn', 'ifende' ),
			'url'   => "https://www.linkedin.com/sharing/share-offsite/?url={$permalink}",
			'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
		],
		'facebook' => [
			'label' => __( 'Share on Facebook', 'ifende' ),
			'url'   => "https://www.facebook.com/sharer/sharer.php?u={$permalink}",
			'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
		],
		'email'    => [
			'label' => __( 'Share via Email', 'ifende' ),
			'url'   => "mailto:?subject={$title}&body={$permalink}",
			'icon'  => '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="M22 7l-10 6L2 7"/></svg>',
		],
	];

	$html = '<div class="share-buttons" aria-label="' . esc_attr__( 'Share this post', 'ifende' ) . '">' . "\n";
	$html .= '<span class="share-label">' . esc_html__( 'Share', 'ifende' ) . '</span>' . "\n";

	foreach ( $networks as $key => $network ) {
		$html .= sprintf(
			'<a href="%s" class="share-btn share-btn--%s" target="_blank" rel="noopener noreferrer" aria-label="%s">%s</a>' . "\n",
			esc_url( $network['url'] ),
			esc_attr( $key ),
			esc_attr( $network['label'] ),
			$network['icon']
		);
	}

	$html .= '</div>';

	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	return $html;
}
