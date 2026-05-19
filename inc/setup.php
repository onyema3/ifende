<?php
/**
 * Theme Setup
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function ifende_content_width() {
  $GLOBALS['content_width'] = apply_filters( 'ifende_content_width', 1200 );
}
add_action( 'after_setup_theme', 'ifende_content_width', 0 );

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function ifende_setup() {
  // Let WordPress manage the document title.
  add_theme_support( 'title-tag' );

  // Load theme text domain for translations.
  load_theme_textdomain( 'ifende', get_template_directory() . '/languages' );

  // Enable support for Post Thumbnails.
  add_theme_support( 'post-thumbnails' );

  // Custom logo support.
  add_theme_support( 'custom-logo', [
    'height'      => 60,
    'width'       => 200,
    'flex-height' => true,
    'flex-width'  => true,
  ] );

  // Switch default core markup to output valid HTML5.
  add_theme_support( 'html5', [
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption',
    'style',
    'script',
  ] );

  // Add support for automatic feed links.
  add_theme_support( 'automatic-feed-links' );

  // Block editor (Gutenberg) support.
  add_theme_support( 'align-wide' );
  add_theme_support( 'responsive-embeds' );
  add_theme_support( 'wp-block-styles' );
  add_theme_support( 'editor-styles' );
  add_editor_style( 'assets/css/editor-style.css' );

  // Elementor / page builder support — allow full-width layouts.
  add_theme_support( 'elementor', [
    'default_generic_fonts' => 'Syne, sans-serif',
  ] );

  // Register navigation menus.
  register_nav_menus( [
    'primary' => esc_html__( 'Primary Navigation', 'ifende' ),
    'footer'  => esc_html__( 'Footer Navigation', 'ifende' ),
    'social'  => esc_html__( 'Social Links', 'ifende' ),
  ] );
}
add_action( 'after_setup_theme', 'ifende_setup' );

/**
 * Register widget areas.
 */
function ifende_widgets_init() {
  register_sidebar( [
    'name'          => esc_html__( 'Sidebar', 'ifende' ),
    'id'            => 'ifende-sidebar',
    'description'   => esc_html__( 'Add widgets here.', 'ifende' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ] );

  // Footer widget columns. Up to 4; only ones with widgets render.
  for ( $i = 1; $i <= 4; $i++ ) {
    register_sidebar( [
      'name'          => sprintf( esc_html__( 'Footer Column %d', 'ifende' ), $i ),
      'id'            => 'ifende-footer-' . $i,
      /* translators: %d: footer column number */
      'description'   => sprintf( esc_html__( 'Widgets added here appear in column %d of the footer. Empty columns are skipped.', 'ifende' ), $i ),
      'before_widget' => '<section id="%1$s" class="widget footer-widget %2$s">',
      'after_widget'  => '</section>',
      'before_title'  => '<h4 class="footer-widget-title">',
      'after_title'   => '</h4>',
    ] );
  }
}
add_action( 'widgets_init', 'ifende_widgets_init' );

/**
 * Flush rewrite rules on theme switch.
 */
add_action( 'after_switch_theme', function() {
  flush_rewrite_rules();
} );
