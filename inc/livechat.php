<?php
/**
 * Live Chat Widget — Supports Tawk.to, Crisp, and custom embed codes.
 *
 * @package Ifende
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Live Chat Customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 */
function ifende_livechat_customizer( $wp_customize ) {
	$wp_customize->add_section( 'ifende_livechat', [
		'title'       => esc_html__( 'Live Chat', 'ifende' ),
		'panel'       => 'ifende_panel',
		'description' => esc_html__( 'Add a live chat widget to your site. Choose a provider and enter your widget ID.', 'ifende' ),
	] );

	// Provider selector.
	$wp_customize->add_setting( 'ifende_livechat_provider', [
		'default'           => 'none',
		'sanitize_callback' => 'ifende_sanitize_livechat_provider',
	] );
	$wp_customize->add_control( 'ifende_livechat_provider', [
		'label'   => esc_html__( 'Chat Provider', 'ifende' ),
		'section' => 'ifende_livechat',
		'type'    => 'select',
		'choices' => [
			'none'   => esc_html__( 'Disabled', 'ifende' ),
			'tawkto' => esc_html__( 'Tawk.to', 'ifende' ),
			'crisp'  => esc_html__( 'Crisp', 'ifende' ),
			'custom' => esc_html__( 'Custom Code', 'ifende' ),
		],
	] );

	// Tawk.to Property ID.
	$wp_customize->add_setting( 'ifende_livechat_tawkto_id', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_tawkto_id', [
		'label'       => esc_html__( 'Tawk.to Property ID', 'ifende' ),
		'description' => esc_html__( 'Found in Tawk.to Dashboard > Administration > Chat Widget. Format: xxxxxxxxxxxxxxxxx/xxxxxxxx', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
	] );


	// Crisp Website ID.
	$wp_customize->add_setting( 'ifende_livechat_crisp_id', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_crisp_id', [
		'label'       => esc_html__( 'Crisp Website ID', 'ifende' ),
		'description' => esc_html__( 'Found in Crisp Dashboard > Settings > Website Settings. Format: xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
	] );

	// Custom embed code.
	$wp_customize->add_setting( 'ifende_livechat_custom_code', [
		'default'           => '',
		'sanitize_callback' => 'ifende_sanitize_livechat_code',
	] );
	$wp_customize->add_control( 'ifende_livechat_custom_code', [
		'label'       => esc_html__( 'Custom Chat Widget Code', 'ifende' ),
		'description' => esc_html__( 'Paste the full embed code from your chat provider (including <script> tags).', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'textarea',
		'input_attrs' => [ 'rows' => 8 ],
	] );

	// Hide for logged-in admins option.
	$wp_customize->add_setting( 'ifende_livechat_hide_admin', [
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'ifende_livechat_hide_admin', [
		'label'   => esc_html__( 'Hide chat widget for logged-in admins', 'ifende' ),
		'section' => 'ifende_livechat',
		'type'    => 'checkbox',
	] );
}
add_action( 'customize_register', 'ifende_livechat_customizer' );


/**
 * Sanitize the livechat provider selection.
 *
 * @param string $value Selected value.
 * @return string Sanitized value.
 */
function ifende_sanitize_livechat_provider( $value ) {
	$valid = [ 'none', 'tawkto', 'crisp', 'custom' ];
	return in_array( $value, $valid, true ) ? $value : 'none';
}

/**
 * Sanitize custom chat code — allows script tags for admin users.
 *
 * @param string $input Raw input.
 * @return string Sanitized output.
 */
function ifende_sanitize_livechat_code( $input ) {
	if ( current_user_can( 'unfiltered_html' ) ) {
		return $input;
	}
	return wp_kses( $input, [
		'script' => [ 'src' => [], 'async' => [], 'defer' => [], 'type' => [], 'charset' => [] ],
	] );
}

/**
 * Output the live chat widget script in the footer.
 *
 * Only loads on the front-end, never in admin.
 */
function ifende_livechat_output() {
	// Never load in admin.
	if ( is_admin() ) {
		return;
	}

	$provider = get_theme_mod( 'ifende_livechat_provider', 'none' );

	// Bail if disabled.
	if ( 'none' === $provider ) {
		return;
	}

	// Optionally hide for admins.
	$hide_admin = get_theme_mod( 'ifende_livechat_hide_admin', false );
	if ( $hide_admin && current_user_can( 'manage_options' ) ) {
		return;
	}

	switch ( $provider ) {
		case 'tawkto':
			ifende_livechat_tawkto();
			break;
		case 'crisp':
			ifende_livechat_crisp();
			break;
		case 'custom':
			ifende_livechat_custom();
			break;
	}
}
add_action( 'wp_footer', 'ifende_livechat_output', 99 );


/**
 * Output Tawk.to chat widget.
 */
function ifende_livechat_tawkto() {
	$property_id = get_theme_mod( 'ifende_livechat_tawkto_id', '' );

	if ( empty( $property_id ) ) {
		return;
	}

	// Sanitize — only allow alphanumeric and forward slashes.
	$property_id = preg_replace( '/[^a-zA-Z0-9\/]/', '', $property_id );
	?>
	<!--Start of Tawk.to Script-->
	<script type="text/javascript">
	var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
	(function(){
	var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
	s1.async=true;
	s1.src='https://embed.tawk.to/<?php echo esc_js( $property_id ); ?>/default';
	s1.charset='UTF-8';
	s1.setAttribute('crossorigin','*');
	s0.parentNode.insertBefore(s1,s0);
	})();
	</script>
	<!--End of Tawk.to Script-->
	<?php
}

/**
 * Output Crisp chat widget.
 */
function ifende_livechat_crisp() {
	$website_id = get_theme_mod( 'ifende_livechat_crisp_id', '' );

	if ( empty( $website_id ) ) {
		return;
	}

	// Sanitize — UUID format.
	$website_id = preg_replace( '/[^a-f0-9\-]/', '', strtolower( $website_id ) );
	?>
	<!--Start of Crisp Script-->
	<script type="text/javascript">
	window.$crisp=[];window.CRISP_WEBSITE_ID="<?php echo esc_js( $website_id ); ?>";
	(function(){
	var d=document;var s=d.createElement("script");
	s.src="https://client.crisp.chat/l.js";
	s.async=1;d.getElementsByTagName("head")[0].appendChild(s);
	})();
	</script>
	<!--End of Crisp Script-->
	<?php
}

/**
 * Output custom chat widget code.
 */
function ifende_livechat_custom() {
	$code = get_theme_mod( 'ifende_livechat_custom_code', '' );

	if ( empty( $code ) ) {
		return;
	}

	// Output the custom code. Only admins can save unfiltered HTML.
	echo "\n<!--Start of Custom Chat Widget-->\n";
	echo $code; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized on save.
	echo "\n<!--End of Custom Chat Widget-->\n";
}
