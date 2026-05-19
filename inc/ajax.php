<?php
/**
 * AJAX Handlers
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Handle contact form submissions via AJAX.
 * Includes basic rate limiting using transients.
 */
function ifende_handle_contact() {
  check_ajax_referer( 'ifende_nonce', 'nonce' );

  // Basic rate limiting: 1 submission per IP per 60 seconds.
  $ip_hash       = md5( $_SERVER['REMOTE_ADDR'] ?? 'unknown' );
  $transient_key = 'ifende_contact_' . $ip_hash;

  if ( get_transient( $transient_key ) ) {
    wp_send_json_error( [ 'message' => __( 'Please wait before sending another message.', 'ifende' ) ] );
  }

  $name    = sanitize_text_field( $_POST['name'] ?? '' );
  $email   = sanitize_email( $_POST['email'] ?? '' );
  $subject = sanitize_text_field( $_POST['subject'] ?? 'Portfolio Enquiry' );
  $message = sanitize_textarea_field( $_POST['message'] ?? '' );

  if ( ! $email || ! $message ) {
    wp_send_json_error( [ 'message' => __( 'Required fields missing.', 'ifende' ) ] );
  }

  $to      = get_theme_mod( 'ifende_email', 'hello@ifende.com' );
  $headers = [
    'Content-Type: text/html; charset=UTF-8',
    'Reply-To: ' . $name . ' <' . $email . '>',
  ];
  $body = sprintf(
    '<p><strong>From:</strong> %s &lt;%s&gt;</p><p><strong>Subject:</strong> %s</p><p><strong>Message:</strong><br>%s</p>',
    esc_html( $name ),
    esc_html( $email ),
    esc_html( $subject ),
    nl2br( esc_html( $message ) )
  );

  $sent = wp_mail( $to, $subject, $body, $headers );

  if ( $sent ) {
    // Set rate limit transient for 60 seconds.
    set_transient( $transient_key, true, 60 );
    wp_send_json_success();
  } else {
    wp_send_json_error( [ 'message' => __( 'Failed to send.', 'ifende' ) ] );
  }
}
add_action( 'wp_ajax_nopriv_ifende_contact', 'ifende_handle_contact' );
add_action( 'wp_ajax_ifende_contact', 'ifende_handle_contact' );



/**
 * Provide contact email via AJAX (avoids exposing it in page source).
 */
function ifende_get_email() {
  check_ajax_referer( 'ifende_nonce', 'nonce' );
  $email = get_theme_mod( 'ifende_email', 'hello@ifende.com' );
  wp_send_json_success( [ 'email' => sanitize_email( $email ) ] );
}
add_action( 'wp_ajax_nopriv_ifende_get_email', 'ifende_get_email' );
add_action( 'wp_ajax_ifende_get_email', 'ifende_get_email' );
