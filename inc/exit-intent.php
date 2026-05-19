<?php
/**
 * Exit-Intent Popup — Detects when visitor is about to leave and shows a CTA.
 *
 * Configurable via Customizer: enable/disable, headline, message, CTA text/URL,
 * display frequency (once per session or once per X days).
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Exit-Intent Customizer settings.
 */
function ifende_exit_intent_customizer( $wp_customize ) {
	$wp_customize->add_section( 'ifende_exit_intent', [
		'title'       => esc_html__( 'Exit-Intent Popup', 'ifende' ),
		'panel'       => 'ifende_panel',
		'description' => esc_html__( 'Show a popup when visitors are about to leave the page.', 'ifende' ),
	] );

	$wp_customize->add_setting( 'ifende_exit_intent_enabled', [
		'default'           => false,
		'sanitize_callback' => 'wp_validate_boolean',
	] );
	$wp_customize->add_control( 'ifende_exit_intent_enabled', [
		'label'   => esc_html__( 'Enable Exit-Intent Popup', 'ifende' ),
		'section' => 'ifende_exit_intent',
		'type'    => 'checkbox',
	] );

	$wp_customize->add_setting( 'ifende_exit_intent_headline', [
		'default'           => 'Wait! Before you go...',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_exit_intent_headline', [
		'label'   => esc_html__( 'Headline', 'ifende' ),
		'section' => 'ifende_exit_intent',
		'type'    => 'text',
	] );

	$wp_customize->add_setting( 'ifende_exit_intent_message', [
		'default'           => 'I would love to work with you. Let me know how I can help with your next project.',
		'sanitize_callback' => 'sanitize_textarea_field',
	] );
	$wp_customize->add_control( 'ifende_exit_intent_message', [
		'label'   => esc_html__( 'Message', 'ifende' ),
		'section' => 'ifende_exit_intent',
		'type'    => 'textarea',
	] );

	$wp_customize->add_setting( 'ifende_exit_intent_cta_text', [
		'default'           => 'Get In Touch',
		'sanitize_callback' => 'sanitize_text_field',
	] );
	$wp_customize->add_control( 'ifende_exit_intent_cta_text', [
		'label'   => esc_html__( 'CTA Button Text', 'ifende' ),
		'section' => 'ifende_exit_intent',
		'type'    => 'text',
	] );

	$wp_customize->add_setting( 'ifende_exit_intent_cta_url', [
		'default'           => '#contact',
		'sanitize_callback' => 'esc_url_raw',
	] );
	$wp_customize->add_control( 'ifende_exit_intent_cta_url', [
		'label'   => esc_html__( 'CTA Button URL', 'ifende' ),
		'section' => 'ifende_exit_intent',
		'type'    => 'url',
	] );

	$wp_customize->add_setting( 'ifende_exit_intent_days', [
		'default'           => 7,
		'sanitize_callback' => 'absint',
	] );
	$wp_customize->add_control( 'ifende_exit_intent_days', [
		'label'       => esc_html__( 'Show again after (days)', 'ifende' ),
		'description' => esc_html__( 'Once dismissed, the popup won\'t show again for this many days. Set to 0 for once per session.', 'ifende' ),
		'section'     => 'ifende_exit_intent',
		'type'        => 'number',
		'input_attrs' => [ 'min' => 0, 'max' => 90 ],
	] );
}
add_action( 'customize_register', 'ifende_exit_intent_customizer' );

/**
 * Output the exit-intent popup markup and script in the footer.
 */
function ifende_exit_intent_output() {
	if ( is_admin() ) {
		return;
	}

	if ( ! get_theme_mod( 'ifende_exit_intent_enabled', false ) ) {
		return;
	}

	// Don't show to logged-in admins.
	if ( current_user_can( 'manage_options' ) ) {
		return;
	}

	$headline = get_theme_mod( 'ifende_exit_intent_headline', 'Wait! Before you go...' );
	$message  = get_theme_mod( 'ifende_exit_intent_message', 'I would love to work with you. Let me know how I can help with your next project.' );
	$cta_text = get_theme_mod( 'ifende_exit_intent_cta_text', 'Get In Touch' );
	$cta_url  = get_theme_mod( 'ifende_exit_intent_cta_url', '#contact' );
	$days     = get_theme_mod( 'ifende_exit_intent_days', 7 );
	?>
	<!-- Exit-Intent Popup -->
	<div class="exit-popup-overlay" id="exitPopupOverlay" aria-hidden="true">
		<div class="exit-popup" role="dialog" aria-labelledby="exitPopupHeadline" aria-modal="true">
			<button class="exit-popup-close" id="exitPopupClose" aria-label="<?php esc_attr_e( 'Close popup', 'ifende' ); ?>">&times;</button>
			<div class="exit-popup-content">
				<h2 id="exitPopupHeadline" class="exit-popup-headline"><?php echo esc_html( $headline ); ?></h2>
				<p class="exit-popup-message"><?php echo esc_html( $message ); ?></p>
				<a href="<?php echo esc_url( $cta_url ); ?>" class="btn-primary exit-popup-cta"><?php echo esc_html( $cta_text ); ?> &rarr;</a>
			</div>
		</div>
	</div>
	<script>
	(function(){
		var days = <?php echo (int) $days; ?>;
		var storageKey = 'ifende-exit-popup-dismissed';

		// Check if already dismissed.
		var dismissed = localStorage.getItem(storageKey);
		if (dismissed) {
			if (days === 0) return; // session-based, already seen
			var dismissedAt = parseInt(dismissed, 10);
			if (Date.now() - dismissedAt < days * 86400000) return;
		}

		var shown = false;
		var overlay = document.getElementById('exitPopupOverlay');
		var closeBtn = document.getElementById('exitPopupClose');
		var trap = null;

		function showPopup() {
			if (shown) return;
			shown = true;
			overlay.classList.add('visible');
			overlay.setAttribute('aria-hidden', 'false');
			// Trap focus inside the dialog so Tab cycles between Close and CTA.
			// Falls back gracefully when main.js hasn't loaded yet.
			if (typeof window.ifendeFocusTrap === 'function') {
				trap = window.ifendeFocusTrap(overlay.querySelector('.exit-popup'));
			} else {
				closeBtn.focus();
			}
		}

		function hidePopup() {
			overlay.classList.remove('visible');
			overlay.setAttribute('aria-hidden', 'true');
			localStorage.setItem(storageKey, String(Date.now()));
			if (trap) { trap.release(); trap = null; }
		}

		// Exit-intent: mouse leaves viewport from the top.
		document.addEventListener('mouseout', function(e) {
			if (e.clientY < 5 && !shown) {
				showPopup();
			}
		});

		// Mobile fallback: show after 30 seconds of inactivity.
		var mobileTimer = setTimeout(function() {
			if (!shown && 'ontouchstart' in window) showPopup();
		}, 30000);

		closeBtn.addEventListener('click', hidePopup);
		overlay.addEventListener('click', function(e) {
			if (e.target === overlay) hidePopup();
		});
		document.addEventListener('keydown', function(e) {
			if (e.key === 'Escape' && shown) hidePopup();
		});
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'ifende_exit_intent_output', 95 );
