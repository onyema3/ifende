<?php
/**
 * Ifende Child Theme — functions.php
 *
 * Enqueues parent theme styles and provides a place
 * for child-theme-specific functionality.
 *
 * @package Ifende_Child
 * @since   1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue parent and child theme styles.
 *
 * The parent theme's main stylesheet is loaded first, then the child
 * theme's style.css is loaded as a dependency so it always overrides.
 */
function ifende_child_enqueue_styles() {
	$parent_handle = 'ifende-main';

	// Parent theme stylesheet (already registered by parent — ensure dependency).
	wp_enqueue_style(
		$parent_handle,
		get_template_directory_uri() . '/assets/css/main.min.css',
		[],
		wp_get_theme( 'ifende' )->get( 'Version' )
	);

	// Child theme stylesheet.
	wp_enqueue_style(
		'ifende-child-style',
		get_stylesheet_uri(),
		[ $parent_handle ],
		wp_get_theme()->get( 'Version' )
	);
}
add_action( 'wp_enqueue_scripts', 'ifende_child_enqueue_styles', 20 );

/*
 * ─────────────────────────────────────────────
 * Add your custom functions below this line.
 * ─────────────────────────────────────────────
 */
