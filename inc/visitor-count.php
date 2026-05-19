<?php
/**
 * Live Visitor Count — Lightweight AJAX-based visitor counter.
 *
 * Shows "X people viewing now" badge on the front-end.
 * Uses transients for a simple, privacy-friendly approach (no cookies/tracking).
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the visitor count badge in the footer.
 *
 * Although the AJAX endpoint stores only an MD5(IP|hour) hash with a 5-minute
 * sliding window (no cookies, no persistent identifiers), we still gate on
 * consent — the IP itself is processed server-side, and a cautious GDPR read
 * treats that as personal data. Better to err on the side of asking first.
 */
function ifende_visitor_count_output() {
	if ( is_admin() || current_user_can( 'manage_options' ) ) {
		return;
	}

	// GDPR: skip the badge entirely until the visitor consents. The badge
	// is a nice-to-have, not core functionality, so a degraded UX (no badge
	// for non-consenting visitors) is the right trade-off.
	if ( function_exists( 'ifende_consent_given' ) && ! ifende_consent_given() ) {
		return;
	}
	?>
	<div class="visitor-count" id="visitorCount" aria-live="polite">
		<span class="visitor-count-dot"></span>
		<span id="visitorCountNum">—</span> <?php esc_html_e( 'viewing now', 'ifende' ); ?>
	</div>
	<script>
	(function(){
		var el = document.getElementById('visitorCount');
		var num = document.getElementById('visitorCountNum');
		if (!el || !num) return;

		// Ping on load.
		var fd = new FormData();
		fd.append('action', 'ifende_visitor_ping');
		fd.append('nonce', ifendeData.nonce);
		fetch(ifendeData.ajaxUrl, { method:'POST', body:fd })
			.then(function(r){ return r.json(); })
			.then(function(res){
				if (res.success && res.data.count > 1) {
					num.textContent = res.data.count;
					el.classList.add('visible');
				}
			});
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'ifende_visitor_count_output', 90 );

/**
 * AJAX handler: Record visitor ping and return count.
 *
 * Gated on consent at the endpoint level too — even though the front-end
 * only fires the ping when the badge renders (which itself is gated), a
 * non-consenting client could still POST to /wp-admin/admin-ajax.php
 * directly. Defense in depth.
 */
function ifende_visitor_ping() {
	if ( function_exists( 'ifende_consent_given' ) && ! ifende_consent_given() ) {
		wp_send_json_success( [ 'count' => 0 ] );
	}

	// Use IP hash for privacy (no actual IP stored). isset() guards against
	// CLI/PHPUnit contexts where REMOTE_ADDR may not be set; wp_unslash + the
	// hash hop satisfy WPCS sanitisation rules even though md5() makes the
	// raw value irrelevant.
	$remote_addr = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
	$visitor_id  = md5( $remote_addr . wp_date( 'Y-m-d-H' ) );
	$key        = 'ifende_visitors';
	$visitors   = get_transient( $key );

	if ( ! is_array( $visitors ) ) {
		$visitors = [];
	}

	// Add/update this visitor with current timestamp.
	$visitors[ $visitor_id ] = time();

	// Remove visitors older than 5 minutes.
	$cutoff = time() - 300;
	$visitors = array_filter( $visitors, function( $t ) use ( $cutoff ) {
		return $t > $cutoff;
	} );

	set_transient( $key, $visitors, 600 );

	wp_send_json_success( [ 'count' => count( $visitors ) ] );
}
add_action( 'wp_ajax_ifende_visitor_ping', 'ifende_visitor_ping' );
add_action( 'wp_ajax_nopriv_ifende_visitor_ping', 'ifende_visitor_ping' );
