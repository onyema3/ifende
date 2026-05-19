<?php
/**
 * Project Inquiry Form — Pre-filled form on single project pages.
 *
 * Outputs a "Start a similar project" form at the bottom of single project
 * pages. Auto-fills the project name/category so leads are qualified.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the project inquiry form on single project pages.
 */
function ifende_project_inquiry_form() {
	if ( ! is_singular( 'ifende_project' ) ) {
		return;
	}

	$project_title = get_the_title();
	$cats          = get_the_terms( get_the_ID(), 'project_category' );
	$category_name = ( $cats && ! is_wp_error( $cats ) ) ? $cats[0]->name : '';
	?>
	<section class="project-inquiry" id="project-inquiry">
		<div class="section-label"><?php esc_html_e( 'Interested?', 'ifende' ); ?></div>
		<h2 class="project-inquiry-title"><?php esc_html_e( 'Start a Similar Project', 'ifende' ); ?></h2>
		<p class="project-inquiry-desc"><?php esc_html_e( 'Liked what you see? Tell me about your project and let\'s make it happen.', 'ifende' ); ?></p>

		<form class="project-inquiry-form" id="projectInquiryForm" method="post">
			<input type="hidden" name="action" value="ifende_project_inquiry">
			<input type="hidden" name="nonce" value="<?php echo esc_attr( wp_create_nonce( 'ifende_project_inquiry' ) ); ?>">
			<input type="hidden" name="referral_project" value="<?php echo esc_attr( $project_title ); ?>">

			<div class="form-row">
				<div class="form-group">
					<label for="piq-name"><?php esc_html_e( 'Your Name', 'ifende' ); ?></label>
					<input type="text" id="piq-name" name="client_name" required placeholder="<?php esc_attr_e( 'John Doe', 'ifende' ); ?>">
				</div>
				<div class="form-group">
					<label for="piq-email"><?php esc_html_e( 'Email', 'ifende' ); ?></label>
					<input type="email" id="piq-email" name="client_email" required placeholder="<?php esc_attr_e( 'john@example.com', 'ifende' ); ?>">
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label for="piq-project"><?php esc_html_e( 'Project Type', 'ifende' ); ?></label>
					<input type="text" id="piq-project" name="project_type" value="<?php echo esc_attr( $category_name ); ?>" placeholder="<?php esc_attr_e( 'e.g., Web Development', 'ifende' ); ?>">
				</div>
				<div class="form-group">
					<label for="piq-budget"><?php esc_html_e( 'Budget Range', 'ifende' ); ?></label>
					<input type="text" id="piq-budget" name="budget" placeholder="<?php esc_attr_e( 'e.g., $1,000 - $5,000', 'ifende' ); ?>">
				</div>
			</div>

			<div class="form-group">
				<label for="piq-details"><?php esc_html_e( 'Project Details', 'ifende' ); ?></label>
				<textarea id="piq-details" name="details" rows="4" placeholder="<?php esc_attr_e( 'Tell me briefly about your project goals...', 'ifende' ); ?>"></textarea>
			</div>

			<div class="project-inquiry-ref">
				<span class="project-inquiry-ref-label"><?php esc_html_e( 'Referral:', 'ifende' ); ?></span>
				<span class="project-inquiry-ref-value"><?php echo esc_html( $project_title ); ?></span>
			</div>

			<button type="submit" class="btn-primary" id="piqSubmitBtn"><?php esc_html_e( 'Send Inquiry', 'ifende' ); ?> &rarr;</button>
			<div class="project-inquiry-msg" id="piqMsg" style="display:none;"></div>
		</form>
	</section>
	<script>
	(function(){
		var form = document.getElementById('projectInquiryForm');
		if (!form) return;
		form.addEventListener('submit', function(e){
			e.preventDefault();
			var btn = document.getElementById('piqSubmitBtn');
			var msg = document.getElementById('piqMsg');
			btn.textContent = 'Sending...';
			btn.classList.add('loading');
			var fd = new FormData(form);
			fd.append('action', 'ifende_project_inquiry');
			fetch(ifendeData.ajaxUrl, { method:'POST', body:fd })
				.then(function(r){ return r.json(); })
				.then(function(res){
					if(res.success){
						btn.textContent = 'Sent ✓';
						msg.textContent = 'Thank you! I\'ll get back to you within 24 hours.';
						msg.style.display = 'block';
						msg.style.color = 'var(--green)';
						form.reset();
					} else {
						btn.textContent = 'Send Inquiry →';
						msg.textContent = res.data || 'Something went wrong. Please try again.';
						msg.style.display = 'block';
						msg.style.color = '#e74c3c';
					}
					btn.classList.remove('loading');
				})
				.catch(function(){
					btn.textContent = 'Send Inquiry →';
					btn.classList.remove('loading');
					msg.textContent = 'Network error. Please try again.';
					msg.style.display = 'block';
					msg.style.color = '#e74c3c';
				});
		});
	})();
	</script>
	<?php
}

/**
 * Handle project inquiry AJAX submission.
 */
function ifende_handle_project_inquiry() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_key( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'ifende_project_inquiry' ) ) {
		wp_send_json_error( 'Invalid request.' );
	}

	$name    = isset( $_POST['client_name'] ) ? sanitize_text_field( wp_unslash( $_POST['client_name'] ) ) : '';
	$email   = isset( $_POST['client_email'] ) ? sanitize_email( wp_unslash( $_POST['client_email'] ) ) : '';
	$type    = isset( $_POST['project_type'] ) ? sanitize_text_field( wp_unslash( $_POST['project_type'] ) ) : '';
	$budget  = isset( $_POST['budget'] ) ? sanitize_text_field( wp_unslash( $_POST['budget'] ) ) : '';
	$details = isset( $_POST['details'] ) ? sanitize_textarea_field( wp_unslash( $_POST['details'] ) ) : '';
	$project = isset( $_POST['referral_project'] ) ? sanitize_text_field( wp_unslash( $_POST['referral_project'] ) ) : '';

	if ( empty( $name ) || empty( $email ) ) {
		wp_send_json_error( 'Name and email are required.' );
	}

	$admin_email = get_option( 'admin_email' );
	$subject     = sprintf( '[Project Inquiry] %s — via %s', $name, $project );
	$body        = sprintf(
		"New project inquiry from your portfolio:\n\n" .
		"Name: %s\nEmail: %s\nProject Type: %s\nBudget: %s\n\nDetails:\n%s\n\nReferral Project: %s\n",
		$name, $email, $type, $budget, $details, $project
	);
	$headers = [ 'Reply-To: ' . $name . ' <' . $email . '>' ];

	$sent = wp_mail( $admin_email, $subject, $body, $headers );

	if ( $sent ) {
		wp_send_json_success();
	} else {
		wp_send_json_error( 'Could not send email. Please try again later.' );
	}
}
add_action( 'wp_ajax_ifende_project_inquiry', 'ifende_handle_project_inquiry' );
add_action( 'wp_ajax_nopriv_ifende_project_inquiry', 'ifende_handle_project_inquiry' );
