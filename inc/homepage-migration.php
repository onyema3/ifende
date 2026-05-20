<?php
/**
 * Homepage Migration Notice
 *
 * One-time admin notice on the Pages list screen pointing users at the
 * static-page-as-homepage workflow so they can edit their homepage with
 * Gutenberg or Elementor instead of (or in addition to) the Customizer-
 * driven default layout.
 *
 * The notice self-suppresses in two situations:
 *   - Once a static front page IS set (the user has already migrated; the
 *     notice has nothing to teach them).
 *   - When the current user has clicked the dismiss "X" (per-user choice,
 *     persisted via user_meta).
 *
 * Pairs with front-page.php: the notice explains the workflow that
 * front-page.php enables.
 *
 * @package Ifende
 * @since   1.6.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * User-meta key used to remember per-user dismissal of the homepage notice.
 */
const IFENDE_HOMEPAGE_NOTICE_USER_META = 'ifende_homepage_notice_dismissed';

/**
 * Is the current request a context where we should consider rendering
 * the homepage migration notice? Centralised so the display gate and the
 * AJAX dismissal endpoint share the same definition.
 *
 * @return bool
 */
function ifende_homepage_notice_should_consider() {
	if ( ! function_exists( 'is_admin' ) || ! is_admin() ) {
		return false;
	}
	if ( ! current_user_can( 'edit_pages' ) ) {
		return false;
	}
	return true;
}

/**
 * Render the migration notice on the Pages list screen.
 *
 * Hooked on admin_notices. Each early-return covers a specific reason
 * not to render — comments document why, so the cumulative gate is
 * easy to review.
 */
function ifende_homepage_migration_notice() {
	if ( ! ifende_homepage_notice_should_consider() ) {
		return;
	}

	// Only on the Pages list screen — the notice is teaching the user how
	// to wire up a Page as the homepage, so it's most discoverable there.
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'edit-page' !== $screen->id ) {
		return;
	}

	// Already migrated to a static front page? Nothing to teach.
	if (
		'page' === get_option( 'show_on_front' )
		&& (int) get_option( 'page_on_front' ) > 0
	) {
		return;
	}

	// Per-user dismissal.
	if ( get_user_meta( get_current_user_id(), IFENDE_HOMEPAGE_NOTICE_USER_META, true ) ) {
		return;
	}

	$reading_url  = admin_url( 'options-reading.php' );
	$new_page_url = admin_url( 'post-new.php?post_type=page' );
	$nonce        = wp_create_nonce( 'ifende_dismiss_homepage_notice' );
	?>
	<div class="notice notice-info is-dismissible" data-ifende-notice="homepage-migration">
		<p style="font-size:14px;font-weight:600;margin:12px 0 4px;">
			<?php esc_html_e( 'Want to edit your homepage with the block editor or Elementor?', 'ifende' ); ?>
		</p>
		<p style="margin:4px 0;">
			<?php esc_html_e( "By default, Ifende's homepage is rendered from your Customizer settings (Hero, About, Services, Testimonials, FAQ, etc.) using the section template parts. To compose it visually with the block editor or Elementor instead:", 'ifende' ); ?>
		</p>
		<ol style="margin:0 0 12px 28px;">
			<li>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: URL of the "Add New Page" admin screen */
						__( '<a href="%s">Add a new page</a> &mdash; e.g. titled "Home" or "Landing".', 'ifende' ),
						esc_url( $new_page_url )
					)
				);
				?>
			</li>
			<li>
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: URL of the Settings → Reading admin screen */
						__( 'Open <a href="%s">Settings &rarr; Reading</a>, set "Your homepage displays" to <strong>A static page</strong>, and pick the page you just created.', 'ifende' ),
						esc_url( $reading_url )
					)
				);
				?>
			</li>
			<li>
				<?php esc_html_e( 'Edit that page. Open the inserter (+), filter to the "Ifende" pattern category, and drop in pre-built sections (Hero, Services, Testimonials, etc.). Or click "Edit with Elementor" if you prefer that editor.', 'ifende' ); ?>
			</li>
		</ol>
		<p style="font-size:12px;color:#646970;margin:0 0 12px;">
			<?php esc_html_e( 'Existing Customizer-driven content keeps working until you change the Reading setting — nothing breaks in the meantime.', 'ifende' ); ?>
		</p>
	</div>
	<script>
	(function () {
		var notice = document.querySelector('[data-ifende-notice="homepage-migration"]');
		if (!notice) return;
		notice.addEventListener('click', function (e) {
			if (!e.target || !e.target.classList || !e.target.classList.contains('notice-dismiss')) {
				return;
			}
			var data = new FormData();
			data.append('action', 'ifende_dismiss_homepage_notice');
			data.append('_wpnonce', <?php echo wp_json_encode( $nonce ); ?>);
			fetch(ajaxurl, { method: 'POST', body: data, credentials: 'same-origin' });
		});
	})();
	</script>
	<?php
}
add_action( 'admin_notices', 'ifende_homepage_migration_notice' );

/**
 * AJAX handler — record per-user dismissal.
 */
function ifende_dismiss_homepage_notice() {
	check_ajax_referer( 'ifende_dismiss_homepage_notice' );
	if ( ! ifende_homepage_notice_should_consider() ) {
		wp_send_json_error( [ 'message' => __( 'Permission denied.', 'ifende' ) ], 403 );
	}
	update_user_meta( get_current_user_id(), IFENDE_HOMEPAGE_NOTICE_USER_META, 1 );
	wp_send_json_success();
}
add_action( 'wp_ajax_ifende_dismiss_homepage_notice', 'ifende_dismiss_homepage_notice' );


/* ============================================================================
 * Homepage pre-fill — one-click migration from Customizer/CPTs into the
 * static front page's post_content.
 *
 * After the user wires up a static front page (via the notice above) they
 * land on an empty Page in the editor. The helper below offers a single
 * button that captures the live homepage section template parts (each
 * already populated from the user's Customizer settings + CPT entries)
 * and writes the result into the Page as a sequence of wp:html blocks.
 *
 * Result: the Home page comes pre-filled with the user's actual content,
 * each section editable as its own block. Same homepage they had before,
 * now visually composable.
 *
 * Only offered when the page is empty so we never destroy work the user
 * already did in the editor.
 * ========================================================================= */

/**
 * Sections rendered into the pre-fill, in front-end display order. Mirrors
 * the get_template_part() sequence in index.php so the pre-filled page
 * matches the live Customizer-driven homepage exactly.
 *
 * @return string[]
 */
function ifende_homepage_prefill_sections() {
	/**
	 * Filter the list of section template parts captured during pre-fill.
	 *
	 * Each value is the suffix passed to get_template_part('template-parts/section', $value).
	 * Reorder, add, or remove entries to customise the pre-filled output.
	 *
	 * @since 1.6.0
	 *
	 * @param string[] $sections Default: index.php's section order.
	 */
	return apply_filters(
		'ifende_homepage_prefill_sections',
		[ 'hero', 'marquee', 'about', 'services', 'portfolio', 'clients', 'testimonials', 'blog', 'faq', 'newsletter', 'contact' ]
	);
}

/**
 * Determine whether the current edit screen is the static front page AND
 * the page is currently empty (no post_content). Both must hold for the
 * pre-fill notice to render.
 *
 * @return int|false Static front page post ID when applicable, otherwise false.
 */
function ifende_homepage_prefill_target_id() {
	if ( ! ifende_homepage_notice_should_consider() ) {
		return false;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'post' !== $screen->base ) {
		return false;
	}

	$front_page_id = (int) get_option( 'page_on_front' );
	if ( ! $front_page_id || 'page' !== get_option( 'show_on_front' ) ) {
		return false;
	}

	global $post;
	if ( ! $post || (int) $post->ID !== $front_page_id ) {
		return false;
	}

	if ( '' !== trim( (string) $post->post_content ) ) {
		// Page already has content — never overwrite.
		return false;
	}

	return $front_page_id;
}

/**
 * Render the pre-fill notice on the static front page's edit screen.
 */
function ifende_homepage_prefill_notice() {
	$page_id = ifende_homepage_prefill_target_id();
	if ( ! $page_id ) {
		return;
	}

	$action_url = admin_url( 'admin-post.php' );
	?>
	<div class="notice notice-info">
		<p style="font-size:14px;font-weight:600;margin:12px 0 4px;">
			<?php esc_html_e( 'Pre-fill this page with your existing homepage content?', 'ifende' ); ?>
		</p>
		<p style="margin:4px 0 12px;">
			<?php esc_html_e( 'This page is empty. Click below to drop in the eleven homepage sections (Hero, Marquee, About, Services, Portfolio, Clients, Testimonials, Blog, FAQ, Newsletter, Contact) populated from your current Customizer settings and CPT entries. Each section becomes a block you can edit visually. Sections you don\'t want can be deleted afterwards.', 'ifende' ); ?>
		</p>
		<form method="post" action="<?php echo esc_url( $action_url ); ?>" style="margin:0 0 12px;">
			<?php wp_nonce_field( 'ifende_prefill_homepage', '_ifende_prefill_nonce' ); ?>
			<input type="hidden" name="action" value="ifende_prefill_homepage">
			<input type="hidden" name="page_id" value="<?php echo esc_attr( (string) $page_id ); ?>">
			<button type="submit" class="button button-primary">
				<?php esc_html_e( 'Pre-fill from Customizer', 'ifende' ); ?>
			</button>
		</form>
		<p style="font-size:12px;color:#646970;margin:0 0 12px;">
			<?php esc_html_e( "Tip: only shown while this page is empty. If you'd rather start from scratch, use the inserter (+) and pick patterns from the \"Ifende\" category instead.", 'ifende' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'ifende_homepage_prefill_notice' );

/**
 * Render the success notice after a pre-fill, triggered by a query arg
 * appended on the post-prefill redirect.
 */
function ifende_homepage_prefill_success_notice() {
	if ( ! isset( $_GET['ifende_prefilled'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- read-only flag from our own redirect; no state mutation here.
		return;
	}
	$flag = sanitize_key( wp_unslash( $_GET['ifende_prefilled'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- same.
	if ( '1' !== $flag ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible">
		<p>
			<?php esc_html_e( 'Homepage pre-filled with your existing Customizer content. Each section is now an editable block below.', 'ifende' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'ifende_homepage_prefill_success_notice' );

/**
 * Capture one section template part as wp:html block markup.
 *
 * The wp:html wrapper round-trips through the block parser unchanged, so
 * each captured section becomes a single editable block in the editor.
 *
 * @param string $section Template part suffix (e.g. 'hero', 'services').
 * @return string Block markup, or empty string on failure / empty capture.
 */
function ifende_homepage_prefill_capture_section( $section ) {
	ob_start();
	get_template_part( 'template-parts/section', $section );
	$html = trim( (string) ob_get_clean() );

	if ( '' === $html ) {
		return '';
	}

	return "<!-- wp:html -->\n" . $html . "\n<!-- /wp:html -->";
}

/**
 * Build the full pre-fill block content for the static front page.
 *
 * Walks ifende_homepage_prefill_sections() in order, captures each section
 * template part with the user's live Customizer + CPT data, and concatenates
 * the wp:html blocks with a blank line between each.
 *
 * @return string Block markup ready for wp_update_post.
 */
function ifende_homepage_prefill_build_content() {
	$blocks = [];
	foreach ( ifende_homepage_prefill_sections() as $section ) {
		$block = ifende_homepage_prefill_capture_section( $section );
		if ( '' !== $block ) {
			$blocks[] = $block;
		}
	}
	return implode( "\n\n", $blocks );
}

/**
 * Form handler for the pre-fill button. Captures the section template
 * parts, writes them into the static front page's post_content, and
 * redirects back to the editor with a success flag.
 *
 * Hooked to admin_post_ifende_prefill_homepage so it fires before any
 * output, allowing a clean wp_safe_redirect.
 */
function ifende_homepage_prefill_handle() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'ifende' ), '', [ 'response' => 403 ] );
	}
	check_admin_referer( 'ifende_prefill_homepage', '_ifende_prefill_nonce' );

	$page_id = isset( $_POST['page_id'] ) ? absint( wp_unslash( $_POST['page_id'] ) ) : 0;
	if ( ! $page_id ) {
		wp_die( esc_html__( 'Missing target page.', 'ifende' ), '', [ 'response' => 400 ] );
	}

	// Only operate on a static front page that's actually configured as such,
	// and only when it's empty. Two checks defend against stale form posts
	// where the user reconfigured Settings -> Reading or pasted content
	// between rendering the notice and submitting it.
	$front_page_id = (int) get_option( 'page_on_front' );
	if (
		'page' !== get_option( 'show_on_front' )
		|| $front_page_id !== $page_id
	) {
		wp_die( esc_html__( 'This page is no longer the static front page.', 'ifende' ), '', [ 'response' => 400 ] );
	}

	$post = get_post( $page_id );
	if ( ! $post || 'page' !== $post->post_type ) {
		wp_die( esc_html__( 'Target is not a page.', 'ifende' ), '', [ 'response' => 400 ] );
	}
	if ( '' !== trim( (string) $post->post_content ) ) {
		wp_die( esc_html__( 'Page already has content; pre-fill skipped to avoid overwriting your work.', 'ifende' ), '', [ 'response' => 409 ] );
	}

	$content = ifende_homepage_prefill_build_content();

	// Bypass kses while saving so the captured HTML (which we authored, not
	// untrusted user input) survives intact even when the saving user lacks
	// the unfiltered_html capability. Restored immediately after the update.
	$kses_was_active = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
	if ( $kses_was_active ) {
		kses_remove_filters();
	}

	$result = wp_update_post(
		[
			'ID'           => $page_id,
			'post_content' => $content,
		],
		true
	);

	if ( $kses_was_active ) {
		kses_init_filters();
	}

	if ( is_wp_error( $result ) ) {
		wp_die( esc_html( $result->get_error_message() ), '', [ 'response' => 500 ] );
	}

	$redirect_url = add_query_arg(
		'ifende_prefilled',
		'1',
		get_edit_post_link( $page_id, 'url' )
	);
	wp_safe_redirect( $redirect_url );
	exit;
}
add_action( 'admin_post_ifende_prefill_homepage', 'ifende_homepage_prefill_handle' );
