<?php
/**
 * Appointment Booking — Calendly, Cal.com, or custom embed integration.
 *
 * Adds a floating "Book a Call" button and/or embeddable booking widget
 * configurable via the Customizer.
 *
 * @package Ifende
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Booking Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function ifende_booking_customizer( $wp_customize ) {
	$wp_customize->add_section( 'ifende_booking', [
		'title'       => esc_html__( 'Appointment Booking', 'ifende' ),
		'panel'       => 'ifende_panel',
		'description' => esc_html__( 'Add a booking widget so visitors can schedule calls directly from your site.', 'ifende' ),
	] );

	// Provider selector.
	$wp_customize->add_setting( 'ifende_booking_provider', [
		'default'           => 'none',
		'sanitize_callback' => 'ifende_sanitize_booking_provider',
	] );
	$wp_customize->add_control( 'ifende_booking_provider', [
		'label'   => esc_html__( 'Booking Provider', 'ifende' ),
		'section' => 'ifende_booking',
		'type'    => 'select',
		'choices' => [
			'none'     => esc_html__( 'Disabled', 'ifende' ),
			'calendly' => esc_html__( 'Calendly', 'ifende' ),
			'calcom'   => esc_html__( 'Cal.com', 'ifende' ),
			'custom'   => esc_html__( 'Custom URL', 'ifende' ),
		],
	] );

	// Calendly URL.
	$wp_customize->add_setting( 'ifende_booking_calendly_url', [
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( 'ifende_booking_calendly_url', [
		'label'       => esc_html__( 'Calendly URL', 'ifende' ),
		'description' => esc_html__( 'Your Calendly scheduling page URL (e.g., https://calendly.com/yourname/30min).', 'ifende' ),
		'section'     => 'ifende_booking',
		'type'        => 'url',
	] );

	// Cal.com URL.
	$wp_customize->add_setting( 'ifende_booking_calcom_url', [
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( 'ifende_booking_calcom_url', [
		'label'       => esc_html__( 'Cal.com URL', 'ifende' ),
		'description' => esc_html__( 'Your Cal.com scheduling page URL (e.g., https://cal.com/yourname/30min).', 'ifende' ),
		'section'     => 'ifende_booking',
		'type'        => 'url',
	] );

	// Custom booking URL.
	$wp_customize->add_setting( 'ifende_booking_custom_url', [
		'default'           => '',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( 'ifende_booking_custom_url', [
		'label'       => esc_html__( 'Custom Booking URL', 'ifende' ),
		'description' => esc_html__( 'Any external booking page URL. Opens in a new tab.', 'ifende' ),
		'section'     => 'ifende_booking',
		'type'        => 'url',
	] );

	// Button text.
	$wp_customize->add_setting( 'ifende_booking_button_text', [
		'default'           => 'Book a Call',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_booking_button_text', [
		'label'   => esc_html__( 'Button Text', 'ifende' ),
		'section' => 'ifende_booking',
		'type'    => 'text',
	] );

	// Show floating button.
	$wp_customize->add_setting( 'ifende_booking_show_float', [
		'default'           => true,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'ifende_booking_show_float', [
		'label'   => esc_html__( 'Show floating "Book a Call" button', 'ifende' ),
		'section' => 'ifende_booking',
		'type'    => 'checkbox',
	] );

	// Hide for logged-in admins.
	$wp_customize->add_setting( 'ifende_booking_hide_admin', [
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'ifende_booking_hide_admin', [
		'label'   => esc_html__( 'Hide booking button for logged-in admins', 'ifende' ),
		'section' => 'ifende_booking',
		'type'    => 'checkbox',
	] );
}
add_action( 'customize_register', 'ifende_booking_customizer' );

/**
 * Sanitize the booking provider selection.
 *
 * @param string $value Selected value.
 * @return string Sanitized value.
 */
function ifende_sanitize_booking_provider( $value ) {
	$valid = [ 'none', 'calendly', 'calcom', 'custom' ];
	return in_array( $value, $valid, true ) ? $value : 'none';
}

/**
 * Output the booking widget in the footer.
 */
function ifende_booking_output() {
	if ( is_admin() ) {
		return;
	}

	$provider = get_theme_mod( 'ifende_booking_provider', 'none' );

	if ( 'none' === $provider ) {
		return;
	}

	// Optionally hide for admins.
	$hide_admin = get_theme_mod( 'ifende_booking_hide_admin', false );
	if ( $hide_admin && current_user_can( 'manage_options' ) ) {
		return;
	}

	// Get the booking URL based on provider.
	$url = '';
	switch ( $provider ) {
		case 'calendly':
			$url = get_theme_mod( 'ifende_booking_calendly_url', '' );
			break;
		case 'calcom':
			$url = get_theme_mod( 'ifende_booking_calcom_url', '' );
			break;
		case 'custom':
			$url = get_theme_mod( 'ifende_booking_custom_url', '' );
			break;
	}

	if ( empty( $url ) ) {
		return;
	}

	$button_text = get_theme_mod( 'ifende_booking_button_text', 'Book a Call' );
	$show_float  = get_theme_mod( 'ifende_booking_show_float', true );

	// Output Calendly inline script if Calendly provider.
	if ( 'calendly' === $provider ) {
		ifende_booking_calendly( $url, $button_text, $show_float );
	} elseif ( 'calcom' === $provider ) {
		ifende_booking_calcom( $url, $button_text, $show_float );
	} else {
		ifende_booking_custom_link( $url, $button_text, $show_float );
	}
}
add_action( 'wp_footer', 'ifende_booking_output', 98 );

/**
 * Output Calendly popup widget.
 */
function ifende_booking_calendly( $url, $button_text, $show_float ) {
	?>
	<!-- Calendly Booking Widget -->
	<link href="https://assets.calendly.com/assets/external/widget.css" rel="stylesheet">
	<script src="https://assets.calendly.com/assets/external/widget.js" type="text/javascript" async></script>
	<?php if ( $show_float ) : ?>
	<button
		class="ifende-booking-btn"
		onclick="Calendly.initPopupWidget({url: '<?php echo esc_url( $url ); ?>'}); return false;"
		aria-label="<?php echo esc_attr( $button_text ); ?>"
	>
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
		<span><?php echo esc_html( $button_text ); ?></span>
	</button>
	<?php endif; ?>
	<?php ifende_booking_btn_styles(); ?>
	<?php
}

/**
 * Output Cal.com popup widget.
 */
function ifende_booking_calcom( $url, $button_text, $show_float ) {
	// Extract the Cal.com path from the URL.
	$path = str_replace( [ 'https://cal.com/', 'http://cal.com/' ], '', $url );
	?>
	<!-- Cal.com Booking Widget -->
	<script>
	(function(C,A,L){let p=function(a,ar){a.q.push(ar)};let d=C.document;C.Cal=C.Cal||function(){let cal=C.Cal;if(!cal.loaded){cal.ns={};cal.q=cal.q||[];d.head.appendChild(d.createElement("script")).src=A;cal.loaded=true}if(ar=arguments[1]){p(cal,ar)}};C.Cal.l=L})(window,"https://app.cal.com/embed/embed.js","init");
	Cal("init");
	</script>
	<?php if ( $show_float ) : ?>
	<button
		class="ifende-booking-btn"
		data-cal-link="<?php echo esc_attr( $path ); ?>"
		data-cal-config='{"layout":"month_view"}'
		aria-label="<?php echo esc_attr( $button_text ); ?>"
	>
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
		<span><?php echo esc_html( $button_text ); ?></span>
	</button>
	<?php endif; ?>
	<?php ifende_booking_btn_styles(); ?>
	<?php
}

/**
 * Output custom booking link button.
 */
function ifende_booking_custom_link( $url, $button_text, $show_float ) {
	if ( ! $show_float ) {
		return;
	}
	?>
	<!-- Booking Button -->
	<a
		href="<?php echo esc_url( $url ); ?>"
		class="ifende-booking-btn"
		target="_blank"
		rel="noopener noreferrer"
		aria-label="<?php echo esc_attr( $button_text ); ?>"
	>
		<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
		<span><?php echo esc_html( $button_text ); ?></span>
	</a>
	<?php ifende_booking_btn_styles(); ?>
	<?php
}

/**
 * Output shared booking button styles (inline to avoid extra CSS file).
 */
function ifende_booking_btn_styles() {
	static $output = false;
	if ( $output ) {
		return;
	}
	$output = true;
	?>
	<style>
	.ifende-booking-btn{position:fixed;bottom:32px;left:32px;z-index:100;display:inline-flex;align-items:center;gap:8px;background:var(--green,#21A14E);color:var(--black,#0A0A0A);padding:12px 20px;border-radius:2px;border:none;cursor:pointer;font-family:'Syne',sans-serif;font-size:0.72rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;text-decoration:none;box-shadow:0 4px 16px rgba(33,161,78,0.3);transition:all .2s;}
	.ifende-booking-btn:hover{background:var(--green2,#17783A);transform:translateY(-2px);box-shadow:0 6px 20px rgba(33,161,78,0.4);color:var(--white,#F5F2EC);}
	.ifende-booking-btn:focus-visible{outline:2px solid var(--gold,#C9A84C);outline-offset:3px;}
	.ifende-booking-btn svg{flex-shrink:0;}
	@media(max-width:600px){.ifende-booking-btn{bottom:20px;left:20px;padding:10px 16px;font-size:0.65rem;}.ifende-booking-btn span{display:none;}.ifende-booking-btn{width:44px;height:44px;border-radius:50%;padding:0;justify-content:center;}}
	</style>
	<?php
}

/**
 * Helper: Output a booking embed shortcode for use in pages/posts.
 * Usage: [ifende_booking]
 */
function ifende_booking_shortcode( $atts ) {
	$provider = get_theme_mod( 'ifende_booking_provider', 'none' );

	if ( 'none' === $provider ) {
		return '';
	}

	$url = '';
	switch ( $provider ) {
		case 'calendly':
			$url = get_theme_mod( 'ifende_booking_calendly_url', '' );
			break;
		case 'calcom':
			$url = get_theme_mod( 'ifende_booking_calcom_url', '' );
			break;
		case 'custom':
			$url = get_theme_mod( 'ifende_booking_custom_url', '' );
			break;
	}

	if ( empty( $url ) ) {
		return '';
	}

	$atts = shortcode_atts( [ 'height' => '700' ], $atts, 'ifende_booking' );

	if ( 'calendly' === $provider ) {
		return '<div class="calendly-inline-widget" data-url="' . esc_url( $url ) . '" style="min-width:320px;height:' . esc_attr( $atts['height'] ) . 'px;"></div>';
	} elseif ( 'calcom' === $provider ) {
		$path = str_replace( [ 'https://cal.com/', 'http://cal.com/' ], '', $url );
		return '<div data-cal-link="' . esc_attr( $path ) . '" data-cal-config=\'{"layout":"month_view"}\' style="min-width:320px;height:' . esc_attr( $atts['height'] ) . 'px;overflow:hidden;"></div>';
	}

	// Custom — just show a styled link.
	$button_text = get_theme_mod( 'ifende_booking_button_text', 'Book a Call' );
	return '<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer" class="btn-primary">' . esc_html( $button_text ) . ' &rarr;</a>';
}
add_shortcode( 'ifende_booking', 'ifende_booking_shortcode' );
