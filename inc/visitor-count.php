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
 */
function ifende_visitor_count_output() {
	if ( is_admin() || current_user_can( 'manage_options' ) ) {
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
 */
function ifende_visitor_ping() {
	// Use IP hash for privacy (no actual IP stored).
	$visitor_id = md5( $_SERVER['REMOTE_ADDR'] . wp_date( 'Y-m-d-H' ) );
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
