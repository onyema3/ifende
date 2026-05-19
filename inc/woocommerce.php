<?php
/**
 * WooCommerce Compatibility — Theme support, hooks, and template overrides.
 *
 * @package Ifende
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Declare WooCommerce support and configure theme options.
 */
function ifende_woocommerce_setup() {
	add_theme_support( 'woocommerce', [
		'thumbnail_image_width' => 400,
		'single_image_width'    => 600,
		'product_grid'          => [
			'default_rows'    => 3,
			'min_rows'        => 1,
			'max_rows'        => 10,
			'default_columns' => 3,
			'min_columns'     => 1,
			'max_columns'     => 4,
		],
	] );

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
}
add_action( 'after_setup_theme', 'ifende_woocommerce_setup' );

/**
 * Enqueue WooCommerce-specific styles only when WooCommerce is active.
 */
function ifende_woocommerce_styles() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	wp_enqueue_style(
		'ifende-woocommerce',
		IFENDE_URI . '/assets/css/woocommerce.css',
		[ 'ifende-main' ],
		IFENDE_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'ifende_woocommerce_styles', 25 );

/**
 * Wrap WooCommerce content in theme markup.
 */
function ifende_woocommerce_wrapper_before() {
	echo '<main id="main-content" class="if-section" style="padding-top:140px;"><div class="woocommerce-wrap">';
}

function ifende_woocommerce_wrapper_after() {
	echo '</div></main>';
}

// Only apply hooks when WooCommerce is active.
if ( class_exists( 'WooCommerce' ) ) {
	remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
	remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );
	add_action( 'woocommerce_before_main_content', 'ifende_woocommerce_wrapper_before' );
	add_action( 'woocommerce_after_main_content', 'ifende_woocommerce_wrapper_after' );

	// Remove default WooCommerce sidebar.
	remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );
}

/**
 * Adjust WooCommerce products per page via Customizer.
 *
 * @param int $cols Number of columns.
 * @return int
 */
function ifende_woocommerce_products_per_row( $cols ) {
	return 3;
}
add_filter( 'loop_shop_columns', 'ifende_woocommerce_products_per_row' );

/**
 * Products per page.
 *
 * @return int
 */
function ifende_woocommerce_products_per_page() {
	return 12;
}
add_filter( 'loop_shop_per_page', 'ifende_woocommerce_products_per_page' );

/**
 * Customize the "Add to Cart" button text for simple products.
 *
 * @param string      $text    Button text.
 * @param \WC_Product $product Product object.
 * @return string
 */
function ifende_woocommerce_add_to_cart_text( $text, $product ) {
	if ( $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		return __( 'Add to Cart', 'ifende' );
	}
	return $text;
}
add_filter( 'woocommerce_product_add_to_cart_text', 'ifende_woocommerce_add_to_cart_text', 10, 2 );

/**
 * Add theme body class for WooCommerce pages.
 *
 * @param array $classes Body classes.
 * @return array
 */
function ifende_woocommerce_body_class( $classes ) {
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() || is_product_category() || is_product_tag() || is_product() ) {
			$classes[] = 'ifende-woocommerce-page';
		}
	}
	return $classes;
}
add_filter( 'body_class', 'ifende_woocommerce_body_class' );

/**
 * Customize WooCommerce breadcrumb.
 *
 * @param array $args Breadcrumb args.
 * @return array
 */
function ifende_woocommerce_breadcrumb_args( $args ) {
	$args['delimiter']   = ' <span class="title-sep">/</span> ';
	$args['wrap_before'] = '<nav class="woocommerce-breadcrumb" style="font-family:\'DM Mono\',monospace;font-size:0.68rem;letter-spacing:1.5px;text-transform:uppercase;color:var(--grey);margin-bottom:24px;">';
	$args['wrap_after']  = '</nav>';
	return $args;
}
add_filter( 'woocommerce_breadcrumb_defaults', 'ifende_woocommerce_breadcrumb_args' );

/**
 * Register WooCommerce widget area.
 */
function ifende_woocommerce_widgets() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	register_sidebar( [
		'name'          => esc_html__( 'Shop Sidebar', 'ifende' ),
		'id'            => 'ifende-shop-sidebar',
		'description'   => esc_html__( 'Widgets displayed on WooCommerce shop pages.', 'ifende' ),
		'before_widget' => '<section id="%1$s" class="widget woo-widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	] );
}
add_action( 'widgets_init', 'ifende_woocommerce_widgets' );
