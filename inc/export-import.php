<?php
/**
 * Import/Export — One-click backup and restore of all Customizer settings.
 *
 * Adds an admin page under Appearance for exporting theme_mods as JSON
 * and importing them back. Useful for migrating between staging/production.
 *
 * @package Ifende
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Import/Export admin page.
 */
function ifende_export_import_menu() {
	add_theme_page(
		esc_html__( 'Import/Export Settings', 'ifende' ),
		esc_html__( 'Import/Export', 'ifende' ),
		'manage_options',
		'ifende-export-import',
		'ifende_export_import_page'
	);
}
add_action( 'admin_menu', 'ifende_export_import_menu' );

/**
 * Handle export and import actions.
 */
function ifende_export_import_handler() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// EXPORT.
	if ( isset( $_POST['ifende_export'] ) && check_admin_referer( 'ifende_export_nonce', 'ifende_export_nonce_field' ) ) {
		$theme_mods = get_theme_mods();

		// Remove non-serializable items.
		unset( $theme_mods['nav_menu_locations'] );
		unset( $theme_mods['custom_css_post_id'] );
		unset( $theme_mods['sidebars_widgets'] );

		$json = wp_json_encode( $theme_mods, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES );

		header( 'Content-Type: application/json' );
		header( 'Content-Disposition: attachment; filename="ifende-settings-' . gmdate( 'Y-m-d' ) . '.json"' );
		header( 'Content-Length: ' . strlen( $json ) );
		echo $json;
		exit;
	}

	// IMPORT.
	if ( isset( $_POST['ifende_import'] ) && check_admin_referer( 'ifende_import_nonce', 'ifende_import_nonce_field' ) ) {
		if ( empty( $_FILES['ifende_import_file']['tmp_name'] ) ) {
			add_settings_error( 'ifende_import', 'no_file', esc_html__( 'Please select a JSON file to import.', 'ifende' ), 'error' );
			return;
		}

		$file = $_FILES['ifende_import_file']['tmp_name'];

		// Validate file type.
		$file_info = wp_check_filetype( $_FILES['ifende_import_file']['name'] );
		if ( 'json' !== $file_info['ext'] ) {
			add_settings_error( 'ifende_import', 'invalid_file', esc_html__( 'Invalid file type. Please upload a .json file.', 'ifende' ), 'error' );
			return;
		}

		// Read and parse.
		$contents = file_get_contents( $file ); // phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$settings = json_decode( $contents, true );

		if ( ! is_array( $settings ) || json_last_error() !== JSON_ERROR_NONE ) {
			add_settings_error( 'ifende_import', 'invalid_json', esc_html__( 'The file contains invalid JSON. Please check the file and try again.', 'ifende' ), 'error' );
			return;
		}

		// Apply each setting.
		foreach ( $settings as $key => $value ) {
			set_theme_mod( $key, $value );
		}

		add_settings_error( 'ifende_import', 'success', esc_html__( 'Settings imported successfully!', 'ifende' ), 'success' );
	}
}
add_action( 'admin_init', 'ifende_export_import_handler' );

/**
 * Render the Import/Export admin page.
 */
function ifende_export_import_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Ifende — Import/Export Settings', 'ifende' ); ?></h1>
		<p><?php esc_html_e( 'Export your theme Customizer settings as a JSON file, or import a previously exported file to restore settings.', 'ifende' ); ?></p>

		<?php settings_errors( 'ifende_import' ); ?>

		<div style="display:grid;grid-template-columns:1fr 1fr;gap:32px;margin-top:24px;">

			<!-- EXPORT -->
			<div style="background:#fff;padding:24px;border:1px solid #ccd0d4;border-radius:4px;">
				<h2 style="margin-top:0;"><?php esc_html_e( 'Export Settings', 'ifende' ); ?></h2>
				<p><?php esc_html_e( 'Download all your current Customizer settings as a JSON file. Use this to back up your configuration or migrate to another site.', 'ifende' ); ?></p>
				<form method="post">
					<?php wp_nonce_field( 'ifende_export_nonce', 'ifende_export_nonce_field' ); ?>
					<p>
						<button type="submit" name="ifende_export" class="button button-primary">
							<?php esc_html_e( 'Download Settings (.json)', 'ifende' ); ?>
						</button>
					</p>
				</form>
				<p class="description"><?php esc_html_e( 'This includes all Ifende theme options: hero, about, services, clients, testimonials, FAQ, newsletter, analytics, GDPR, live chat, booking, and maintenance settings.', 'ifende' ); ?></p>
			</div>

			<!-- IMPORT -->
			<div style="background:#fff;padding:24px;border:1px solid #ccd0d4;border-radius:4px;">
				<h2 style="margin-top:0;"><?php esc_html_e( 'Import Settings', 'ifende' ); ?></h2>
				<p><?php esc_html_e( 'Upload a previously exported JSON file to restore your Customizer settings. This will overwrite your current settings.', 'ifende' ); ?></p>
				<form method="post" enctype="multipart/form-data">
					<?php wp_nonce_field( 'ifende_import_nonce', 'ifende_import_nonce_field' ); ?>
					<p>
						<input type="file" name="ifende_import_file" accept=".json" required>
					</p>
					<p>
						<button type="submit" name="ifende_import" class="button button-secondary" onclick="return confirm('<?php echo esc_js( __( 'This will overwrite your current settings. Continue?', 'ifende' ) ); ?>');">
							<?php esc_html_e( 'Upload & Import', 'ifende' ); ?>
						</button>
					</p>
				</form>
				<p class="description" style="color:#d63638;"><?php esc_html_e( 'Warning: Importing will replace all current theme settings. Consider exporting first as a backup.', 'ifende' ); ?></p>
			</div>

		</div>

		<!-- RESET -->
		<div style="margin-top:32px;background:#fff;padding:24px;border:1px solid #ccd0d4;border-radius:4px;">
			<h2 style="margin-top:0;"><?php esc_html_e( 'What Gets Exported', 'ifende' ); ?></h2>
			<p><?php esc_html_e( 'The export includes all theme_mod settings for the Ifende theme:', 'ifende' ); ?></p>
			<ul style="list-style:disc;padding-left:20px;columns:2;gap:32px;">
				<li><?php esc_html_e( 'Hero section (name, roles, bio, stats, photo)', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'About section (bio, skills, social links)', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Services (icons, titles, descriptions)', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Clients list', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Testimonials', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'FAQ items', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Marquee/scrolling text', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Newsletter configuration', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Contact form settings', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Analytics & tracking IDs', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'GDPR/cookie consent settings', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Live chat provider & IDs', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Booking provider settings', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Maintenance mode settings', 'ifende' ); ?></li>
				<li><?php esc_html_e( 'Custom logo & site identity', 'ifende' ); ?></li>
			</ul>
			<p class="description" style="margin-top:16px;"><?php esc_html_e( 'Note: CPT entries (Projects, Services, Clients, Testimonials, FAQs) are stored as posts and are not included in this export. Use WordPress built-in Tools > Export for those.', 'ifende' ); ?></p>
		</div>
	</div>
	<?php
}
