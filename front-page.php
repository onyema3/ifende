<?php
/**
 * Ifende Portfolio — front-page.php
 *
 * Routes the homepage URL between two rendering modes based on the
 * Settings → Reading configuration:
 *
 *   1. Static page on front
 *      `Settings → Reading → "Your homepage displays" = "A static page"`
 *      plus a chosen page. Renders the page edge-to-edge inside a
 *      fullwidth <main>, so each homepage section can take full-viewport
 *      control of its own padding (matching the index.php layout).
 *
 *      We deliberately do NOT delegate to page.php here. page.php wraps
 *      content in `<main class="page-content-wrap">` whose CSS centres
 *      everything to an 860px column — fine for blog posts, wrong for a
 *      portfolio homepage where each section paints across the viewport.
 *      We also suppress the page title (a "Home" H1 above the hero is
 *      visually redundant) and skip wp_link_pages() (homepages don't
 *      paginate).
 *
 *      The output mirrors template-fullwidth.php with one addition:
 *      an `ifende-static-front-page` marker class so future styling can
 *      target the homepage specifically without affecting other
 *      Full-Width pages.
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
 *   gives us a single place to put the conditional and override the
 *   page.php width constraint for the homepage case, so editors don't
 *   have to remember to assign the Full Width template manually.
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
	get_header();
	?>
	<main id="main-content" tabindex="-1" class="fullwidth-content ifende-static-front-page">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<div class="entry-content"><?php the_content(); ?></div>
			</article>
			<?php
		endwhile;
		?>
	</main>
	<?php
	get_footer();
	return;
}

require get_template_directory() . '/index.php';
