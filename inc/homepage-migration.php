<?php
/**
 * Homepage Migration — Notices + Rebuild from Customizer.
 *
 * Provides:
 *   1. A one-time admin notice on the Pages list explaining the static-
 *      front-page workflow (shown only until Reading is configured).
 *   2. A "Pre-fill from Customizer" button when the Home page is empty.
 *   3. A "Rebuild from Customizer" button when the Home page already has
 *      content — re-captures all section template parts with the current
 *      Customizer values and CPT entries, overwrites post_content.
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
 */
function ifende_homepage_migration_notice() {
	if ( ! ifende_homepage_notice_should_consider() ) {
		return;
	}

	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || 'edit-page' !== $screen->id ) {
		return;
	}

	if (
		'page' === get_option( 'show_on_front' )
		&& (int) get_option( 'page_on_front' ) > 0
	) {
		return;
	}

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
 * Homepage rebuild — captures Customizer + CPT content into the static
 * front page's post_content. Works whether the page is empty (first-time
 * setup) OR already has content (subsequent rebuilds after updating
 * Customizer fields).
 *
 * Workflow for the user:
 *   1. Appearance → Customize → Ifende Portfolio Options → fill in name,
 *      photo, bio, services, etc.
 *   2. Pages → Home → Edit → click "Rebuild from Customizer" → confirm.
 *   3. Page rebuilds with the latest Customizer + CPT values. Done.
 * ========================================================================= */

/**
 * Sections rendered into the rebuild, in front-end display order.
 *
 * @return string[]
 */
function ifende_homepage_prefill_sections() {
	/**
	 * Filter the list of section template parts captured during rebuild.
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
 * Determine whether the current edit screen is the static front page.
 *
 * @return int|false Static front page post ID when applicable, otherwise false.
 */
function ifende_homepage_is_front_page_editor() {
	if ( ! ifende_homepage_notice_should_consider() ) {
		return false;
	}

	// Check if we're on the post editor screen. Use multiple detection
	// methods because get_current_screen() can return null in some WP
	// versions during early admin_notices, and the block editor iframes
	// may behave differently.
	$on_editor = false;

	// Method 1: get_current_screen().
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( $screen && 'post' === $screen->base ) {
		$on_editor = true;
	}

	// Method 2: Check $_GET['action'] + $_GET['post'] (classic editor URL params).
	if ( ! $on_editor ) {
		$action = isset( $_GET['action'] ) ? sanitize_key( $_GET['action'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( 'edit' === $action && isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$on_editor = true;
		}
	}

	// Method 3: Check global $pagenow.
	if ( ! $on_editor ) {
		global $pagenow;
		if ( 'post.php' === $pagenow || 'post-new.php' === $pagenow ) {
			$on_editor = true;
		}
	}

	if ( ! $on_editor ) {
		return false;
	}

	$front_page_id = (int) get_option( 'page_on_front' );
	if ( ! $front_page_id || 'page' !== get_option( 'show_on_front' ) ) {
		return false;
	}

	// Determine the post ID being edited.
	$editing_id = 0;
	global $post;
	if ( $post && $post->ID ) {
		$editing_id = (int) $post->ID;
	} elseif ( isset( $_GET['post'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$editing_id = absint( $_GET['post'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	if ( $editing_id !== $front_page_id ) {
		return false;
	}

	return $front_page_id;
}

/**
 * Render the rebuild/pre-fill notice on the static front page's edit screen.
 *
 * Shows "Pre-fill" when the page is empty (first time), or "Rebuild" when
 * the page already has content (subsequent updates).
 */
function ifende_homepage_rebuild_notice() {
	$page_id = ifende_homepage_is_front_page_editor();
	if ( ! $page_id ) {
		return;
	}

	global $post;
	$is_empty   = '' === trim( (string) $post->post_content );
	$action_url = admin_url( 'admin-post.php' );

	if ( $is_empty ) {
		// First-time pre-fill (page is empty).
		?>
		<div class="notice notice-info">
			<p style="font-size:14px;font-weight:600;margin:12px 0 4px;">
				<?php esc_html_e( 'Pre-fill this page with your existing homepage content?', 'ifende' ); ?>
			</p>
			<p style="margin:4px 0 12px;">
				<?php esc_html_e( 'This page is empty. Click below to populate it with the eleven homepage sections from your Customizer settings and CPT entries. Each section becomes a block you can edit visually.', 'ifende' ); ?>
			</p>
			<form method="post" action="<?php echo esc_url( $action_url ); ?>" style="margin:0 0 12px;">
				<?php wp_nonce_field( 'ifende_rebuild_homepage', '_ifende_rebuild_nonce' ); ?>
				<input type="hidden" name="action" value="ifende_rebuild_homepage">
				<input type="hidden" name="page_id" value="<?php echo esc_attr( (string) $page_id ); ?>">
				<button type="submit" class="button button-primary">
					<?php esc_html_e( 'Pre-fill from Customizer', 'ifende' ); ?>
				</button>
			</form>
			<p style="font-size:12px;color:#646970;margin:0 0 12px;">
				<?php esc_html_e( "Or use the inserter (+) → Patterns → Ifende to add sections manually.", 'ifende' ); ?>
			</p>
		</div>
		<?php
	} else {
		// Page already has content — offer rebuild with confirmation.
		$customize_url = admin_url( 'customize.php?autofocus[panel]=ifende_panel' );
		?>
		<div class="notice notice-warning" style="border-left-color:#21A14E;">
			<p style="font-size:14px;font-weight:600;margin:12px 0 4px;">
				<?php esc_html_e( 'Rebuild homepage from Customizer?', 'ifende' ); ?>
			</p>
			<p style="margin:4px 0 12px;">
				<?php
				echo wp_kses_post(
					sprintf(
						/* translators: %s: URL to Customizer */
						__( 'Update your content in <a href="%s">Appearance → Customize → Ifende Portfolio Options</a> (name, photo, bio, services, etc.), then click the button below to rebuild this page with the latest values. <strong>This replaces all current page content.</strong>', 'ifende' ),
						esc_url( $customize_url )
					)
				);
				?>
			</p>
			<form method="post" action="<?php echo esc_url( $action_url ); ?>" style="margin:0 0 12px;" onsubmit="return confirm('<?php echo esc_js( __( 'This will replace ALL content on this page with a fresh build from your Customizer settings. Any manual edits you made in the editor will be lost. Continue?', 'ifende' ) ); ?>');">
				<?php wp_nonce_field( 'ifende_rebuild_homepage', '_ifende_rebuild_nonce' ); ?>
				<input type="hidden" name="action" value="ifende_rebuild_homepage">
				<input type="hidden" name="page_id" value="<?php echo esc_attr( (string) $page_id ); ?>">
				<button type="submit" class="button button-secondary" style="border-color:#21A14E;color:#21A14E;">
					<?php esc_html_e( 'Rebuild from Customizer', 'ifende' ); ?>
				</button>
			</form>
			<p style="font-size:12px;color:#646970;margin:0 0 12px;">
				<?php esc_html_e( 'Tip: edit name, photo, bio, services, clients, testimonials, FAQs in Customize first, then rebuild here. One click, no HTML editing.', 'ifende' ); ?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'ifende_homepage_rebuild_notice' );

/**
 * Render the success notice after a rebuild.
 */
function ifende_homepage_rebuild_success_notice() {
	if ( ! isset( $_GET['ifende_rebuilt'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		return;
	}
	$flag = sanitize_key( wp_unslash( $_GET['ifende_rebuilt'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( '1' !== $flag ) {
		return;
	}
	?>
	<div class="notice notice-success is-dismissible">
		<p>
			<?php esc_html_e( 'Homepage rebuilt from Customizer. Each section is now an editable block below. Refresh the front-end to see changes.', 'ifende' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'admin_notices', 'ifende_homepage_rebuild_success_notice' );

/**
 * Capture one section template part as wp:html block markup.
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
 * Build the full block content for the static front page.
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
 * Form handler — rebuild/pre-fill the static front page from Customizer.
 *
 * Works whether the page is empty (first-time) or already has content
 * (subsequent rebuild). The confirmation JS on the form prevents
 * accidental overwrites; the server-side still processes either case.
 */
function ifende_rebuild_homepage_handle() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		wp_die( esc_html__( 'Permission denied.', 'ifende' ), '', [ 'response' => 403 ] );
	}
	check_admin_referer( 'ifende_rebuild_homepage', '_ifende_rebuild_nonce' );

	$page_id = isset( $_POST['page_id'] ) ? absint( wp_unslash( $_POST['page_id'] ) ) : 0;
	if ( ! $page_id ) {
		wp_die( esc_html__( 'Missing target page.', 'ifende' ), '', [ 'response' => 400 ] );
	}

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

	$content = ifende_homepage_prefill_build_content();

	// Bypass kses so theme-authored HTML survives intact.
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
		'ifende_rebuilt',
		'1',
		get_edit_post_link( $page_id, 'url' )
	);
	wp_safe_redirect( $redirect_url );
	exit;
}
add_action( 'admin_post_ifende_rebuild_homepage', 'ifende_rebuild_homepage_handle' );

// Keep the old action name as an alias so existing forms (PR #42) still work.
add_action( 'admin_post_ifende_prefill_homepage', 'ifende_rebuild_homepage_handle' );
