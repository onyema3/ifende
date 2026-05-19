<?php
/**
 * Performance Optimisations — Critical CSS inlining, media-swap deferral,
 * script deferral, and security headers.
 *
 * Goal: ship a render-blocking-free first paint.
 *
 *   1. Inline ~5 KB of above-the-fold CSS in <head> via wp_head priority 1
 *      (root vars, reset, nav, hero, hero CTAs, preloader, mobile nav).
 *   2. Load main.css and fonts.css with the media="print" + onload swap
 *      technique so they download in parallel without blocking rendering.
 *   3. Provide a <noscript> fallback so non-JS browsers still receive the
 *      full stylesheet.
 *
 * The critical bundle is intentionally inlined as a literal string (not
 * read from disk) to avoid a per-request file read on every front-end
 * page view. Keep it in sync with assets/css/main.css when the
 * above-the-fold styles change.
 *
 * @package Ifende
 * @since   1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Stylesheet handles that should be loaded with the media-swap technique
 * (print → all on load) so they don't block the initial paint.
 *
 * Anything not in this list is emitted with the default render-blocking
 * <link rel="stylesheet">. WP/plugin-owned handles are deliberately
 * excluded — we only defer assets we own.
 *
 * @return string[]
 */
function ifende_deferrable_style_handles() {
	/**
	 * Filter the list of stylesheet handles that should be deferred via
	 * media-swap. Plugins or child themes can extend or shorten this list.
	 *
	 * @since 1.1.0
	 *
	 * @param string[] $handles Default deferrable handles.
	 */
	return apply_filters(
		'ifende_deferrable_style_handles',
		[
			'ifende-main',
			'ifende-fonts',
		]
	);
}

/**
 * Inline above-the-fold CSS in <head> to eliminate render-blocking on the
 * initial paint.
 *
 * What's inlined (in order):
 *   - :root + light-theme custom property overrides
 *   - Box-sizing reset, html, body
 *   - Skip-link + screen-reader-text (accessibility must work pre-CSS)
 *   - Site preloader (full-screen overlay; would otherwise FOUC)
 *   - Site nav (logo, links, hamburger, admin-bar offset)
 *   - Hero section (layout, photo, status, stats, headings, bio)
 *   - Hero CTA buttons (.btn-primary, .btn-secondary)
 *   - @keyframes pulse / spin used above the fold
 *   - Mobile + reduced-motion media queries that affect the nav and hero
 *
 * Hooked at wp_head priority 1 — printed before any preload/preconnect
 * hints so the parser can use these styles immediately.
 *
 * @since 1.1.0
 */
function ifende_inline_critical_css() {
	if ( is_admin() ) {
		return;
	}
	?>
	<style id="ifende-critical-css">
	:root{--black:#0A0A0A;--white:#F5F2EC;--green:#21A14E;--green2:#17783A;--gold:#C9A84C;--grey:#8A8A8A;--border:rgba(245,242,236,0.12);}
	[data-theme="light"]{--black:#F5F2EC;--white:#1A1A1A;--border:rgba(26,26,26,0.1);}
	*,*::before,*::after{margin:0;padding:0;box-sizing:border-box;}
	html{scroll-behavior:smooth;}
	body{font-family:'Syne',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;background:var(--black);color:var(--white);overflow-x:hidden;}
	.skip-link{position:absolute;top:-100px;left:5vw;z-index:10000;background:var(--green);color:var(--black);padding:12px 24px;font-size:0.78rem;font-weight:700;letter-spacing:1px;text-transform:uppercase;text-decoration:none;border-radius:0 0 4px 4px;transition:top .2s;}
	.skip-link:focus{top:0;}
	.screen-reader-text{clip:rect(1px,1px,1px,1px);clip-path:inset(50%);height:1px;width:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;word-wrap:normal!important;}
	.scroll-progress{position:fixed;top:0;left:0;height:2px;width:0;background:var(--green);z-index:10001;transition:width .1s linear;}
	.site-preloader{position:fixed;inset:0;z-index:99999;background:var(--black);display:flex;align-items:center;justify-content:center;transition:opacity .4s ease,visibility .4s ease;}
	.site-preloader.loaded{opacity:0;visibility:hidden;pointer-events:none;}
	.preloader-spinner{width:32px;height:32px;border:2px solid var(--border);border-top-color:var(--green);border-radius:50%;animation:spin .7s linear infinite;}
	@keyframes spin{to{transform:rotate(360deg)}}
	.site-nav{position:fixed;top:0;left:0;right:0;z-index:200;padding:24px 5vw;display:flex;justify-content:space-between;align-items:center;transition:all .3s;}
	.site-nav.scrolled{background:rgba(10,10,10,0.92);backdrop-filter:blur(14px);border-bottom:1px solid var(--border);padding:16px 5vw;}
	.nav-logo{font-family:'Cormorant Garamond',serif;font-size:1.2rem;font-weight:600;letter-spacing:1px;color:var(--white);text-decoration:none;}
	.nav-logo em{color:var(--green);font-style:normal;}
	.site-nav .custom-logo,.site-nav .custom-logo-link img{max-height:40px;width:auto;height:auto;object-fit:contain;display:block;}
	.nav-links{display:flex;gap:32px;list-style:none;align-items:center;margin:0;padding:0;}
	.nav-links a{font-size:0.72rem;font-weight:600;letter-spacing:2.5px;text-transform:uppercase;color:rgba(245,242,236,0.6);text-decoration:none;transition:color .2s;}
	.hamburger{display:none;flex-direction:column;gap:5px;cursor:pointer;background:none;border:none;padding:4px;z-index:201;}
	.hamburger span{display:block;width:24px;height:1.5px;background:var(--white);transition:all .3s;}
	.admin-bar .site-nav{top:32px;}
	.hero-section{min-height:100vh;display:grid;grid-template-columns:1fr 1fr;align-items:center;padding:120px 5vw 80px;position:relative;overflow:hidden;}
	.hero-bg{position:absolute;inset:0;z-index:0;background:radial-gradient(ellipse 50% 60% at 70% 40%,rgba(33,161,78,0.06) 0%,transparent 70%),radial-gradient(ellipse 30% 40% at 10% 80%,rgba(201,168,76,0.04) 0%,transparent 60%);}
	.hero-grid-bg{position:absolute;inset:0;background-image:linear-gradient(rgba(245,242,236,0.03) 1px,transparent 1px),linear-gradient(90deg,rgba(245,242,236,0.03) 1px,transparent 1px);background-size:80px 80px;}
	.hero-content{position:relative;z-index:2;}
	.hero-label{font-family:'DM Mono',monospace;font-size:0.7rem;letter-spacing:3px;text-transform:uppercase;color:var(--green);margin-bottom:24px;display:flex;align-items:center;gap:12px;}
	.hero-label::before{content:'';width:32px;height:1px;background:var(--green);}
	.hero-section h1{font-family:'Cormorant Garamond',serif;font-size:clamp(3.5rem,7vw,6.5rem);font-weight:300;line-height:1.02;color:var(--white);margin-bottom:8px;}
	.hero-section h1 em{font-style:italic;color:var(--gold);}
	.hero-title-line{font-family:'DM Mono',monospace;font-size:0.72rem;letter-spacing:3px;text-transform:uppercase;color:var(--grey);margin-bottom:32px;display:flex;align-items:center;gap:8px;flex-wrap:wrap;}
	.title-sep{color:var(--green);}
	.hero-bio{font-size:1rem;line-height:1.8;color:rgba(245,242,236,0.65);max-width:460px;margin-bottom:40px;}
	.hero-actions{display:flex;gap:16px;flex-wrap:wrap;margin-bottom:48px;}
	.hero-stats{display:flex;gap:40px;padding-top:40px;border-top:1px solid var(--border);flex-wrap:wrap;}
	.stat-num{font-family:'Cormorant Garamond',serif;font-size:2.2rem;font-weight:600;color:var(--green);line-height:1;}
	.stat-label{font-size:0.65rem;letter-spacing:2px;text-transform:uppercase;color:var(--grey);margin-top:4px;}
	.hero-right{position:relative;z-index:2;display:flex;justify-content:flex-end;align-items:center;}
	.hero-photo-wrap{position:relative;width:380px;height:480px;}
	.hero-photo-border{position:absolute;top:16px;left:16px;right:-16px;bottom:-16px;border:1px solid rgba(33,161,78,0.3);border-radius:4px;z-index:0;}
	.hero-photo{position:relative;z-index:1;width:100%;height:100%;border-radius:4px;overflow:hidden;background:linear-gradient(145deg,#1a2f1a,#0d1a0d);}
	.hero-photo img{width:100%;height:100%;object-fit:cover;}
	.hero-photo-placeholder{width:100%;height:100%;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:16px;background:linear-gradient(145deg,rgba(33,161,78,0.15),rgba(10,10,10,0.8));}
	.photo-initials{font-family:'Cormorant Garamond',serif;font-size:5rem;font-weight:300;color:var(--green);line-height:1;}
	.photo-name{font-size:0.7rem;letter-spacing:3px;text-transform:uppercase;color:rgba(245,242,236,0.4);}
	.hero-status{position:absolute;bottom:24px;left:-24px;z-index:2;background:var(--black);border:1px solid var(--border);padding:14px 20px;border-radius:4px;display:flex;align-items:center;gap:10px;}
	.status-dot{width:8px;height:8px;border-radius:50%;background:var(--green);animation:pulse 2s infinite;}
	.status-text{font-size:0.72rem;letter-spacing:1.5px;text-transform:uppercase;color:var(--white);}
	@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:0.5;transform:scale(1.3)}}
	.btn-primary{display:inline-flex;align-items:center;gap:10px;background:var(--green);color:var(--black);padding:14px 30px;border-radius:2px;font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;text-decoration:none;transition:all .3s;}
	.btn-secondary{display:inline-flex;align-items:center;gap:10px;border:1px solid rgba(245,242,236,0.25);color:var(--white);padding:14px 30px;border-radius:2px;font-size:0.72rem;font-weight:600;letter-spacing:2px;text-transform:uppercase;text-decoration:none;transition:all .3s;}
	@media(max-width:900px){.hamburger{display:flex!important;}.nav-links{display:none!important;}.hero-section{grid-template-columns:1fr;padding:100px 5vw 60px;}.hero-right{display:none;}}
	@media(max-width:782px){.admin-bar .site-nav{top:46px;}}
	@media(max-width:600px){.hero-stats{gap:24px;}}
	@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:0.01ms!important;animation-iteration-count:1!important;transition-duration:0.01ms!important;scroll-behavior:auto!important;}}
	</style>
	<?php
}
add_action( 'wp_head', 'ifende_inline_critical_css', 1 );

/**
 * Convert the main stylesheet's <link> tag to a non-render-blocking variant
 * via the media-swap technique.
 *
 * Trick: setting media="print" tells the browser the stylesheet only applies
 * to print rendering, so it downloads it asynchronously without blocking
 * paint. The onload handler then flips media back to "all" so the styles
 * actually apply once they've arrived. A <noscript> twin is emitted as a
 * fallback for JS-disabled browsers.
 *
 * Skipped:
 *   - admin (dashboard / block editor) — render-blocking is fine there
 *   - customize preview — Customizer needs styles applied immediately
 *   - feed requests
 *
 * @since 1.1.0
 *
 * @param string $tag    The original <link> tag.
 * @param string $handle The registered handle.
 * @param string $href   The stylesheet URL.
 * @param string $media  The original media attribute (unused; we override).
 * @return string Filtered tag (deferred + noscript fallback) or original.
 */
function ifende_defer_non_critical_styles( $tag, $handle, $href, $media ) {
	unset( $media ); // Intentionally overridden; see media-swap technique above.

	if ( is_admin() || is_feed() ) {
		return $tag;
	}

	if ( function_exists( 'is_customize_preview' ) && is_customize_preview() ) {
		return $tag;
	}

	if ( ! in_array( $handle, ifende_deferrable_style_handles(), true ) ) {
		return $tag;
	}

	if ( empty( $href ) ) {
		return $tag;
	}

	$id_attr = esc_attr( $handle . '-css' );
	$href    = esc_url( $href );

	$deferred = sprintf(
		'<link rel="stylesheet" id="%1$s" href="%2$s" media="print" onload="this.media=\'all\';this.onload=null;">',
		$id_attr,
		$href
	);

	$fallback = sprintf(
		'<noscript><link rel="stylesheet" id="%1$s-noscript" href="%2$s" media="all"></noscript>',
		$id_attr,
		$href
	);

	return $deferred . "\n" . $fallback . "\n";
}
add_filter( 'style_loader_tag', 'ifende_defer_non_critical_styles', 10, 4 );

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
	if ( is_admin() ) {
		return $tag;
	}

	// Scripts that must NOT be deferred (critical / dependency).
	$no_defer = [
		'jquery-core',
		'jquery-migrate',
		'wp-polyfill',
		'wp-hooks',
		'ifende-no-flash', // Theme detection — must run synchronously in <head>.
		'ifende-main',     // Theme script must run immediately to dismiss the preloader.
	];

	if ( in_array( $handle, $no_defer, true ) ) {
		return $tag;
	}

	if ( strpos( $tag, ' defer' ) !== false || strpos( $tag, ' async' ) !== false ) {
		return $tag;
	}

	if ( empty( $src ) ) {
		return $tag;
	}

	$tag = str_replace( '<script ', '<script defer ', $tag );

	return $tag;
}
add_filter( 'script_loader_tag', 'ifende_defer_scripts', 10, 3 );

/**
 * Preload the main stylesheet so it starts downloading in parallel with
 * the HTML parse. Combined with the media-swap deferral above, this gives
 * the browser the file early without making it render-blocking.
 *
 * Note: We do NOT preload Google Fonts here — the theme uses self-hosted
 * woff2 files preloaded in inc/enqueue.php. A leftover googleapis.com hint
 * was removed in 1.1.0 (it pointed at a stylesheet the theme no longer
 * loads).
 *
 * @since 1.1.0
 */
function ifende_preload_critical_assets() {
	if ( is_admin() ) {
		return;
	}

	$suffix = ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
	?>
	<link rel="preload" href="<?php echo esc_url( IFENDE_URI . '/assets/css/main' . $suffix . '.css' ); ?>" as="style">
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
 * Add DNS prefetch hints for external domains.
 *
 * Currently advertises Google Fonts CDN — kept for compatibility with
 * page builders (e.g. Elementor) that may pull additional Google Fonts
 * even though the theme itself self-hosts its woff2 files.
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

/**
 * Add Content Security Policy and other security headers on the front-end.
 *
 * Uses a permissive policy that works with Google Fonts (legacy), GA,
 * Calendly/Cal.com embeds, Tawk/Crisp chat, Formspree/Web3Forms — while
 * still blocking truly malicious injection vectors like external object/
 * embed tags.
 *
 * @param array $headers Existing headers.
 * @return array Modified headers.
 */
function ifende_security_headers( $headers ) {
	if ( is_admin() ) {
		return $headers;
	}

	$csp  = "default-src 'self'; ";
	$csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com https://assets.calendly.com https://app.cal.com https://embed.tawk.to https://client.crisp.chat https://wa.me; ";
	$csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com https://assets.calendly.com; ";
	$csp .= "font-src 'self' https://fonts.gstatic.com; ";
	$csp .= "img-src 'self' data: https: http:; ";
	$csp .= "connect-src 'self' https://www.google-analytics.com https://formspree.io https://api.web3forms.com https://region1.google-analytics.com; ";
	$csp .= "frame-src https://calendly.com https://app.cal.com https://www.youtube.com https://player.vimeo.com; ";
	$csp .= "object-src 'none'; ";
	$csp .= "base-uri 'self';";

	$headers['Content-Security-Policy'] = $csp;
	$headers['X-Content-Type-Options']  = 'nosniff';
	$headers['X-Frame-Options']         = 'SAMEORIGIN';
	$headers['Referrer-Policy']         = 'strict-origin-when-cross-origin';
	$headers['Permissions-Policy']      = 'camera=(), microphone=(), geolocation=()';

	return $headers;
}
add_filter( 'wp_headers', 'ifende_security_headers' );
