<?php
/**
 * Social Menu Walker
 *
 * Renders a nav menu as a row of icon-only links. Detects the platform from
 * each menu item's URL (or its label as a fallback) and outputs an inline SVG.
 *
 * Usage:
 *   wp_nav_menu( [
 *     'theme_location' => 'social',
 *     'container'      => false,
 *     'items_wrap'     => '<ul class="ifende-social-menu">%3$s</ul>',
 *     'walker'         => new Ifende_Social_Walker(),
 *     'depth'          => 1,
 *     'fallback_cb'    => false,
 *   ] );
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'Ifende_Social_Walker' ) ) :

class Ifende_Social_Walker extends Walker_Nav_Menu {

	/**
	 * Render a single menu item as an icon link. Submenus are ignored (depth=1).
	 *
	 * @param string   $output Passed by reference. Used to append additional content.
	 * @param WP_Post  $item   Menu item data object.
	 * @param int      $depth  Depth of menu item.
	 * @param stdClass $args   wp_nav_menu args.
	 * @param int      $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$url   = ! empty( $item->url ) ? $item->url : '';
		$title = ! empty( $item->title ) ? $item->title : '';

		$platform = ifende_detect_social_platform( $url, $title );
		$icon     = ifende_social_icon_svg( $platform );
		$label    = $title ? $title : ucfirst( $platform );

		$classes   = empty( $item->classes ) ? [] : (array) $item->classes;
		$classes[] = 'ifende-social-item';
		$classes[] = 'ifende-social-item--' . $platform;
		$class_str = join( ' ', array_map( 'sanitize_html_class', array_filter( $classes ) ) );

		// Open in a new tab for off-site, but not for mailto/tel.
		$target_attr = '';
		$rel_attr    = '';
		if ( $url && ! preg_match( '#^(mailto:|tel:|/|#)#i', $url ) ) {
			$target_attr = ' target="_blank"';
			$rel_attr    = ' rel="noopener noreferrer"';
		}

		$output .= sprintf(
			'<li class="%1$s"><a href="%2$s" aria-label="%3$s"%4$s%5$s>%6$s<span class="screen-reader-text">%3$s</span></a></li>',
			esc_attr( $class_str ),
			esc_url( $url ),
			esc_attr( $label ),
			$target_attr,
			$rel_attr,
			$icon // Already-escaped SVG markup.
		);
	}

	/**
	 * No-op: list items are self-closed in start_el.
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		// Intentionally empty.
	}
}

endif;

/**
 * Map a URL (or label fallback) to a known social platform slug.
 *
 * @param string $url   Menu item URL.
 * @param string $title Menu item label (used when URL is generic, e.g. mailto).
 * @return string Platform slug, e.g. 'github', 'linkedin', 'link' for unknowns.
 */
function ifende_detect_social_platform( $url, $title = '' ) {
	$url = strtolower( (string) $url );

	if ( 0 === strpos( $url, 'mailto:' ) ) {
		return 'email';
	}
	if ( 0 === strpos( $url, 'tel:' ) ) {
		return 'phone';
	}

	$host = wp_parse_url( $url, PHP_URL_HOST );
	$host = $host ? preg_replace( '/^www\./', '', $host ) : '';

	$map = [
		'github.com'        => 'github',
		'linkedin.com'      => 'linkedin',
		'twitter.com'       => 'twitter',
		'x.com'             => 'twitter',
		'instagram.com'     => 'instagram',
		'facebook.com'      => 'facebook',
		'fb.com'            => 'facebook',
		'youtube.com'       => 'youtube',
		'youtu.be'          => 'youtube',
		'dribbble.com'      => 'dribbble',
		'behance.net'       => 'behance',
		'tiktok.com'        => 'tiktok',
		'mastodon.social'   => 'mastodon',
		'bsky.app'          => 'bluesky',
		'wa.me'             => 'whatsapp',
		'whatsapp.com'      => 'whatsapp',
		't.me'              => 'telegram',
		'telegram.org'      => 'telegram',
		'discord.gg'        => 'discord',
		'discord.com'       => 'discord',
		'medium.com'        => 'medium',
		'codepen.io'        => 'codepen',
		'stackoverflow.com' => 'stackoverflow',
	];

	if ( $host && isset( $map[ $host ] ) ) {
		return $map[ $host ];
	}

	// Suffix-match for Mastodon-style instances and country TLDs.
	if ( $host ) {
		foreach ( $map as $needle => $slug ) {
			if ( substr( $host, -strlen( $needle ) ) === $needle ) {
				return $slug;
			}
		}
	}

	// Fallback: try matching the menu item title.
	$lower_title = strtolower( $title );
	foreach ( [ 'github', 'linkedin', 'twitter', 'instagram', 'facebook', 'youtube', 'dribbble', 'behance', 'tiktok', 'mastodon', 'bluesky', 'whatsapp', 'telegram', 'discord', 'medium', 'codepen' ] as $slug ) {
		if ( false !== strpos( $lower_title, $slug ) ) {
			return $slug;
		}
	}

	return 'link';
}

/**
 * Return inline SVG markup for a known platform slug.
 *
 * Icons are simple stroked glyphs sized to currentColor, 20x20.
 *
 * @param string $slug Platform slug from ifende_detect_social_platform().
 * @return string SVG markup (safe to echo; no user-generated content).
 */
function ifende_social_icon_svg( $slug ) {
	$icons = [
		'github'    => '<path d="M12 2a10 10 0 0 0-3.16 19.49c.5.09.68-.22.68-.48v-1.7c-2.78.6-3.37-1.34-3.37-1.34-.46-1.16-1.11-1.47-1.11-1.47-.91-.62.07-.6.07-.6 1 .07 1.53 1.03 1.53 1.03.89 1.53 2.34 1.09 2.91.83.09-.65.35-1.09.63-1.34-2.22-.25-4.55-1.11-4.55-4.94 0-1.09.39-1.98 1.03-2.68-.1-.25-.45-1.27.1-2.65 0 0 .84-.27 2.75 1.02a9.56 9.56 0 0 1 5 0c1.91-1.29 2.75-1.02 2.75-1.02.55 1.38.2 2.4.1 2.65.64.7 1.03 1.59 1.03 2.68 0 3.84-2.34 4.69-4.57 4.94.36.31.68.92.68 1.85v2.74c0 .27.18.58.69.48A10 10 0 0 0 12 2z"/>',
		'linkedin'  => '<path d="M4.98 3.5a2.5 2.5 0 1 1 0 5 2.5 2.5 0 0 1 0-5zM3 9h4v12H3V9zm7 0h3.8v1.7h.05c.53-.95 1.83-1.95 3.77-1.95 4.03 0 4.78 2.65 4.78 6.1V21h-4v-5.4c0-1.29-.02-2.95-1.8-2.95-1.8 0-2.08 1.4-2.08 2.86V21h-4V9z"/>',
		'twitter'   => '<path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>',
		'instagram' => '<rect x="3" y="3" width="18" height="18" rx="5" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="12" cy="12" r="4" fill="none" stroke="currentColor" stroke-width="2"/><circle cx="17.5" cy="6.5" r="1.2" fill="currentColor"/>',
		'facebook'  => '<path d="M22 12a10 10 0 1 0-11.56 9.88v-6.99H7.9V12h2.54V9.8c0-2.5 1.49-3.89 3.78-3.89 1.09 0 2.24.2 2.24.2v2.46h-1.26c-1.24 0-1.63.77-1.63 1.56V12h2.78l-.45 2.89h-2.34v6.99A10 10 0 0 0 22 12z"/>',
		'youtube'   => '<path d="M23.5 6.2a3 3 0 0 0-2.12-2.13C19.5 3.5 12 3.5 12 3.5s-7.5 0-9.38.57A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.12 2.13C4.5 20.5 12 20.5 12 20.5s7.5 0 9.38-.57a3 3 0 0 0 2.12-2.13A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8zM9.6 15.57V8.43L15.82 12z"/>',
		'dribbble'  => '<circle cx="12" cy="12" r="10" fill="none" stroke="currentColor" stroke-width="2"/><path d="M2.4 9.5c4 .8 11.6 1 16-1.5M3 16c4-2.5 11-3.5 16 0M9 3c2 4 4 12 4.5 18.5" fill="none" stroke="currentColor" stroke-width="2"/>',
		'behance'   => '<path d="M7.5 7H3v10h4.7c2 0 3.6-.9 3.6-2.9 0-1.4-.7-2.2-1.7-2.5.7-.3 1.2-1 1.2-2 0-1.7-1.4-2.6-3.3-2.6zm-.3 4h-2V8.7h2c.8 0 1.4.4 1.4 1.1S8 11 7.2 11zm.3 4.4h-2.3v-2.6h2.3c1 0 1.6.5 1.6 1.3s-.6 1.3-1.6 1.3zM21 13.6c0-2.4-1.4-4.1-3.9-4.1s-4 1.8-4 4.1 1.5 4 4.1 4c1.9 0 3.3-.9 3.7-2.5h-2.1c-.2.5-.7.8-1.5.8-1.1 0-1.7-.7-1.8-1.7H21zm-5.5-1.1c.1-.9.7-1.5 1.6-1.5s1.5.6 1.5 1.5zM14 7h5v1.4h-5z"/>',
		'tiktok'    => '<path d="M16 3v2.5a4.5 4.5 0 0 0 4.5 4.5V12a7 7 0 0 1-4.5-1.7V16a5 5 0 1 1-5-5v2.5a2.5 2.5 0 1 0 2.5 2.5V3z"/>',
		'mastodon'  => '<path d="M19 9.4c0-3.4-2.2-4.4-2.2-4.4-1.1-.5-3-.7-5-.7h-.1c-2 0-3.9.2-5 .7 0 0-2.2 1-2.2 4.4v3.5c0 3.5 2.2 4.4 4.5 4.7 1.3.1 2.5.2 3.7.1 2.3 0 2.9-.7 2.9-.7v-1.6s-.8.4-2.7.5c-1.9.1-3.7-.2-4-2.4 0 0 0-.2.1-.2 0 0 1.7.4 4 .4 1.4 0 2.7-.1 4-.2 1.9-.2 3.5-1.4 3.7-2.4.3-1.7.3-2.7.3-2.7zm-2.7 4h-1.7V9.3c0-.9-.4-1.4-1.1-1.4-.8 0-1.2.5-1.2 1.5v2.2h-1.7V9.4c0-1-.4-1.5-1.2-1.5-.7 0-1.1.5-1.1 1.4v4.1H6.6V9.2c0-.9.2-1.7.7-2.2.5-.6 1.2-.9 2.1-.9 1 0 1.8.4 2.3 1.2l.4.7.4-.7c.5-.8 1.3-1.2 2.3-1.2.9 0 1.6.3 2.1.9.5.6.7 1.3.7 2.2v4.2z"/>',
		'bluesky'   => '<path d="M6 4c2.5 1.7 5 5 6 7 1-2 3.5-5.3 6-7 1.5-1 4-1.7 4 1.3 0 .6-.4 4.5-.6 5.2-.7 2.2-3 2.7-5 2.4 3.5.6 4.5 2.6 2.5 4.6-3.6 3.7-5.2-.9-5.6-2.1l-.3-.9-.3.9c-.4 1.2-2 5.8-5.6 2.1-2-2-1-4 2.5-4.6-2 .3-4.3-.2-5-2.4-.2-.7-.6-4.6-.6-5.2 0-3 2.5-2.3 4-1.3z"/>',
		'whatsapp'  => '<path d="M20.5 3.5A10 10 0 0 0 3.6 16.6L2 22l5.5-1.4A10 10 0 1 0 20.5 3.5zm-8.5 17a8.4 8.4 0 0 1-4.3-1.2l-.3-.2-3.2.8.9-3.1-.2-.3a8.4 8.4 0 1 1 7.1 4zm4.6-6.2c-.3-.1-1.5-.7-1.7-.8s-.4-.1-.6.1-.7.8-.8 1-.3.2-.6.1c-1.6-.8-2.7-1.5-3.7-3.4-.3-.5.3-.5.8-1.6.1-.2 0-.3 0-.5s-.6-1.4-.8-2c-.2-.5-.4-.4-.6-.4h-.5c-.2 0-.5.1-.7.3-.3.3-1 1-1 2.4s1 2.8 1.2 3 2.1 3.2 5 4.5c1.8.8 2.5.9 3.4.7.5-.1 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2-.1-.1-.3-.2-.6-.3z"/>',
		'telegram'  => '<path d="M9.78 18.65l.28-4.23 7.68-6.92c.34-.31-.07-.46-.52-.19L7.74 13.5 3.64 12.2c-.88-.25-.89-.86.2-1.3l15.97-6.16c.73-.33 1.43.18 1.15 1.3l-2.72 12.81c-.19.91-.74 1.13-1.5.71L12.6 16.3l-1.99 1.93c-.23.23-.42.42-.83.42z"/>',
		'discord'   => '<path d="M19.27 5.33A18 18 0 0 0 14.85 4l-.22.4a13.4 13.4 0 0 1 4 2 13.7 13.7 0 0 0-13.3 0 13.4 13.4 0 0 1 4-2L9.16 4a18 18 0 0 0-4.43 1.33A18.5 18.5 0 0 0 2 17.6 13.4 13.4 0 0 0 6.05 19.6c.32-.44.6-.9.85-1.4a8.7 8.7 0 0 1-1.34-.65c.11-.08.22-.17.33-.25a9.6 9.6 0 0 0 8.22 0c.11.08.22.17.33.25a8.7 8.7 0 0 1-1.34.65c.25.5.53.96.85 1.4a13.4 13.4 0 0 0 4.05-2 18.5 18.5 0 0 0-2.73-12.27zM8.52 14.62a2 2 0 0 1-1.85-2.1 1.99 1.99 0 0 1 1.85-2.1 2 2 0 0 1 1.85 2.1 1.99 1.99 0 0 1-1.85 2.1zm6.96 0a2 2 0 0 1-1.85-2.1 1.99 1.99 0 0 1 1.85-2.1 2 2 0 0 1 1.85 2.1 1.99 1.99 0 0 1-1.85 2.1z"/>',
		'medium'    => '<path d="M2.85 6.18a.96.96 0 0 0-.31-.81L.27 2.62V2.2h7.04l5.44 11.94L17.53 2.2H24v.42l-1.94 1.86a.57.57 0 0 0-.21.54v13.6a.57.57 0 0 0 .21.55l1.9 1.86v.42h-9.55v-.42l1.96-1.9c.2-.2.2-.25.2-.55V7.59l-5.46 13.86h-.74L3.05 7.59v9.29c-.05.4.08.8.37 1.07l2.55 3.1v.41H0v-.41l2.55-3.1c.28-.27.4-.67.34-1.07V6.18z"/>',
		'codepen'   => '<polygon points="12,2 22,8.5 22,15.5 12,22 2,15.5 2,8.5" fill="none" stroke="currentColor" stroke-width="2"/><line x1="2" y1="8.5" x2="22" y2="15.5" stroke="currentColor" stroke-width="2"/><line x1="22" y1="8.5" x2="2" y2="15.5" stroke="currentColor" stroke-width="2"/><line x1="12" y1="2" x2="12" y2="9" stroke="currentColor" stroke-width="2"/><line x1="12" y1="15" x2="12" y2="22" stroke="currentColor" stroke-width="2"/>',
		'stackoverflow' => '<path d="M17 21v-5h2v7H3v-7h2v5zM7.5 18l9 1.9.4-2-9-1.9zm.7-4.6 8.6 4 .9-1.8-8.6-4zm2.4-4.4 7.4 6.1 1.3-1.5-7.4-6.1zm4.6-4.4-1.6 1.2 5.7 7.7 1.6-1.2zm-3.4 13H6v2h11.8z"/>',
		'email'     => '<rect x="2" y="4" width="20" height="16" rx="2" fill="none" stroke="currentColor" stroke-width="2"/><path d="M2 6l10 7 10-7" fill="none" stroke="currentColor" stroke-width="2"/>',
		'phone'     => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round"/>',
		'link'      => '<path d="M10 13a5 5 0 0 0 7.07 0l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.72M14 11a5 5 0 0 0-7.07 0l-3 3a5 5 0 0 0 7.07 7.07l1.72-1.72" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>',
	];

	$path = isset( $icons[ $slug ] ) ? $icons[ $slug ] : $icons['link'];

	return '<svg class="ifende-social-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true" focusable="false">' . $path . '</svg>';
}

/**
 * Convenience renderer used by templates. Outputs nothing if no menu is set.
 *
 * @param array $args Optional overrides for wp_nav_menu().
 */
function ifende_render_social_menu( $args = [] ) {
	if ( ! has_nav_menu( 'social' ) ) {
		return;
	}

	$defaults = [
		'theme_location' => 'social',
		'container'      => false,
		'items_wrap'     => '<ul class="ifende-social-menu" aria-label="' . esc_attr__( 'Social links', 'ifende' ) . '">%3$s</ul>',
		'walker'         => new Ifende_Social_Walker(),
		'depth'          => 1,
		'fallback_cb'    => false,
	];

	wp_nav_menu( array_merge( $defaults, $args ) );
}
