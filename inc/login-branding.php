<?php
/**
 * Login Page Branding — Style wp-login.php to match the Ifende dark theme.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Enqueue custom login styles.
 */
function ifende_login_styles() {
	?>
	<style>
	body.login {
		background: #0A0A0A;
		font-family: 'Syne', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
	}
	body.login #login {
		padding-top: 8%;
	}
	body.login h1 a {
		background-image: none;
		width: auto;
		height: auto;
		text-indent: 0;
		font-family: 'Cormorant Garamond', serif;
		font-size: 2rem;
		font-weight: 300;
		color: #F5F2EC;
		letter-spacing: 1px;
	}
	body.login h1 a:hover,
	body.login h1 a:focus {
		color: #21A14E;
	}
	body.login form {
		background: rgba(245, 242, 236, 0.03);
		border: 1px solid rgba(245, 242, 236, 0.12);
		border-radius: 4px;
		box-shadow: 0 4px 24px rgba(0, 0, 0, 0.3);
	}
	body.login form label {
		font-family: 'DM Mono', monospace;
		font-size: 0.68rem;
		letter-spacing: 2px;
		text-transform: uppercase;
		color: #21A14E;
	}
	body.login form input[type="text"],
	body.login form input[type="password"] {
		background: rgba(245, 242, 236, 0.04);
		border: 1px solid rgba(245, 242, 236, 0.12);
		border-radius: 2px;
		color: #F5F2EC;
		font-family: 'Syne', sans-serif;
		font-size: 0.9rem;
		padding: 10px 14px;
	}
	body.login form input[type="text"]:focus,
	body.login form input[type="password"]:focus {
		border-color: #21A14E;
		box-shadow: 0 0 0 1px #21A14E;
		outline: none;
		background: rgba(33, 161, 78, 0.04);
	}
	body.login form .forgetmenot label {
		color: #8A8A8A;
		font-size: 0.75rem;
		text-transform: none;
		letter-spacing: 0;
	}
	body.login #wp-submit {
		background: #21A14E;
		border: none;
		border-radius: 2px;
		color: #0A0A0A;
		font-family: 'Syne', sans-serif;
		font-size: 0.72rem;
		font-weight: 700;
		letter-spacing: 2px;
		text-transform: uppercase;
		padding: 10px 24px;
		text-shadow: none;
		box-shadow: none;
		transition: all 0.2s;
	}
	body.login #wp-submit:hover {
		background: #17783A;
		color: #F5F2EC;
	}
	body.login #nav,
	body.login #backtoblog {
		text-align: center;
	}
	body.login #nav a,
	body.login #backtoblog a {
		color: #8A8A8A;
		font-size: 0.78rem;
		text-decoration: none;
		transition: color 0.2s;
	}
	body.login #nav a:hover,
	body.login #backtoblog a:hover {
		color: #21A14E;
	}
	body.login .message,
	body.login .success {
		border-left-color: #21A14E;
		background: rgba(33, 161, 78, 0.06);
		color: #F5F2EC;
	}
	body.login #login_error {
		border-left-color: #e74c3c;
		background: rgba(231, 76, 60, 0.06);
		color: #F5F2EC;
	}
	body.login .privacy-policy-page-link a {
		color: #8A8A8A;
	}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'ifende_login_styles' );

/**
 * Change the login logo URL to the site home.
 */
function ifende_login_logo_url() {
	return home_url( '/' );
}
add_filter( 'login_headerurl', 'ifende_login_logo_url' );

/**
 * Change the login logo title to site name.
 */
function ifende_login_logo_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'ifende_login_logo_title' );
