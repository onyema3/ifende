<?php
/**
 * Legal Pages — Auto-generate Privacy Policy and Terms & Conditions pages.
 *
 * Creates draft pages on theme activation with starter content.
 * Adds Customizer links for easy editing.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Create Privacy Policy and T&C pages on theme activation if they don't exist.
 */
function ifende_create_legal_pages() {
	// Privacy Policy.
	$privacy_id = get_option( 'ifende_privacy_page_id' );
	if ( ! $privacy_id || ! get_post( $privacy_id ) ) {
		$privacy_id = wp_insert_post( [
			'post_title'   => __( 'Privacy Policy', 'ifende' ),
			'post_content' => ifende_privacy_policy_content(),
			'post_status'  => 'draft',
			'post_type'    => 'page',
		] );
		if ( $privacy_id && ! is_wp_error( $privacy_id ) ) {
			update_option( 'ifende_privacy_page_id', $privacy_id );
			// Set as WP Privacy Policy page.
			update_option( 'wp_page_for_privacy_policy', $privacy_id );
		}
	}

	// Terms & Conditions.
	$terms_id = get_option( 'ifende_terms_page_id' );
	if ( ! $terms_id || ! get_post( $terms_id ) ) {
		$terms_id = wp_insert_post( [
			'post_title'   => __( 'Terms & Conditions', 'ifende' ),
			'post_content' => ifende_terms_content(),
			'post_status'  => 'draft',
			'post_type'    => 'page',
		] );
		if ( $terms_id && ! is_wp_error( $terms_id ) ) {
			update_option( 'ifende_terms_page_id', $terms_id );
		}
	}
}
add_action( 'after_switch_theme', 'ifende_create_legal_pages' );

/**
 * Show admin notice about draft legal pages.
 */
function ifende_legal_pages_notice() {
	$privacy_id = get_option( 'ifende_privacy_page_id' );
	$terms_id   = get_option( 'ifende_terms_page_id' );

	$drafts = [];
	if ( $privacy_id && get_post_status( $privacy_id ) === 'draft' ) {
		$drafts[] = '<a href="' . esc_url( get_edit_post_link( $privacy_id ) ) . '">' . esc_html__( 'Privacy Policy', 'ifende' ) . '</a>';
	}
	if ( $terms_id && get_post_status( $terms_id ) === 'draft' ) {
		$drafts[] = '<a href="' . esc_url( get_edit_post_link( $terms_id ) ) . '">' . esc_html__( 'Terms & Conditions', 'ifende' ) . '</a>';
	}

	if ( empty( $drafts ) ) {
		return;
	}

	// $drafts entries each contain an already-escaped <a> tag (esc_url +
	// esc_html__ above). Compose the message with __() and run the
	// concatenated result through wp_kses_post so anchors survive but
	// any other HTML would be stripped.
	$message = sprintf(
		/* translators: %s: comma-separated list of page links */
		__( 'Ifende created draft legal pages for you: %s. Review and publish them when ready.', 'ifende' ),
		implode( ', ', $drafts )
	);

	echo '<div class="notice notice-info is-dismissible"><p>' . wp_kses_post( $message ) . '</p></div>';
}
add_action( 'admin_notices', 'ifende_legal_pages_notice' );

/**
 * Privacy Policy starter content.
 *
 * @return string Page content.
 */
function ifende_privacy_policy_content() {
	$site_name = get_bloginfo( 'name' );
	$email     = get_theme_mod( 'ifende_email', 'hello@ifende.com' );

	return <<<CONTENT
<!-- wp:heading -->
<h2>Introduction</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>At {$site_name}, we take your privacy seriously. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Information We Collect</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We may collect personal information that you voluntarily provide to us when you:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Fill out a contact form</li><li>Subscribe to our newsletter</li><li>Submit a project inquiry</li><li>Leave a comment on a blog post</li></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>This information may include your name, email address, phone number, and any message you provide.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Cookies and Tracking</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We use cookies and similar tracking technologies to enhance your browsing experience. These include:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li><strong>Essential cookies</strong> — Required for basic site functionality (theme preference, cookie consent)</li><li><strong>Analytics cookies</strong> — Help us understand how visitors use our site (Google Analytics)</li><li><strong>Third-party cookies</strong> — Set by services like Google Fonts, live chat widgets</li></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>You can manage your cookie preferences through our cookie consent banner or your browser settings.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>How We Use Your Information</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>To respond to your inquiries and provide services</li><li>To send newsletters (only if you opted in)</li><li>To improve our website and user experience</li><li>To comply with legal obligations</li></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Third-Party Services</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We may use third-party services that collect information, including:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Google Analytics (website analytics)</li><li>Google Fonts (typography)</li><li>Formspree / Web3Forms (contact form processing)</li><li>Tawk.to / Crisp / WhatsApp (live chat)</li><li>Calendly / Cal.com (appointment booking)</li></ul>
<!-- /wp:list -->

<!-- wp:paragraph -->
<p>Each of these services has their own privacy policy governing how they use your data.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Data Retention</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We retain your personal information only for as long as necessary to fulfill the purposes outlined in this policy, unless a longer retention period is required by law.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Your Rights</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Depending on your location, you may have the right to:</p>
<!-- /wp:paragraph -->

<!-- wp:list -->
<ul><li>Access the personal data we hold about you</li><li>Request correction of inaccurate data</li><li>Request deletion of your data</li><li>Withdraw consent for data processing</li><li>Lodge a complaint with a supervisory authority</li></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Contact Us</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>If you have questions about this Privacy Policy, please contact us at: <a href="mailto:{$email}">{$email}</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><em>Last updated: {CURRENT_DATE}</em></p>
<!-- /wp:paragraph -->
CONTENT;
}

/**
 * Terms & Conditions starter content.
 *
 * @return string Page content.
 */
function ifende_terms_content() {
	$site_name = get_bloginfo( 'name' );
	$email     = get_theme_mod( 'ifende_email', 'hello@ifende.com' );

	return <<<CONTENT
<!-- wp:heading -->
<h2>Agreement to Terms</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>By accessing and using the {$site_name} website, you agree to be bound by these Terms and Conditions. If you do not agree with any part of these terms, please do not use our website.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Services</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$site_name} provides professional services including but not limited to web development, project management, consulting, branding, and game development. Specific terms for individual projects will be outlined in separate agreements or proposals.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Intellectual Property</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>All content on this website — including text, graphics, logos, images, code, and design — is the property of {$site_name} and is protected by copyright and intellectual property laws. You may not reproduce, distribute, or use any content without prior written permission.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>User Submissions</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>When you submit information through our contact forms, project inquiries, or testimonials, you grant us permission to use that information for the purposes stated (responding to inquiries, displaying testimonials, etc.).</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Project Terms</h2>
<!-- /wp:heading -->

<!-- wp:list -->
<ul><li>Project timelines, deliverables, and pricing will be agreed upon in writing before work begins</li><li>Payment terms will be specified in individual project proposals</li><li>Either party may terminate a project with written notice, subject to payment for work completed</li><li>Intellectual property for completed projects transfers to the client upon full payment</li></ul>
<!-- /wp:list -->

<!-- wp:heading -->
<h2>Limitation of Liability</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>{$site_name} shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the website or our services. Our total liability shall not exceed the amount paid for the specific service in question.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Third-Party Links</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Our website may contain links to third-party websites. We are not responsible for the content, privacy practices, or terms of use of any linked sites.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Modifications</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>We reserve the right to modify these Terms and Conditions at any time. Changes will be effective immediately upon posting to this page. Your continued use of the website constitutes acceptance of the revised terms.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Governing Law</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>These Terms and Conditions shall be governed by and construed in accordance with the laws of the Federal Republic of Nigeria, without regard to conflict of law principles.</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2>Contact</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>For questions about these Terms and Conditions, contact us at: <a href="mailto:{$email}">{$email}</a></p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p><em>Last updated: {CURRENT_DATE}</em></p>
<!-- /wp:paragraph -->
CONTENT;
}
