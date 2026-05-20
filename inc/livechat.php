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

	// --- Working Hours Schedule ---

	$wp_customize->add_setting( 'ifende_livechat_schedule_enabled', [
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'ifende_livechat_schedule_enabled', [
		'label'       => esc_html__( 'Only show during working hours', 'ifende' ),
		'description' => esc_html__( 'When enabled, the chat widget is hidden outside your configured working days and hours.', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'checkbox',
	] );

	// Working days (comma-separated numbers: 1=Mon, 2=Tue, …, 7=Sun).
	$wp_customize->add_setting( 'ifende_livechat_working_days', [
		'default'           => '1,2,3,4,5',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_working_days', [
		'label'       => esc_html__( 'Working Days', 'ifende' ),
		'description' => esc_html__( 'Comma-separated day numbers. 1=Monday, 2=Tuesday, 3=Wednesday, 4=Thursday, 5=Friday, 6=Saturday, 7=Sunday. Default: 1,2,3,4,5 (Mon–Fri).', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
	] );

	// Start hour (24h format, e.g. 09:00).
	$wp_customize->add_setting( 'ifende_livechat_start_time', [
		'default'           => '09:00',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_start_time', [
		'label'       => esc_html__( 'Start Time (24h)', 'ifende' ),
		'description' => esc_html__( 'Time when chat becomes available. Format: HH:MM (e.g. 09:00).', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
	] );

	// End hour (24h format, e.g. 17:00).
	$wp_customize->add_setting( 'ifende_livechat_end_time', [
		'default'           => '17:00',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_end_time', [
		'label'       => esc_html__( 'End Time (24h)', 'ifende' ),
		'description' => esc_html__( 'Time when chat becomes unavailable. Format: HH:MM (e.g. 17:00).', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
	] );

	// Timezone override (defaults to WP timezone from Settings → General).
	$wp_customize->add_setting( 'ifende_livechat_timezone', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_timezone', [
		'label'       => esc_html__( 'Timezone (optional)', 'ifende' ),
		'description' => esc_html__( 'Leave empty to use the WordPress timezone from Settings → General. Or enter a timezone string like "Africa/Lagos", "America/New_York", "Europe/London".', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
	] );

	// Offline message (shown instead of chat when outside working hours).
	$wp_customize->add_setting( 'ifende_livechat_offline_message', [
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_livechat_offline_message', [
		'label'       => esc_html__( 'Offline Message (optional)', 'ifende' ),
		'description' => esc_html__( 'A small floating message shown when chat is unavailable, e.g. "We\'re offline. Back Mon–Fri 9am–5pm." Leave empty to show nothing outside working hours.', 'ifende' ),
		'section'     => 'ifende_livechat',
		'type'        => 'text',
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
 * Check whether the chat widget should be active based on the configured
 * working hours schedule.
 *
 * Returns true if:
 *   - The schedule feature is disabled (always show), OR
 *   - The current server time (in the configured timezone) falls within
 *     the configured working days AND hours.
 *
 * @since 1.6.0
 *
 * @return bool True if chat should render, false if outside working hours.
 */
function ifende_livechat_is_within_working_hours() {
	$schedule_enabled = get_theme_mod( 'ifende_livechat_schedule_enabled', false );

	// If scheduling is disabled, chat is always active.
	if ( ! $schedule_enabled ) {
		return true;
	}

	// Determine timezone.
	$tz_string = get_theme_mod( 'ifende_livechat_timezone', '' );
	if ( empty( $tz_string ) ) {
		$tz_string = wp_timezone_string();
	}

	try {
		$tz = new DateTimeZone( $tz_string );
	} catch ( \Exception $e ) {
		// Invalid timezone — fall back to UTC rather than hiding chat.
		$tz = new DateTimeZone( 'UTC' );
	}

	$now = new DateTime( 'now', $tz );

	// Check day of week. PHP: 1=Mon … 7=Sun (ISO-8601).
	$current_day  = (int) $now->format( 'N' );
	$working_days = get_theme_mod( 'ifende_livechat_working_days', '1,2,3,4,5' );
	$days_array   = array_map( 'absint', array_filter( explode( ',', $working_days ) ) );

	if ( ! in_array( $current_day, $days_array, true ) ) {
		return false;
	}

	// Check time window.
	$start_time = get_theme_mod( 'ifende_livechat_start_time', '09:00' );
	$end_time   = get_theme_mod( 'ifende_livechat_end_time', '17:00' );

	$current_minutes = (int) $now->format( 'G' ) * 60 + (int) $now->format( 'i' );

	$start_parts   = explode( ':', $start_time );
	$start_minutes = ( (int) ( $start_parts[0] ?? 9 ) ) * 60 + ( (int) ( $start_parts[1] ?? 0 ) );

	$end_parts   = explode( ':', $end_time );
	$end_minutes = ( (int) ( $end_parts[0] ?? 17 ) ) * 60 + ( (int) ( $end_parts[1] ?? 0 ) );

	// Handle overnight schedules (e.g. 22:00 → 06:00): if end < start,
	// the active window spans midnight.
	if ( $end_minutes <= $start_minutes ) {
		return ( $current_minutes >= $start_minutes || $current_minutes < $end_minutes );
	}

	return ( $current_minutes >= $start_minutes && $current_minutes < $end_minutes );
}

/**
 * Output the offline message bubble when chat is hidden due to working
 * hours restrictions. Only renders if the admin configured an offline
 * message in the Customizer.
 *
 * @since 1.6.0
 */
function ifende_livechat_offline_bubble() {
	$message = get_theme_mod( 'ifende_livechat_offline_message', '' );
	if ( empty( $message ) ) {
		return;
	}
	?>
	<!-- Ifende: Chat offline message -->
	<div class="ifende-chat-offline" aria-label="<?php esc_attr_e( 'Chat is currently offline', 'ifende' ); ?>">
		<span class="ifende-chat-offline-dot"></span>
		<span class="ifende-chat-offline-text"><?php echo esc_html( $message ); ?></span>
	</div>
	<style>
	.ifende-chat-offline{position:fixed;bottom:32px;right:32px;z-index:99;background:var(--black,#0A0A0A);border:1px solid var(--border,rgba(245,242,236,0.12));border-radius:24px;padding:10px 18px;display:flex;align-items:center;gap:8px;box-shadow:0 4px 16px rgba(0,0,0,0.3);font-size:0.75rem;letter-spacing:0.5px;color:var(--grey,#8A8A8A);}
	.ifende-chat-offline-dot{width:8px;height:8px;border-radius:50%;background:#e74c3c;flex-shrink:0;}
	.ifende-chat-offline-text{white-space:nowrap;}
	@media(max-width:600px){.ifende-chat-offline{bottom:20px;right:20px;font-size:0.7rem;padding:8px 14px;}}
	</style>
	<?php
}

/**
 * Output the live chat widget script in the footer.
 *
 * Only loads on the front-end, never in admin. Respects working hours
 * schedule when enabled — shows an optional offline message bubble
 * instead of the chat widget outside configured hours.
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

	// Working hours check.
	if ( ! ifende_livechat_is_within_working_hours() ) {
		ifende_livechat_offline_bubble();
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
 *
 * Defers the actual widget.js fetch until the visitor shows intent —
 * either by interacting with the page (move mouse, touch, scroll, key)
 * or after a 6-second idle window — whichever comes first. Saves
 * 200-400 KB of script weight on the initial load for the ~95 % of
 * visitors who never engage with chat.
 *
 * Once loaded, Tawk.to's own widget.js renders the floating chat
 * button; we just delay when that script runs.
 */
function ifende_livechat_tawkto() {
	$property_id = get_theme_mod( 'ifende_livechat_tawkto_id', '' );

	if ( empty( $property_id ) ) {
		return;
	}

	// Sanitize — only allow alphanumeric and forward slashes.
	$property_id = preg_replace( '/[^a-zA-Z0-9\/]/', '', $property_id );
	?>
	<!-- Tawk.to (deferred until user interaction or 6s idle) -->
	<script type="text/javascript">
	(function(){
		var loaded = false;
		function loadTawk() {
			if (loaded) return;
			loaded = true;
			window.Tawk_API = window.Tawk_API || {};
			window.Tawk_LoadStart = new Date();
			var s = document.createElement('script');
			s.async = true;
			s.src = 'https://embed.tawk.to/<?php echo esc_js( $property_id ); ?>/default';
			s.charset = 'UTF-8';
			s.setAttribute('crossorigin', '*');
			(document.getElementsByTagName('script')[0] || document.body).parentNode
				.insertBefore(s, document.getElementsByTagName('script')[0] || null);
		}
		var events = ['mousemove', 'touchstart', 'keydown', 'scroll'];
		events.forEach(function(ev) {
			window.addEventListener(ev, loadTawk, { once: true, passive: true });
		});
		// Idle fallback — load anyway after 6s so visitors who land on the
		// page and read without interacting still get the chat widget.
		setTimeout(loadTawk, 6000);
	})();
	</script>
	<?php
}

/**
 * Output Crisp chat widget.
 *
 * Same deferred-load pattern as Tawk.to — the official Crisp install
 * snippet immediately injects client.crisp.chat/l.js into <head>, which
 * then pulls another ~200 KB. Wrapping it in the same first-interaction
 * gate is a free perf win on cold loads.
 */
function ifende_livechat_crisp() {
	$website_id = get_theme_mod( 'ifende_livechat_crisp_id', '' );

	if ( empty( $website_id ) ) {
		return;
	}

	// Sanitize — UUID format.
	$website_id = preg_replace( '/[^a-f0-9\-]/', '', strtolower( $website_id ) );
	?>
	<!-- Crisp (deferred until user interaction or 6s idle) -->
	<script type="text/javascript">
	(function(){
		var loaded = false;
		window.$crisp = [];
		window.CRISP_WEBSITE_ID = '<?php echo esc_js( $website_id ); ?>';
		function loadCrisp() {
			if (loaded) return;
			loaded = true;
			var s = document.createElement('script');
			s.src = 'https://client.crisp.chat/l.js';
			s.async = 1;
			document.getElementsByTagName('head')[0].appendChild(s);
		}
		var events = ['mousemove', 'touchstart', 'keydown', 'scroll'];
		events.forEach(function(ev) {
			window.addEventListener(ev, loadCrisp, { once: true, passive: true });
		});
		setTimeout(loadCrisp, 6000);
	})();
	</script>
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
