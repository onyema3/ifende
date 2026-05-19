<?php
/**
 * Performance Optimizations — Critical CSS inlining and script deferral.
 *
 * @package Ifende
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Inline critical above-the-fold CSS in <head> to eliminate render-blocking.
 *
 * This inlines a small subset of styles needed for the initial viewport paint
 * (nav, hero, preloader, layout basics). The full stylesheet still loads normally
 * for the rest of the page.
 */
function ifende_inline_critical_css() {
	// Only output on the front-end.
	if ( is_admin() ) {
		return;
	}
	?>
	<style id="ifende-critical-css">
	/* Critical CSS — above-the-fold styles for instant first paint */
	:root{--black:#0A0A0A;--white:#F5F2EC;--green:#21A14E;--green2:#17783A;--gold:#C9A84C;--grey:#8A8A8A;--border:rgba(245,242,236,0.12);}
	[data-theme="light"]{--black:#F5F2EC;--white:#1A1A1A;--border:rgba(26,26,26,0.1);}
	*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
	html{scroll-behavior:smooth;}
	body{font-family:'Syne',sans-serif;background:var(--black);color:var(--white);overflow-x:hidden;}
	.site-nav{position:fixed;top:0;left:0;right:0;z-index:200;padding:24px 5vw;display:flex;justify-content:space-between;align-items:center;transition:all .3s;}
	.nav-logo{font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;letter-spacing:1px;color:var(--white);text-decoration:none;}
	.nav-logo em{color:var(--green);font-style:normal;}
	.nav-links{display:flex;gap:32px;list-style:none;align-items:center;margin:0;padding:0;}
	.nav-links a{font-size:0.72rem;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;color:rgba(245,242,236,0.6);text-decoration:none;transition:color .2s;}
	.site-preloader{position:fixed;inset:0;z-index:10000;background:var(--black);display:flex;align-items:center;justify-content:center;transition:opacity .4s;}
	.if-section{padding:120px 5vw;}
	.section-label{font-size:0.72rem;font-weight:600;letter-spacing:3px;text-transform:uppercase;color:var(--green);}
	.section-title{font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,5vw,4rem);font-weight:300;line-height:1.1;margin-top:12px;}
	.hamburger{display:none;}
	@media(max-width:768px){.nav-links{display:none;}.hamburger{display:flex;}}
	</style>
	<?php
}
add_action( 'wp_head', 'ifende_inline_critical_css', 1 );

/**
 * Add defer attribute to non-critical scripts.
 *
 * Skips scripts that need to run immediately (inline, jQuery core, etc.)
 * and only defers theme/plugin scripts that can safely load later.
 *
 * @param string $tag    The <script> tag HTML.
 * @param string $handle The script handle.
 * @param string $src    The script source URL.
 * @return string Modified script tag.
 */
function ifende_defer_scripts( $tag, $handle, $src ) {
	// Don't defer in admin.
	if ( is_admin() ) {
		return $tag;
	}

	// Scripts that must NOT be deferred (critical / dependency).
	$no_defer = [
		'jquery-core',
		'jquery-migrate',
		'wp-polyfill',
		'wp-hooks',
		'ifende-main', // Theme script must run immediately to dismiss the preloader.
	];

	if ( in_array( $handle, $no_defer, true ) ) {
		return $tag;
	}

	// Skip if already has defer or async.
	if ( strpos( $tag, ' defer' ) !== false || strpos( $tag, ' async' ) !== false ) {
		return $tag;
	}

	// Skip inline scripts (no src).
	if ( empty( $src ) ) {
		return $tag;
	}

	// Add defer attribute.
	$tag = str_replace( '<script ', '<script defer ', $tag );

	return $tag;
}
add_filter( 'script_loader_tag', 'ifende_defer_scripts', 10, 3 );

/**
 * Add preload hints for critical assets.
 *
 * Preloads the main font and stylesheet for faster first contentful paint.
 */
function ifende_preload_critical_assets() {
	if ( is_admin() ) {
		return;
	}

	$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
	?>
	<link rel="preload" href="<?php echo esc_url( IFENDE_URI . '/assets/css/main' . $suffix . '.css' ); ?>" as="style">
	<link rel="preload" href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&display=swap" as="style" crossorigin>
	<?php
}
add_action( 'wp_head', 'ifende_preload_critical_assets', 2 );

/**
 * Add fetchpriority="high" to above-the-fold images and lazy-load others.
 *
 * WordPress 6.3+ handles this natively, but this ensures it for older versions.
 *
 * @param array $attrs Image attributes.
 * @return array Modified attributes.
 */
function ifende_optimize_thumbnail_loading( $attrs ) {
	// If the image is within the hero/header area, prioritize it.
	if ( ! empty( $attrs['class'] ) && strpos( $attrs['class'], 'hero' ) !== false ) {
		$attrs['fetchpriority'] = 'high';
		$attrs['loading']       = 'eager';
	}

	return $attrs;
}
add_filter( 'wp_get_attachment_image_attributes', 'ifende_optimize_thumbnail_loading' );

/**
 * Remove unused WordPress head clutter for performance.
 */
function ifende_cleanup_head() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wp_shortlink_wp_head' );
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
}
add_action( 'init', 'ifende_cleanup_head' );

/**
 * Add DNS prefetch for external domains.
 *
 * @param array  $urls          Existing hints.
 * @param string $relation_type The relation type (dns-prefetch, preconnect, etc.).
 * @return array Modified hints.
 */
function ifende_dns_prefetch( $urls, $relation_type ) {
	if ( 'dns-prefetch' === $relation_type ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = 'https://fonts.gstatic.com';
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'ifende_dns_prefetch', 10, 2 );
