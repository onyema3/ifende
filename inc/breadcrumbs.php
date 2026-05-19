<?php
/**
 * Breadcrumbs — Theme-native with Schema.org BreadcrumbList markup.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Build the breadcrumb trail for the current request as an array.
 *
 * Returns one entry per crumb, in render order, each shaped as
 * `[ 'name' => string, 'url' => string ]`. The last entry's URL is
 * an empty string, signalling "this is the current page".
 *
 * Extracted from {@see ifende_breadcrumbs()} so the same trail can
 * back both the HTML rendering and the JSON-LD BreadcrumbList emitter
 * in `inc/seo.php` without duplicating the routing logic.
 *
 * @since 1.5.0
 *
 * @return array<int, array{name: string, url: string}> Empty on the front page.
 */
function ifende_breadcrumb_items() {
	if ( is_front_page() ) {
		return [];
	}

	$items   = [];
	$items[] = [
		'name' => esc_html__( 'Home', 'ifende' ),
		'url'  => home_url( '/' ),
	];

	if ( is_singular( 'post' ) ) {
		$items[] = [ 'name' => esc_html__( 'Blog', 'ifende' ), 'url' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ];
		$items[] = [ 'name' => get_the_title(), 'url' => '' ];
	} elseif ( is_singular( 'ifende_project' ) ) {
		$items[] = [ 'name' => esc_html__( 'Projects', 'ifende' ), 'url' => get_post_type_archive_link( 'ifende_project' ) ];
		$items[] = [ 'name' => get_the_title(), 'url' => '' ];
	} elseif ( is_page() ) {
		$items[] = [ 'name' => get_the_title(), 'url' => '' ];
	} elseif ( is_category() || is_tag() ) {
		$items[] = [ 'name' => esc_html__( 'Blog', 'ifende' ), 'url' => get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ];
		$items[] = [ 'name' => single_term_title( '', false ), 'url' => '' ];
	} elseif ( is_post_type_archive( 'ifende_project' ) ) {
		$items[] = [ 'name' => esc_html__( 'Projects', 'ifende' ), 'url' => '' ];
	} elseif ( is_tax( 'project_category' ) ) {
		$items[] = [ 'name' => esc_html__( 'Projects', 'ifende' ), 'url' => get_post_type_archive_link( 'ifende_project' ) ];
		$items[] = [ 'name' => single_term_title( '', false ), 'url' => '' ];
	} elseif ( is_search() ) {
		/* translators: %s: search query */
		$items[] = [ 'name' => sprintf( esc_html__( 'Search: %s', 'ifende' ), get_search_query() ), 'url' => '' ];
	} elseif ( is_404() ) {
		$items[] = [ 'name' => esc_html__( '404', 'ifende' ), 'url' => '' ];
	} elseif ( is_archive() ) {
		$items[] = [ 'name' => get_the_archive_title(), 'url' => '' ];
	}

	return $items;
}

/**
 * Output breadcrumbs with Schema.org structured data.
 *
 * @param bool $echo Whether to echo or return.
 * @return string|void
 */
function ifende_breadcrumbs( $echo = true ) {
	$items = ifende_breadcrumb_items();
	if ( empty( $items ) ) {
		return '';
	}

	// Build HTML.
	$html  = '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'ifende' ) . '">';
	$html .= '<ol class="breadcrumbs-list" itemscope itemtype="https://schema.org/BreadcrumbList">';

	foreach ( $items as $i => $item ) {
		$position = $i + 1;
		$is_last  = ( $i === count( $items ) - 1 );

		$html .= '<li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

		if ( ! $is_last && ! empty( $item['url'] ) ) {
			$html .= '<a href="' . esc_url( $item['url'] ) . '" itemprop="item"><span itemprop="name">' . esc_html( $item['name'] ) . '</span></a>';
		} else {
			$html .= '<span itemprop="name" aria-current="page">' . esc_html( $item['name'] ) . '</span>';
		}

		$html .= '<meta itemprop="position" content="' . esc_attr( $position ) . '">';
		$html .= '</li>';

		if ( ! $is_last ) {
			$html .= '<li class="breadcrumbs-sep" aria-hidden="true">/</li>';
		}
	}

	$html .= '</ol></nav>';

	if ( $echo ) {
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		return;
	}

	return $html;
}

/**
 * Auto-output breadcrumbs on relevant pages.
 */
function ifende_auto_breadcrumbs() {
	if ( is_front_page() || is_admin() ) {
		return;
	}

	// Only show on single posts, projects, archives, search, 404.
	if ( is_singular() || is_archive() || is_search() || is_404() ) {
		echo '<div class="breadcrumbs-wrap">';
		ifende_breadcrumbs();
		echo '</div>';
	}
}
add_action( 'ifende_after_header', 'ifende_auto_breadcrumbs' );
