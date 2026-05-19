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
 * Sets up theme defaults and registers support for various WordPress features.
 */
function ifende_setup() {
  // Let WordPress manage the document title.
  add_theme_support( 'title-tag' );

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

  // Register navigation menus.
  register_nav_menus( [
    'primary' => esc_html__( 'Primary Navigation', 'ifende' ),
  ] );

  // Register widget area.
  register_sidebar( [
    'name'          => esc_html__( 'Sidebar', 'ifende' ),
    'id'            => 'ifende-sidebar',
    'description'   => esc_html__( 'Add widgets here.', 'ifende' ),
    'before_widget' => '<section id="%1$s" class="widget %2$s">',
    'after_widget'  => '</section>',
    'before_title'  => '<h3 class="widget-title">',
    'after_title'   => '</h3>',
  ] );
}
add_action( 'after_setup_theme', 'ifende_setup' );

/**
 * Flush rewrite rules on theme switch.
 */
add_action( 'after_switch_theme', function() {
  flush_rewrite_rules();
} );
