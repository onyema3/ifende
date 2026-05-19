<?php
/**
 * Page Builder Compatibility (Elementor, Gutenberg, etc.)
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Check if the current post/page was built with Elementor.
 *
 * @param int|null $post_id Post ID (defaults to current post).
 * @return bool
 */
function ifende_is_built_with_elementor( $post_id = null ) {
  if ( ! $post_id ) {
    $post_id = get_the_ID();
  }

  // Check if Elementor plugin is active.
  if ( ! did_action( 'elementor/loaded' ) ) {
    return false;
  }

  // Check if this specific page was built with Elementor.
  return \Elementor\Plugin::$instance->documents->get( $post_id )->is_built_with_elementor();
}

/**
 * Register Elementor locations for theme support.
 * This allows Elementor Pro Theme Builder to take over header/footer/etc.
 *
 * @param \ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager $elementor_theme_manager
 */
function ifende_elementor_locations( $elementor_theme_manager ) {
  $elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'ifende_elementor_locations' );

/**
 * Remove default page padding/max-width when Elementor is active on a page.
 */
function ifende_elementor_body_class( $classes ) {
  if ( is_singular() && ifende_is_built_with_elementor() ) {
    $classes[] = 'elementor-active-page';
  }
  return $classes;
}
add_filter( 'body_class', 'ifende_elementor_body_class' );

/**
 * Add Gutenberg editor styles to match front-end appearance.
 */
function ifende_block_editor_assets() {
  wp_enqueue_style(
    'ifende-editor-styles',
    IFENDE_URI . '/assets/css/main.css',
    [],
    IFENDE_VERSION
  );
}
add_action( 'enqueue_block_editor_assets', 'ifende_block_editor_assets' );

/**
 * Register Elementor widget areas if Elementor is active.
 */
function ifende_elementor_widgets_registered() {
  // Placeholder for custom Elementor widgets in the future.
}
add_action( 'elementor/widgets/register', 'ifende_elementor_widgets_registered' );
