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
