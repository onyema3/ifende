<?php
/**
 * Admin Dashboard Widget — Theme status, quick links, and changelog.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the dashboard widget.
 */
function ifende_dashboard_widget() {
	wp_add_dashboard_widget(
		'ifende_dashboard_widget',
		esc_html__( 'Ifende Theme', 'ifende' ),
		'ifende_dashboard_widget_render'
	);
}
add_action( 'wp_dashboard_setup', 'ifende_dashboard_widget' );

/**
 * Render the dashboard widget content.
 */
function ifende_dashboard_widget_render() {
	$theme   = wp_get_theme();
	$version = $theme->get( 'Version' );
	$name    = $theme->get( 'Name' );

	// Count CPT entries.
	$projects     = wp_count_posts( 'ifende_project' );
	$services     = wp_count_posts( 'ifende_service' );
	$testimonials = wp_count_posts( 'ifende_testimonial' );
	$clients      = wp_count_posts( 'ifende_client' );

	$project_count     = isset( $projects->publish ) ? $projects->publish : 0;
	$service_count     = isset( $services->publish ) ? $services->publish : 0;
	$testimonial_count = isset( $testimonials->publish ) ? $testimonials->publish : 0;
	$client_count      = isset( $clients->publish ) ? $clients->publish : 0;

	$maintenance = get_theme_mod( 'ifende_maintenance_enabled', false );
	?>
	<div style="margin:-12px -12px 0;padding:16px 16px 12px;background:#0A0A0A;border-radius:4px 4px 0 0;border-bottom:2px solid #21A14E;">
		<div style="display:flex;justify-content:space-between;align-items:center;">
			<span style="font-size:1.1rem;font-weight:600;color:#F5F2EC;"><?php echo esc_html( $name ); ?><span style="color:#21A14E;">.</span></span>
			<span style="font-size:0.7rem;background:rgba(33,161,78,0.15);color:#21A14E;padding:3px 8px;border-radius:2px;">v<?php echo esc_html( $version ); ?></span>
		</div>
	</div>

	<?php if ( $maintenance ) : ?>
		<div style="margin:12px 0;padding:8px 12px;background:#fff3cd;border-left:3px solid #ffc107;border-radius:2px;font-size:0.82rem;">
			&#9888; <?php esc_html_e( 'Maintenance Mode is ON', 'ifende' ); ?> — <a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=ifende_maintenance' ) ); ?>"><?php esc_html_e( 'Disable', 'ifende' ); ?></a>
		</div>
	<?php endif; ?>

	<div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin:12px 0;">
		<div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:4px;">
			<div style="font-size:1.4rem;font-weight:700;color:#21A14E;"><?php echo esc_html( $project_count ); ?></div>
			<div style="font-size:0.7rem;color:#666;text-transform:uppercase;letter-spacing:1px;"><?php esc_html_e( 'Projects', 'ifende' ); ?></div>
		</div>
		<div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:4px;">
			<div style="font-size:1.4rem;font-weight:700;color:#21A14E;"><?php echo esc_html( $testimonial_count ); ?></div>
			<div style="font-size:0.7rem;color:#666;text-transform:uppercase;letter-spacing:1px;"><?php esc_html_e( 'Testimonials', 'ifende' ); ?></div>
		</div>
		<div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:4px;">
			<div style="font-size:1.4rem;font-weight:700;color:#21A14E;"><?php echo esc_html( $service_count ); ?></div>
			<div style="font-size:0.7rem;color:#666;text-transform:uppercase;letter-spacing:1px;"><?php esc_html_e( 'Services', 'ifende' ); ?></div>
		</div>
		<div style="text-align:center;padding:12px;background:#f8f9fa;border-radius:4px;">
			<div style="font-size:1.4rem;font-weight:700;color:#21A14E;"><?php echo esc_html( $client_count ); ?></div>
			<div style="font-size:0.7rem;color:#666;text-transform:uppercase;letter-spacing:1px;"><?php esc_html_e( 'Clients', 'ifende' ); ?></div>
		</div>
	</div>

	<h4 style="margin:16px 0 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;color:#666;"><?php esc_html_e( 'Quick Links', 'ifende' ); ?></h4>
	<ul style="margin:0;padding:0;list-style:none;">
		<li style="padding:4px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>"><?php esc_html_e( 'Customize Theme', 'ifende' ); ?></a></li>
		<li style="padding:4px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ifende_project' ) ); ?>"><?php esc_html_e( 'Manage Projects', 'ifende' ); ?></a></li>
		<li style="padding:4px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( admin_url( 'themes.php?page=ifende-export-import' ) ); ?>"><?php esc_html_e( 'Import / Export Settings', 'ifende' ); ?></a></li>
		<li style="padding:4px 0;border-bottom:1px solid #eee;"><a href="<?php echo esc_url( admin_url( 'edit.php?post_type=ifende_testimonial&page=ifende-testimonial-request' ) ); ?>"><?php esc_html_e( 'Request Testimonial', 'ifende' ); ?></a></li>
		<li style="padding:4px 0;"><a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=ifende_livechat' ) ); ?>"><?php esc_html_e( 'Live Chat Settings', 'ifende' ); ?></a></li>
	</ul>

	<h4 style="margin:16px 0 8px;font-size:0.75rem;text-transform:uppercase;letter-spacing:1px;color:#666;"><?php esc_html_e( 'Recent Changes', 'ifende' ); ?></h4>
	<ul style="margin:0;padding:0;list-style:none;font-size:0.82rem;color:#555;">
		<li style="padding:3px 0;">&#10003; <?php esc_html_e( 'Page transitions, parallax, typed text, lightbox', 'ifende' ); ?></li>
		<li style="padding:3px 0;">&#10003; <?php esc_html_e( 'Exit-intent popup, action bar, project inquiry', 'ifende' ); ?></li>
		<li style="padding:3px 0;">&#10003; <?php esc_html_e( 'TOC, breadcrumbs, related projects, author box', 'ifende' ); ?></li>
		<li style="padding:3px 0;">&#10003; <?php esc_html_e( 'Debug panel, CSP headers, login branding', 'ifende' ); ?></li>
	</ul>
	<?php
}
