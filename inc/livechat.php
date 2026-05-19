<?php
/**
 * Live Chat Widget — Supports Tawk.to, Crisp, WhatsApp, and custom embed codes.
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
			'none'     => esc_html__( 'Disabled', 'ifende' ),
			'tawkto'   => esc_html__( 'Tawk.to', 'ifende' ),
			'crisp'    => esc_html__( 'Crisp', 'ifende' ),
			'whatsapp' => esc_html__( 'WhatsApp', 'ifende' ),
			'custom'   => esc_html__( 'Custom Code', 'ifende' ),
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

	// WhatsApp Number.
	$wp_customize->add_setting( 'ifende_livechat_whatsapp_number', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_whatsapp_number', [
		'label'       => esc_html__( 'WhatsApp Phone Number', 'ifende' ),
		'description' => esc_html__( 'Enter your full phone number with country code, no spaces or dashes (e.g., 2348012345678).', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
	] );

	// WhatsApp pre-filled message.
	$wp_customize->add_setting( 'ifende_livechat_whatsapp_message', [
		'default'           => 'Hello! I visited your website and would like to chat.',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_whatsapp_message', [
		'label'       => esc_html__( 'WhatsApp Pre-filled Message', 'ifende' ),
		'description' => esc_html__( 'The default message that appears when a visitor clicks the WhatsApp button.', 'ifende' ),
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
	$valid = [ 'none', 'tawkto', 'crisp', 'whatsapp', 'custom' ];
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
		case 'whatsapp':
			ifende_livechat_whatsapp();
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
 * Output WhatsApp floating chat button.
 */
function ifende_livechat_whatsapp() {
	$number = get_theme_mod( 'ifende_livechat_whatsapp_number', '' );

	if ( empty( $number ) ) {
		return;
	}

	// Strip everything except digits.
	$number = preg_replace( '/[^0-9]/', '', $number );

	$message = get_theme_mod( 'ifende_livechat_whatsapp_message', 'Hello! I visited your website and would like to chat.' );
	$url     = 'https://wa.me/' . $number . '?text=' . rawurlencode( $message );
	?>
	<!--Start of WhatsApp Chat Button-->
	<a href="<?php echo esc_url( $url ); ?>" class="ifende-whatsapp-btn" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Chat on WhatsApp', 'ifende' ); ?>" title="<?php esc_attr_e( 'Chat on WhatsApp', 'ifende' ); ?>">
		<svg width="28" height="28" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
	</a>
	<style>
	.ifende-whatsapp-btn{position:fixed;bottom:32px;right:32px;z-index:100;width:56px;height:56px;border-radius:50%;background:#25D366;color:#fff;display:flex;align-items:center;justify-content:center;box-shadow:0 4px 16px rgba(37,211,102,0.4);transition:transform .2s,box-shadow .2s;text-decoration:none;}
	.ifende-whatsapp-btn:hover{transform:translateY(-3px) scale(1.05);box-shadow:0 6px 24px rgba(37,211,102,0.5);}
	.ifende-whatsapp-btn:focus-visible{outline:2px solid var(--gold,#C9A84C);outline-offset:3px;}
	.ifende-whatsapp-btn svg{width:28px;height:28px;}
	@media(max-width:600px){.ifende-whatsapp-btn{bottom:20px;right:20px;width:50px;height:50px;}.ifende-whatsapp-btn svg{width:24px;height:24px;}}
	/* Move back-to-top button left when WhatsApp is active */
	.back-to-top{right:100px!important;}
	@media(max-width:600px){.back-to-top{right:80px!important;}}
	</style>
	<!--End of WhatsApp Chat Button-->
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
