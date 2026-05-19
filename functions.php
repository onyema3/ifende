<?php
/**
 * Ifende Portfolio — functions.php
 *
 * Main theme bootstrap file. Loads modular includes.
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

// Theme constants.
define( 'IFENDE_VERSION', '1.1.0' );
define( 'IFENDE_URI', get_template_directory_uri() );
define( 'IFENDE_DIR', get_template_directory() );

/**
 * Helper: retrieve a theme_mod with the ifende_ prefix.
 *
 * @param string $key     Option key (without prefix).
 * @param string $default Default value.
 * @return string
 */
function ifende_opt( $key, $default = '' ) {
  return get_theme_mod( 'ifende_' . $key, $default );
}

// Load modular includes.
require_once IFENDE_DIR . '/inc/setup.php';
require_once IFENDE_DIR . '/inc/enqueue.php';
require_once IFENDE_DIR . '/inc/customizer.php';
require_once IFENDE_DIR . '/inc/ajax.php';
require_once IFENDE_DIR . '/inc/seo.php';
require_once IFENDE_DIR . '/inc/page-builders.php';
require_once IFENDE_DIR . '/inc/analytics.php';
require_once IFENDE_DIR . '/inc/gdpr.php';
require_once IFENDE_DIR . '/inc/images.php';
require_once IFENDE_DIR . '/inc/blog.php';
require_once IFENDE_DIR . '/inc/performance.php';
require_once IFENDE_DIR . '/inc/woocommerce.php';
require_once IFENDE_DIR . '/inc/patterns.php';
require_once IFENDE_DIR . '/inc/livechat.php';
require_once IFENDE_DIR . '/inc/cpt.php';
require_once IFENDE_DIR . '/inc/portfolio.php';
require_once IFENDE_DIR . '/inc/maintenance.php';
require_once IFENDE_DIR . '/inc/booking.php';
require_once IFENDE_DIR . '/inc/export-import.php';
require_once IFENDE_DIR . '/inc/exit-intent.php';
require_once IFENDE_DIR . '/inc/action-bar.php';
require_once IFENDE_DIR . '/inc/project-inquiry.php';
require_once IFENDE_DIR . '/inc/testimonial-request.php';
require_once IFENDE_DIR . '/inc/toc.php';
require_once IFENDE_DIR . '/inc/breadcrumbs.php';
require_once IFENDE_DIR . '/inc/related-projects.php';
require_once IFENDE_DIR . '/inc/author-box.php';
require_once IFENDE_DIR . '/inc/visitor-count.php';
require_once IFENDE_DIR . '/inc/debug-panel.php';
require_once IFENDE_DIR . '/inc/login-branding.php';
require_once IFENDE_DIR . '/inc/dashboard-widget.php';
require_once IFENDE_DIR . '/inc/legal-pages.php';
