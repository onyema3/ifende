<?php
/**
 * Testimonial Request System — Admin tool to email clients a submission link.
 *
 * Adds an admin page where you can send a branded email to past clients
 * with a link to submit a testimonial. Submissions create draft CPT entries.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the admin page.
 */
function ifende_testimonial_request_menu() {
	add_submenu_page(
		'edit.php?post_type=ifende_testimonial',
		esc_html__( 'Request Testimonial', 'ifende' ),
		esc_html__( 'Request', 'ifende' ),
		'manage_options',
		'ifende-testimonial-request',
		'ifende_testimonial_request_page'
	);
}
add_action( 'admin_menu', 'ifende_testimonial_request_menu' );

/**
 * Handle sending the request email.
 */
function ifende_testimonial_request_handler() {
	if ( ! isset( $_POST['ifende_send_request'] ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['_ifende_request_nonce'] ?? '', 'ifende_testimonial_request' ) ) {
		return;
	}

	$client_name  = sanitize_text_field( $_POST['client_name'] ?? '' );
	$client_email = sanitize_email( $_POST['client_email'] ?? '' );
	$project_name = sanitize_text_field( $_POST['project_name'] ?? '' );

	if ( empty( $client_name ) || empty( $client_email ) ) {
		add_settings_error( 'ifende_testimonial_request', 'missing', esc_html__( 'Client name and email are required.', 'ifende' ), 'error' );
		return;
	}

	// Generate a unique token for the submission link.
	$token = wp_generate_password( 32, false );
	set_transient( 'ifende_testimonial_token_' . $token, [
		'name'    => $client_name,
		'email'   => $client_email,
		'project' => $project_name,
	], 30 * DAY_IN_SECONDS );

	// Build the submission URL.
	$submit_url = add_query_arg( [
		'ifende_testimonial' => '1',
		'token'              => $token,
	], home_url( '/' ) );

	// Send the email.
	$site_name = get_bloginfo( 'name' );
	$subject   = sprintf( __( '%s — We\'d love your feedback!', 'ifende' ), $site_name );
	$body      = sprintf(
		__( "Hi %s,\n\nThank you for working with us" . ( $project_name ? " on %s" : "%s" ) . "! We'd love to hear about your experience.\n\nPlease take a moment to share a brief testimonial:\n\n%s\n\nIt only takes a minute and helps us grow.\n\nThank you!\n— %s", 'ifende' ),
		$client_name,
		$project_name,
		$submit_url,
		$site_name
	);
	$headers = [ 'Content-Type: text/plain; charset=UTF-8' ];

	$sent = wp_mail( $client_email, $subject, $body, $headers );

	if ( $sent ) {
		add_settings_error( 'ifende_testimonial_request', 'sent', sprintf( esc_html__( 'Request sent to %s!', 'ifende' ), $client_email ), 'success' );
	} else {
		add_settings_error( 'ifende_testimonial_request', 'failed', esc_html__( 'Failed to send email. Check your server mail settings.', 'ifende' ), 'error' );
	}
}
add_action( 'admin_init', 'ifende_testimonial_request_handler' );

/**
 * Render the request page.
 */
function ifende_testimonial_request_page() {
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Request a Testimonial', 'ifende' ); ?></h1>
		<p><?php esc_html_e( 'Send a branded email to a past client asking them to submit a testimonial. Their submission will appear as a draft for your review.', 'ifende' ); ?></p>

		<?php settings_errors( 'ifende_testimonial_request' ); ?>

		<form method="post" style="max-width:500px;margin-top:24px;">
			<?php wp_nonce_field( 'ifende_testimonial_request', '_ifende_request_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th><label for="client_name"><?php esc_html_e( 'Client Name', 'ifende' ); ?></label></th>
					<td><input type="text" id="client_name" name="client_name" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="client_email"><?php esc_html_e( 'Client Email', 'ifende' ); ?></label></th>
					<td><input type="email" id="client_email" name="client_email" class="regular-text" required></td>
				</tr>
				<tr>
					<th><label for="project_name"><?php esc_html_e( 'Project Name (optional)', 'ifende' ); ?></label></th>
					<td><input type="text" id="project_name" name="project_name" class="regular-text"></td>
				</tr>
			</table>
			<p>
				<button type="submit" name="ifende_send_request" class="button button-primary"><?php esc_html_e( 'Send Request Email', 'ifende' ); ?></button>
			</p>
		</form>
	</div>
	<?php
}

/**
 * Handle the front-end testimonial submission form.
 */
function ifende_testimonial_submission_form() {
	if ( ! isset( $_GET['ifende_testimonial'] ) || ! isset( $_GET['token'] ) ) {
		return;
	}

	$token = sanitize_text_field( $_GET['token'] );
	$data  = get_transient( 'ifende_testimonial_token_' . $token );

	if ( ! $data ) {
		// Expired or invalid token — show on the page but don't process.
		return;
	}

	// Handle submission.
	if ( isset( $_POST['ifende_submit_testimonial'] ) && wp_verify_nonce( $_POST['_testimonial_nonce'] ?? '', 'ifende_submit_testimonial' ) ) {
		$quote = sanitize_textarea_field( $_POST['testimonial_quote'] ?? '' );
		$role  = sanitize_text_field( $_POST['testimonial_role'] ?? '' );

		if ( ! empty( $quote ) ) {
			$post_id = wp_insert_post( [
				'post_type'    => 'ifende_testimonial',
				'post_title'   => $data['name'],
				'post_content' => $quote,
				'post_status'  => 'draft',
			] );

			if ( $post_id && ! is_wp_error( $post_id ) ) {
				update_post_meta( $post_id, '_ifende_testimonial_role', $role );
				delete_transient( 'ifende_testimonial_token_' . $token );

				// Show thank you message and exit early.
				ifende_testimonial_thank_you_page( $data['name'] );
				exit;
			}
		}
	}

	// Show the submission form.
	ifende_testimonial_submission_page( $data, $token );
	exit;
}
add_action( 'template_redirect', 'ifende_testimonial_submission_form' );

/**
 * Render the testimonial submission page.
 */
function ifende_testimonial_submission_page( $data, $token ) {
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo esc_html__( 'Submit Testimonial', 'ifende' ) . ' — ' . get_bloginfo( 'name' ); ?></title>
		<style>
			:root{--black:#0A0A0A;--white:#F5F2EC;--green:#21A14E;--grey:#8A8A8A;--border:rgba(245,242,236,0.12);}
			*{margin:0;padding:0;box-sizing:border-box;}
			body{font-family:system-ui,-apple-system,sans-serif;background:var(--black);color:var(--white);min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;}
			.testimonial-form-wrap{max-width:520px;width:100%;}
			h1{font-size:1.8rem;font-weight:300;margin-bottom:8px;}
			p{color:var(--grey);margin-bottom:24px;line-height:1.6;}
			label{display:block;font-size:0.72rem;letter-spacing:2px;text-transform:uppercase;color:var(--green);margin-bottom:6px;}
			textarea,input{width:100%;background:rgba(245,242,236,0.04);border:1px solid var(--border);border-radius:2px;padding:14px 16px;font-size:0.9rem;color:var(--white);margin-bottom:16px;outline:none;font-family:inherit;}
			textarea:focus,input:focus{border-color:var(--green);}
			textarea{min-height:140px;resize:vertical;}
			button{background:var(--green);color:var(--black);border:none;padding:14px 30px;border-radius:2px;font-size:0.72rem;font-weight:700;letter-spacing:2px;text-transform:uppercase;cursor:pointer;}
			button:hover{opacity:0.9;}
		</style>
	</head>
	<body>
		<div class="testimonial-form-wrap">
			<h1><?php printf( esc_html__( 'Hi %s!', 'ifende' ), esc_html( $data['name'] ) ); ?></h1>
			<p><?php esc_html_e( 'We\'d love to hear about your experience working with us. Your testimonial helps others make informed decisions.', 'ifende' ); ?></p>

			<form method="post">
				<?php wp_nonce_field( 'ifende_submit_testimonial', '_testimonial_nonce' ); ?>
				<label for="testimonial_role"><?php esc_html_e( 'Your Title / Company', 'ifende' ); ?></label>
				<input type="text" id="testimonial_role" name="testimonial_role" placeholder="<?php esc_attr_e( 'e.g., CEO, Company Name', 'ifende' ); ?>">

				<label for="testimonial_quote"><?php esc_html_e( 'Your Testimonial', 'ifende' ); ?></label>
				<textarea id="testimonial_quote" name="testimonial_quote" required placeholder="<?php esc_attr_e( 'Share your experience...', 'ifende' ); ?>"></textarea>

				<button type="submit" name="ifende_submit_testimonial"><?php esc_html_e( 'Submit Testimonial', 'ifende' ); ?></button>
			</form>
		</div>
	</body>
	</html>
	<?php
}

/**
 * Thank you page after submission.
 */
function ifende_testimonial_thank_you_page( $name ) {
	?>
	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title><?php echo esc_html__( 'Thank You!', 'ifende' ) . ' — ' . get_bloginfo( 'name' ); ?></title>
		<style>
			:root{--black:#0A0A0A;--white:#F5F2EC;--green:#21A14E;}
			*{margin:0;padding:0;box-sizing:border-box;}
			body{font-family:system-ui,sans-serif;background:var(--black);color:var(--white);min-height:100vh;display:flex;align-items:center;justify-content:center;text-align:center;padding:24px;}
			h1{font-size:2rem;font-weight:300;margin-bottom:16px;}
			p{color:#8A8A8A;font-size:1rem;line-height:1.6;}
			.check{font-size:3rem;color:var(--green);margin-bottom:16px;}
		</style>
	</head>
	<body>
		<div>
			<div class="check">&#10003;</div>
			<h1><?php printf( esc_html__( 'Thank you, %s!', 'ifende' ), esc_html( $name ) ); ?></h1>
			<p><?php esc_html_e( 'Your testimonial has been submitted and is pending review. We truly appreciate your feedback!', 'ifende' ); ?></p>
		</div>
	</body>
	</html>
	<?php
}
