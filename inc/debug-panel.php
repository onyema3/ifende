<?php
/**
 * Debug Mode Panel — Admin-only floating panel showing dev info.
 *
 * Only visible when WP_DEBUG is true AND user is a logged-in admin.
 * Shows: current template, memory usage, query count, load time.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the debug panel in the footer (admin-only, WP_DEBUG only).
 */
function ifende_debug_panel() {
	if ( ! defined( 'WP_DEBUG' ) || ! WP_DEBUG ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( is_admin() ) {
		return;
	}

	global $template, $wpdb;

	$memory     = size_format( memory_get_peak_usage( true ) );
	$queries    = $wpdb->num_queries;
	$load_time  = timer_stop( 0, 3 );
	$tpl_name   = basename( $template );
	$theme      = wp_get_theme();
	$wp_version = get_bloginfo( 'version' );
	$php_ver    = phpversion();
	?>
	<div class="ifende-debug-panel" id="ifendeDebugPanel">
		<button class="ifende-debug-toggle" id="ifendeDebugToggle" aria-label="<?php esc_attr_e( 'Toggle debug panel', 'ifende' ); ?>">&#9881;</button>
		<div class="ifende-debug-content" id="ifendeDebugContent" style="display:none;">
			<div class="ifende-debug-title"><?php esc_html_e( 'Debug Info', 'ifende' ); ?></div>
			<table class="ifende-debug-table">
				<tr><td><?php esc_html_e( 'Template', 'ifende' ); ?></td><td><code><?php echo esc_html( $tpl_name ); ?></code></td></tr>
				<tr><td><?php esc_html_e( 'Memory', 'ifende' ); ?></td><td><?php echo esc_html( $memory ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Queries', 'ifende' ); ?></td><td><?php echo esc_html( $queries ); ?></td></tr>
				<tr><td><?php esc_html_e( 'Load Time', 'ifende' ); ?></td><td><?php echo esc_html( $load_time ); ?>s</td></tr>
				<tr><td><?php esc_html_e( 'Theme', 'ifende' ); ?></td><td><?php echo esc_html( $theme->get( 'Name' ) . ' ' . $theme->get( 'Version' ) ); ?></td></tr>
				<tr><td><?php esc_html_e( 'WordPress', 'ifende' ); ?></td><td><?php echo esc_html( $wp_version ); ?></td></tr>
				<tr><td><?php esc_html_e( 'PHP', 'ifende' ); ?></td><td><?php echo esc_html( $php_ver ); ?></td></tr>
			</table>
		</div>
	</div>
	<style>
	.ifende-debug-panel{position:fixed;bottom:80px;left:16px;z-index:99990;font-family:'DM Mono',monospace;font-size:0.65rem;}
	.ifende-debug-toggle{width:32px;height:32px;border-radius:50%;background:var(--black,#0A0A0A);border:1px solid var(--border,rgba(245,242,236,0.12));color:var(--grey,#8A8A8A);cursor:pointer;display:flex;align-items:center;justify-content:center;font-size:1rem;transition:all .2s;}
	.ifende-debug-toggle:hover{border-color:var(--green,#21A14E);color:var(--green,#21A14E);}
	.ifende-debug-content{position:absolute;bottom:40px;left:0;background:var(--black,#0A0A0A);border:1px solid var(--border,rgba(245,242,236,0.12));border-radius:4px;padding:12px 16px;min-width:220px;box-shadow:0 4px 20px rgba(0,0,0,0.4);}
	.ifende-debug-title{font-size:0.6rem;letter-spacing:2px;text-transform:uppercase;color:var(--green,#21A14E);margin-bottom:8px;}
	.ifende-debug-table{width:100%;border-collapse:collapse;}
	.ifende-debug-table td{padding:3px 0;color:var(--white,#F5F2EC);border-bottom:1px solid var(--border,rgba(245,242,236,0.12));}
	.ifende-debug-table td:first-child{color:var(--grey,#8A8A8A);padding-right:12px;white-space:nowrap;}
	.ifende-debug-table code{background:rgba(33,161,78,0.1);padding:1px 4px;border-radius:2px;color:var(--green,#21A14E);}
	</style>
	<script>
	(function(){
		var btn = document.getElementById('ifendeDebugToggle');
		var content = document.getElementById('ifendeDebugContent');
		if (btn && content) {
			btn.addEventListener('click', function() {
				content.style.display = content.style.display === 'none' ? 'block' : 'none';
			});
		}
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'ifende_debug_panel', 999 );
