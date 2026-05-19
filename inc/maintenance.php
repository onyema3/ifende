<?php
/**
 * Maintenance / Coming Soon Mode.
 *
 * Displays a branded maintenance page when enabled via Customizer.
 * Logged-in admins can still view the site normally.
 *
 * @package Ifende
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Maintenance Mode Customizer settings.
 */
function ifende_maintenance_customizer( $wp_customize ) {
	$wp_customize->add_section( 'ifende_maintenance', [
		'title'       => esc_html__( 'Maintenance Mode', 'ifende' ),
		'panel'       => 'ifende_panel',
		'description' => esc_html__( 'Show a coming soon / maintenance page to visitors while you work on the site.', 'ifende' ),
	] );

	$wp_customize->add_setting( 'ifende_maintenance_enabled', [
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'ifende_maintenance_enabled', [
		'label'   => esc_html__( 'Enable Maintenance Mode', 'ifende' ),
		'section' => 'ifende_maintenance',
		'type'    => 'checkbox',
	] );

	$wp_customize->add_setting( 'ifende_maintenance_headline', [
		'default'           => 'Coming Soon',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_maintenance_headline', [
		'label'   => esc_html__( 'Headline', 'ifende' ),
		'section' => 'ifende_maintenance',
		'type'    => 'text',
	] );

	$wp_customize->add_setting( 'ifende_maintenance_message', [
		'default'           => 'We are working on something amazing. Check back soon!',
		'sanitize_callback' => 'sanitize_textarea_field',
	] );
	$wp_customize->add_control( 'ifende_maintenance_message', [
		'label'   => esc_html__( 'Message', 'ifende' ),
		'section' => 'ifende_maintenance',
		'type'    => 'textarea',
	] );
}
add_action( 'customize_register', 'ifende_maintenance_customizer' );

/**
 * Intercept page load and show maintenance page if enabled.
 */
function ifende_maintenance_mode() {
	if ( ! get_theme_mod( 'ifende_maintenance_enabled', false ) ) {
		return;
	}

	// Allow logged-in admins to bypass.
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}

	// Allow wp-login and admin AJAX to work.
	if ( is_admin() || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) || strpos( $_SERVER['REQUEST_URI'], 'wp-login' ) !== false ) {
		return;
	}

	$headline = get_theme_mod( 'ifende_maintenance_headline', 'Coming Soon' );
	$message  = get_theme_mod( 'ifende_maintenance_message', 'We are working on something amazing. Check back soon!' );

	// Return 503 for SEO (tells search engines to come back later).
	status_header( 503 );
	header( 'Retry-After: 3600' );
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="robots" content="noindex, nofollow">
		<title><?php echo esc_html( $headline . ' — ' . get_bloginfo( 'name' ) ); ?></title>
		<style>
			:root{--black:#0A0A0A;--white:#F5F2EC;--green:#21A14E;--grey:#8A8A8A;}
			*{margin:0;padding:0;box-sizing:border-box;}
			body{font-family:'Segoe UI',system-ui,-apple-system,sans-serif;background:var(--black);color:var(--white);min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px;}
			.maintenance-wrap{max-width:520px;}
			.maintenance-dot{width:12px;height:12px;border-radius:50%;background:var(--green);margin:0 auto 32px;animation:pulse 2s infinite;}
			@keyframes pulse{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(1.3)}}
			h1{font-size:clamp(2rem,5vw,3.5rem);font-weight:300;line-height:1.2;margin-bottom:16px;}
			p{font-size:1rem;line-height:1.8;color:var(--grey);margin-bottom:32px;}
			.maintenance-login{font-size:.75rem;letter-spacing:2px;text-transform:uppercase;color:var(--grey);text-decoration:none;border:1px solid rgba(245,242,236,.12);padding:10px 20px;border-radius:2px;transition:all .2s;}
			.maintenance-login:hover{border-color:var(--green);color:var(--green);}
		</style>
	</head>
	<body>
		<div class="maintenance-wrap">
			<div class="maintenance-dot"></div>
			<h1><?php echo esc_html( $headline ); ?></h1>
			<p><?php echo esc_html( $message ); ?></p>
			<a href="<?php echo esc_url( wp_login_url() ); ?>" class="maintenance-login"><?php esc_html_e( 'Admin Login', 'ifende' ); ?></a>
		</div>
	</body>
	</html>
	<?php
	exit;
}
add_action( 'template_redirect', 'ifende_maintenance_mode' );

/**
 * Show admin bar notice when maintenance mode is active.
 */
function ifende_maintenance_admin_notice() {
	if ( get_theme_mod( 'ifende_maintenance_enabled', false ) && current_user_can( 'manage_options' ) ) {
		echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'Maintenance Mode is ON.', 'ifende' ) . '</strong> ' . esc_html__( 'Visitors see the coming soon page. Disable in Customize > Maintenance Mode.', 'ifende' ) . '</p></div>';
	}
}
add_action( 'admin_notices', 'ifende_maintenance_admin_notice' );
