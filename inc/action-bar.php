<?php
/**
 * Floating Action Bar — Sticky top/bottom bar with message and CTA.
 *
 * Dismissible with cookie memory. Configurable via Customizer.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Action Bar Customizer settings.
 */
function ifende_action_bar_customizer( $wp_customize ) {
	$wp_customize->add_section( 'ifende_action_bar', [
		'title'       => esc_html__( 'Floating Action Bar', 'ifende' ),
		'panel'       => 'ifende_panel',
		'description' => esc_html__( 'A sticky announcement bar at the top of the page with a CTA button.', 'ifende' ),
	] );

	$wp_customize->add_setting( 'ifende_action_bar_enabled', [
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'ifende_action_bar_enabled', [
		'label'   => esc_html__( 'Enable Action Bar', 'ifende' ),
		'section' => 'ifende_action_bar',
		'type'    => 'checkbox',
	] );

	$wp_customize->add_setting( 'ifende_action_bar_message', [
		'default'           => 'Limited slots available for Q3 projects.',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_action_bar_message', [
		'label'   => esc_html__( 'Message', 'ifende' ),
		'section' => 'ifende_action_bar',
		'type'    => 'text',
	] );

	$wp_customize->add_setting( 'ifende_action_bar_cta_text', [
		'default'           => 'Book Now',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_action_bar_cta_text', [
		'label'   => esc_html__( 'CTA Button Text', 'ifende' ),
		'section' => 'ifende_action_bar',
		'type'    => 'text',
	] );

	$wp_customize->add_setting( 'ifende_action_bar_cta_url', [
		'default'           => '#contact',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( 'ifende_action_bar_cta_url', [
		'label'   => esc_html__( 'CTA Button URL', 'ifende' ),
		'section' => 'ifende_action_bar',
		'type'    => 'url',
	] );

	$wp_customize->add_setting( 'ifende_action_bar_position', [
		'default'           => 'top',
		'sanitize_callback' => 'ifende_sanitize_action_bar_position',
	] );
	$wp_customize->add_control( 'ifende_action_bar_position', [
		'label'   => esc_html__( 'Position', 'ifende' ),
		'section' => 'ifende_action_bar',
		'type'    => 'select',
		'choices' => [
			'top'    => esc_html__( 'Top of page', 'ifende' ),
			'bottom' => esc_html__( 'Bottom of page', 'ifende' ),
		],
	] );
}
add_action( 'customize_register', 'ifende_action_bar_customizer' );

function ifende_sanitize_action_bar_position( $value ) {
	return in_array( $value, [ 'top', 'bottom' ], true ) ? $value : 'top';
}

/**
 * Output the action bar.
 */
function ifende_action_bar_output() {
	if ( is_admin() ) {
		return;
	}

	if ( ! get_theme_mod( 'ifende_action_bar_enabled', false ) ) {
		return;
	}

	$message  = get_theme_mod( 'ifende_action_bar_message', 'Limited slots available for Q3 projects.' );
	$cta_text = get_theme_mod( 'ifende_action_bar_cta_text', 'Book Now' );
	$cta_url  = get_theme_mod( 'ifende_action_bar_cta_url', '#contact' );
	$position = get_theme_mod( 'ifende_action_bar_position', 'top' );
	?>
	<div class="action-bar action-bar--<?php echo esc_attr( $position ); ?>" id="actionBar" role="banner">
		<div class="action-bar-inner">
			<span class="action-bar-message"><?php echo esc_html( $message ); ?></span>
			<a href="<?php echo esc_url( $cta_url ); ?>" class="action-bar-cta"><?php echo esc_html( $cta_text ); ?></a>
		</div>
		<button class="action-bar-dismiss" id="actionBarDismiss" aria-label="<?php esc_attr_e( 'Dismiss', 'ifende' ); ?>">&times;</button>
	</div>
	<script>
	(function(){
		var bar = document.getElementById('actionBar');
		var btn = document.getElementById('actionBarDismiss');
		if (localStorage.getItem('ifende-action-bar-dismissed')) { bar.style.display='none'; return; }
		btn.addEventListener('click', function(){
			bar.style.display='none';
			localStorage.setItem('ifende-action-bar-dismissed', '1');
		});
	})();
	</script>
	<?php
}
add_action( 'wp_body_open', 'ifende_action_bar_output', 5 );
