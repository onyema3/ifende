<?php
/**
 * Block Patterns — category registration only.
 *
 * The patterns themselves live as standalone files under /patterns/ and
 * are auto-discovered by WordPress 6.0+ via _register_theme_block_patterns().
 * Each file uses the standard pattern header format (Title, Slug,
 * Categories, Description, Keywords, Inserter) and the body of the file
 * is its block markup.
 *
 * This file only does the one thing auto-discovery doesn't handle: register
 * the 'ifende' pattern category so the patterns can be filed under it.
 *
 * Pattern files in /patterns/:
 *   - hero.php          mirrors template-parts/section-hero.php
 *   - marquee.php       mirrors template-parts/section-marquee.php
 *   - about.php         mirrors template-parts/section-about.php
 *   - services.php      mirrors template-parts/section-services.php
 *   - clients.php       mirrors template-parts/section-clients.php
 *   - testimonials.php  mirrors template-parts/section-testimonials.php
 *   - blog.php          mirrors template-parts/section-blog.php
 *   - faq.php           mirrors template-parts/section-faq.php
 *   - newsletter.php    mirrors template-parts/section-newsletter.php
 *   - contact.php       mirrors template-parts/section-contact.php
 *   - portfolio.php     mirrors template-parts/section-portfolio.php
 *   - cta.php           utility: full-width call-to-action banner
 *   - pricing.php       utility: three-tier pricing table
 *
 * The pattern preview in the inserter relies on assets/css/main.css being
 * loaded into the editor canvas — see add_editor_style() in inc/setup.php.
 *
 * @package Ifende
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the 'ifende' pattern category.
 *
 * Categories aren't auto-discovered the way pattern files are, so we
 * register ours explicitly. Hooked on init at default priority — runs
 * before _register_theme_block_patterns() at priority 99, so the
 * category exists by the time the patterns try to claim it.
 *
 * @since 1.2.0
 */
function ifende_register_pattern_category() {
	register_block_pattern_category(
		'ifende',
		[
			'label' => esc_html__( 'Ifende', 'ifende' ),
		]
	);
}
add_action( 'init', 'ifende_register_pattern_category' );
