<?php
/**
 * Regenerate Thumbnails — Admin tool to bulk-rebuild image subsizes.
 *
 * Adds a "Tools > Regenerate Thumbnails" page that walks the entire Media
 * Library and re-runs WordPress's subsize generation for each image
 * attachment. Useful after:
 *
 *   - Registering a new image size via add_image_size()
 *   - Changing the Settings > Media dimensions
 *   - Installing a plugin (e.g. ShortPixel, EWWW) that wants WebP
 *     versions for every existing size
 *   - Recovering from a failed bulk import where some thumbnails
 *     never finished generating
 *
 * Originals are never touched; only the subsize derivatives are rewritten.
 *
 * Implementation notes:
 *   - Batched via AJAX (5 attachments per request) to stay under
 *     max_execution_time on hosts with strict timeouts. The browser
 *     pipelines requests until processed >= total.
 *   - Uses wp_create_image_subsizes() — the canonical WP function that
 *     also rewrites the attachment metadata. wp_generate_attachment_metadata
 *     would also work but is older and doesn't update the post_meta in
 *     the same call.
 *   - Capability gated behind 'manage_options' + nonce verified on every
 *     batch.
 *
 * @package Ifende
 * @since   1.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Number of attachments processed per AJAX batch. Conservative default
 * to keep each request well under typical 30 s timeouts even when source
 * images are large. Filter to tune for your host.
 *
 * @return int
 */
function ifende_regen_batch_size() {
	/**
	 * Filter the number of attachments processed per AJAX batch.
	 *
	 * @since 1.5.0
	 *
	 * @param int $size Default 5.
	 */
	return (int) apply_filters( 'ifende_regen_batch_size', 5 );
}

/**
 * Register the Tools submenu page.
 */
function ifende_regen_register_menu() {
	add_management_page(
		esc_html__( 'Regenerate Thumbnails', 'ifende' ),
		esc_html__( 'Regenerate Thumbnails', 'ifende' ),
		'manage_options',
		'ifende-regenerate-thumbnails',
		'ifende_regen_render_page'
	);
}
add_action( 'admin_menu', 'ifende_regen_register_menu' );

/**
 * Count total image attachments in the library.
 *
 * @return int
 */
function ifende_regen_count_images() {
	$query = new WP_Query(
		[
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'posts_per_page' => 1,
			'fields'         => 'ids',
			'no_found_rows'  => false,
			'cache_results'  => false,
		]
	);
	return (int) $query->found_posts;
}

/**
 * Render the admin page.
 */
function ifende_regen_render_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have permission to access this page.', 'ifende' ) );
	}

	$total      = ifende_regen_count_images();
	$batch_size = ifende_regen_batch_size();
	$sizes      = wp_get_registered_image_subsizes();
	?>
	<div class="wrap ifende-regen-wrap">
		<h1><?php esc_html_e( 'Regenerate Thumbnails', 'ifende' ); ?></h1>

		<p class="description" style="max-width:760px;">
			<?php esc_html_e( 'Walks the entire Media Library and rebuilds the thumbnail (subsize) variants for every image attachment using the currently registered image sizes. Originals are never modified; only the cropped/resized derivatives are rewritten.', 'ifende' ); ?>
		</p>

		<h2 style="margin-top:32px;"><?php esc_html_e( 'What will be regenerated', 'ifende' ); ?></h2>
		<p>
			<?php
			printf(
				/* translators: %s: number of image attachments */
				esc_html( _n( '%s image attachment will be processed.', '%s image attachments will be processed.', $total, 'ifende' ) ),
				'<strong id="ifende-regen-total">' . esc_html( number_format_i18n( $total ) ) . '</strong>'
			);
			?>
		</p>

		<?php if ( ! empty( $sizes ) ) : ?>
		<details style="margin:12px 0 24px;">
			<summary style="cursor:pointer;"><?php esc_html_e( 'Registered image sizes', 'ifende' ); ?></summary>
			<table class="widefat striped" style="max-width:520px;margin-top:12px;">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Size', 'ifende' ); ?></th>
						<th><?php esc_html_e( 'Width', 'ifende' ); ?></th>
						<th><?php esc_html_e( 'Height', 'ifende' ); ?></th>
						<th><?php esc_html_e( 'Crop', 'ifende' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ( $sizes as $name => $details ) : ?>
						<tr>
							<td><code><?php echo esc_html( $name ); ?></code></td>
							<td><?php echo esc_html( isset( $details['width'] ) ? (int) $details['width'] : 0 ); ?></td>
							<td><?php echo esc_html( isset( $details['height'] ) ? (int) $details['height'] : 0 ); ?></td>
							<td><?php echo ! empty( $details['crop'] ) ? esc_html__( 'yes', 'ifende' ) : esc_html__( 'no', 'ifende' ); ?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</details>
		<?php endif; ?>

		<form id="ifende-regen-form" onsubmit="return false;">
			<?php wp_nonce_field( 'ifende_regen', 'ifende_regen_nonce' ); ?>
			<p>
				<button type="button" id="ifende-regen-start" class="button button-primary button-hero" <?php disabled( 0, $total ); ?>>
					<?php esc_html_e( 'Start Regeneration', 'ifende' ); ?>
				</button>
				<button type="button" id="ifende-regen-cancel" class="button button-secondary" disabled>
					<?php esc_html_e( 'Cancel', 'ifende' ); ?>
				</button>
				<?php if ( 0 === $total ) : ?>
					<span style="margin-left:12px;color:#646970;"><?php esc_html_e( 'No image attachments found.', 'ifende' ); ?></span>
				<?php endif; ?>
			</p>
		</form>

		<div id="ifende-regen-progress" style="display:none;margin-top:24px;max-width:760px;">
			<div class="ifende-regen-bar"><div class="ifende-regen-fill" style="width:0%;"></div></div>
			<p style="margin-top:8px;">
				<strong id="ifende-regen-percent">0%</strong>
				<span class="ifende-regen-sep">&middot;</span>
				<span id="ifende-regen-status"><?php esc_html_e( 'Initialising&hellip;', 'ifende' ); ?></span>
			</p>
			<details style="margin-top:8px;">
				<summary style="cursor:pointer;"><?php esc_html_e( 'Activity log', 'ifende' ); ?></summary>
				<pre id="ifende-regen-log" style="background:#f6f7f7;padding:12px;max-height:280px;overflow:auto;border:1px solid #dcdcde;border-radius:4px;font-size:12px;line-height:1.5;"></pre>
			</details>
		</div>

		<style>
			.ifende-regen-bar { width:100%; height:18px; background:#dcdcde; border-radius:4px; overflow:hidden; }
			.ifende-regen-fill { height:100%; background:#21A14E; transition:width .25s ease; }
			.ifende-regen-sep { color:#a7aaad; margin:0 6px; }
		</style>

		<script>
		(function () {
			var startBtn  = document.getElementById('ifende-regen-start');
			var cancelBtn = document.getElementById('ifende-regen-cancel');
			var form      = document.getElementById('ifende-regen-form');
			var progress  = document.getElementById('ifende-regen-progress');
			var fill      = progress.querySelector('.ifende-regen-fill');
			var pctEl     = document.getElementById('ifende-regen-percent');
			var statusEl  = document.getElementById('ifende-regen-status');
			var logEl     = document.getElementById('ifende-regen-log');

			var page       = 1;
			var processed  = 0;
			var total      = parseInt(document.getElementById('ifende-regen-total').textContent.replace(/[^0-9]/g, ''), 10) || 0;
			var cancelled  = false;

			startBtn.addEventListener('click', function () {
				if (!total) return;
				cancelled = false;
				page = 1;
				processed = 0;
				logEl.textContent = '';
				progress.style.display = 'block';
				startBtn.disabled = true;
				cancelBtn.disabled = false;
				statusEl.textContent = <?php echo wp_json_encode( __( 'Starting…', 'ifende' ) ); ?>;
				runBatch();
			});

			cancelBtn.addEventListener('click', function () {
				cancelled = true;
				statusEl.textContent = <?php echo wp_json_encode( __( 'Cancelling after current batch…', 'ifende' ) ); ?>;
			});

			function runBatch() {
				var data = new FormData(form);
				data.append('action', 'ifende_regen_batch');
				data.append('page', page);

				fetch(ajaxurl, { method: 'POST', body: data, credentials: 'same-origin' })
					.then(function (r) { return r.json(); })
					.then(function (res) {
						if (!res || !res.success) {
							throw new Error((res && res.data && res.data.message) || <?php echo wp_json_encode( __( 'Server error', 'ifende' ) ); ?>);
						}
						processed = res.data.processed;
						total     = res.data.total;
						var pct   = total ? Math.round((processed / total) * 100) : 100;
						fill.style.width = pct + '%';
						pctEl.textContent = pct + '%';
						statusEl.textContent = <?php echo wp_json_encode( __( 'Processed', 'ifende' ) ); ?> + ' ' + processed + ' / ' + total;

						if (res.data.log && res.data.log.length) {
							res.data.log.forEach(function (line) { logEl.textContent += line + '\n'; });
							logEl.scrollTop = logEl.scrollHeight;
						}

						if (cancelled) {
							statusEl.textContent = <?php echo wp_json_encode( __( 'Cancelled.', 'ifende' ) ); ?> + ' ' + processed + ' / ' + total;
							startBtn.disabled = false;
							cancelBtn.disabled = true;
							return;
						}

						if (processed < total) {
							page++;
							runBatch();
						} else {
							statusEl.textContent = <?php echo wp_json_encode( __( 'Done.', 'ifende' ) ); ?> + ' ' + total + ' / ' + total;
							startBtn.disabled = false;
							cancelBtn.disabled = true;
						}
					})
					.catch(function (err) {
						statusEl.textContent = <?php echo wp_json_encode( __( 'Error:', 'ifende' ) ); ?> + ' ' + err.message;
						startBtn.disabled = false;
						cancelBtn.disabled = true;
					});
			}
		})();
		</script>
	</div>
	<?php
}

/**
 * AJAX handler — process one batch of attachments.
 *
 * Each call processes up to ifende_regen_batch_size() attachments,
 * starting at page = $_POST['page'] (1-indexed). Returns the running
 * processed/total counters plus a per-attachment log so the client can
 * render progress and a per-image audit trail.
 */
function ifende_regen_ajax_batch() {
	check_ajax_referer( 'ifende_regen', 'ifende_regen_nonce' );
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_send_json_error( [ 'message' => __( 'Permission denied.', 'ifende' ) ], 403 );
	}

	$page       = isset( $_POST['page'] ) ? max( 1, absint( wp_unslash( $_POST['page'] ) ) ) : 1;
	$batch_size = ifende_regen_batch_size();

	$query = new WP_Query(
		[
			'post_type'      => 'attachment',
			'post_status'    => 'inherit',
			'post_mime_type' => 'image',
			'posts_per_page' => $batch_size,
			'paged'          => $page,
			'orderby'        => 'ID',
			'order'          => 'ASC',
			'fields'         => 'ids',
			'no_found_rows'  => false,
			'cache_results'  => false,
		]
	);

	$total           = (int) $query->found_posts;
	$batch           = $query->posts;
	$processed_so_far = ( $page - 1 ) * $batch_size + count( $batch );
	if ( $processed_so_far > $total ) {
		$processed_so_far = $total;
	}

	$log = [];

	// wp_create_image_subsizes() is loaded from wp-admin/includes/image.php
	// in the admin context (always loaded for admin-ajax.php), but require
	// it explicitly to keep this handler robust if WP changes loader order.
	if ( ! function_exists( 'wp_create_image_subsizes' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}

	foreach ( $batch as $att_id ) {
		$file = get_attached_file( $att_id );
		if ( ! $file || ! file_exists( $file ) ) {
			$log[] = sprintf(
				/* translators: %d: attachment ID */
				__( 'Skipped #%d (file missing on disk)', 'ifende' ),
				$att_id
			);
			continue;
		}

		$result = wp_create_image_subsizes( $file, $att_id );

		if ( is_wp_error( $result ) ) {
			$log[] = sprintf(
				/* translators: 1: attachment ID, 2: error message */
				__( 'Error #%1$d: %2$s', 'ifende' ),
				$att_id,
				$result->get_error_message()
			);
		} else {
			$log[] = sprintf(
				/* translators: 1: attachment ID, 2: file basename */
				__( 'Regenerated #%1$d (%2$s)', 'ifende' ),
				$att_id,
				wp_basename( $file )
			);
		}
	}

	wp_send_json_success(
		[
			'processed' => $processed_so_far,
			'total'     => $total,
			'log'       => $log,
		]
	);
}
add_action( 'wp_ajax_ifende_regen_batch', 'ifende_regen_ajax_batch' );
