<?php
/**
 * Ifende Portfolio — front-page.php
 *
 * Routes the homepage URL between two rendering modes based on the
 * Settings → Reading configuration:
 *
 *   1. Static page on front
 *      `Settings → Reading → "Your homepage displays" = "A static page"` plus
 *      a chosen page. Delegates to page.php so the page's content (built with
 *      the block editor, Elementor, or any other editor) renders verbatim
 *      with whatever blocks/widgets the editor laid down.
 *
 *   2. Default — Customizer-driven one-page layout
 *      `Settings → Reading → "Your homepage displays" = "Your latest posts"`
 *      (or no static page chosen). Delegates to index.php which walks the
 *      template-parts/section-*.php sections in order, populated from
 *      Customizer settings and the Services / Clients / Testimonials / FAQ
 *      CPTs. This is the theme's original behaviour — installs that haven't
 *      migrated to a static front page see no change.
 *
 * Why a routing template at all?
 *   WordPress's template hierarchy already maps "static page on front" to
 *   page.php and "latest posts" to index.php. But adding front-page.php
 *   gives us a single place to put the conditional, making the choice
 *   explicit and giving us a hook point for future migration helpers
 *   (e.g. "if static page set, suppress the redundant Customizer hero
 *   section so two homepages don't drift").
 *
 * Cache-friendly: the get_option() calls are cheap (autoloaded options).
 *
 * @package Ifende
 * @since   1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if (
	'page' === get_option( 'show_on_front' )
	&& (int) get_option( 'page_on_front' ) > 0
) {
	require get_template_directory() . '/page.php';
	return;
}

require get_template_directory() . '/index.php';
