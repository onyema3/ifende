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
define( 'IFENDE_VERSION', '1.0.0' );
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
