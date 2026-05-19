<?php
/**
 * Custom Post Types — Services, Clients, Testimonials, FAQ.
 *
 * Replaces Customizer textarea fields with proper CPTs for unlimited entries,
 * drag-and-drop reordering, featured images, and a familiar WordPress editing UX.
 *
 * @package Ifende
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom post types.
 */
function ifende_register_cpts() {
	// --- SERVICES ---
	register_post_type( 'ifende_service', [
		'labels' => [
			'name'               => esc_html__( 'Services', 'ifende' ),
			'singular_name'      => esc_html__( 'Service', 'ifende' ),
			'add_new'            => esc_html__( 'Add Service', 'ifende' ),
			'add_new_item'       => esc_html__( 'Add New Service', 'ifende' ),
			'edit_item'          => esc_html__( 'Edit Service', 'ifende' ),
			'all_items'          => esc_html__( 'All Services', 'ifende' ),
			'search_items'       => esc_html__( 'Search Services', 'ifende' ),
			'not_found'          => esc_html__( 'No services found.', 'ifende' ),
			'not_found_in_trash' => esc_html__( 'No services found in Trash.', 'ifende' ),
		],
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-hammer',
		'menu_position'=> 25,
		'supports'     => [ 'title', 'editor', 'page-attributes' ],
		'hierarchical' => false,
		'has_archive'  => false,
		'rewrite'      => false,
	] );

	// --- CLIENTS ---
	register_post_type( 'ifende_client', [
		'labels' => [
			'name'               => esc_html__( 'Clients', 'ifende' ),
			'singular_name'      => esc_html__( 'Client', 'ifende' ),
			'add_new'            => esc_html__( 'Add Client', 'ifende' ),
			'add_new_item'       => esc_html__( 'Add New Client', 'ifende' ),
			'edit_item'          => esc_html__( 'Edit Client', 'ifende' ),
			'all_items'          => esc_html__( 'All Clients', 'ifende' ),
			'search_items'       => esc_html__( 'Search Clients', 'ifende' ),
			'not_found'          => esc_html__( 'No clients found.', 'ifende' ),
			'not_found_in_trash' => esc_html__( 'No clients found in Trash.', 'ifende' ),
		],
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-groups',
		'menu_position'=> 26,
		'supports'     => [ 'title', 'thumbnail', 'page-attributes' ],
		'hierarchical' => false,
		'has_archive'  => false,
		'rewrite'      => false,
	] );

	// --- TESTIMONIALS ---
	register_post_type( 'ifende_testimonial', [
		'labels' => [
			'name'               => esc_html__( 'Testimonials', 'ifende' ),
			'singular_name'      => esc_html__( 'Testimonial', 'ifende' ),
			'add_new'            => esc_html__( 'Add Testimonial', 'ifende' ),
			'add_new_item'       => esc_html__( 'Add New Testimonial', 'ifende' ),
			'edit_item'          => esc_html__( 'Edit Testimonial', 'ifende' ),
			'all_items'          => esc_html__( 'All Testimonials', 'ifende' ),
			'search_items'       => esc_html__( 'Search Testimonials', 'ifende' ),
			'not_found'          => esc_html__( 'No testimonials found.', 'ifende' ),
			'not_found_in_trash' => esc_html__( 'No testimonials found in Trash.', 'ifende' ),
		],
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-format-quote',
		'menu_position'=> 27,
		'supports'     => [ 'title', 'editor', 'page-attributes' ],
		'hierarchical' => false,
		'has_archive'  => false,
		'rewrite'      => false,
	] );

	// --- FAQ ---
	register_post_type( 'ifende_faq', [
		'labels' => [
			'name'               => esc_html__( 'FAQs', 'ifende' ),
			'singular_name'      => esc_html__( 'FAQ', 'ifende' ),
			'add_new'            => esc_html__( 'Add FAQ', 'ifende' ),
			'add_new_item'       => esc_html__( 'Add New FAQ', 'ifende' ),
			'edit_item'          => esc_html__( 'Edit FAQ', 'ifende' ),
			'all_items'          => esc_html__( 'All FAQs', 'ifende' ),
			'search_items'       => esc_html__( 'Search FAQs', 'ifende' ),
			'not_found'          => esc_html__( 'No FAQs found.', 'ifende' ),
			'not_found_in_trash' => esc_html__( 'No FAQs found in Trash.', 'ifende' ),
		],
		'public'       => false,
		'show_ui'      => true,
		'show_in_menu' => true,
		'menu_icon'    => 'dashicons-editor-help',
		'menu_position'=> 28,
		'supports'     => [ 'title', 'editor', 'page-attributes' ],
		'hierarchical' => false,
		'has_archive'  => false,
		'rewrite'      => false,
	] );
}
add_action( 'init', 'ifende_register_cpts' );

/**
 * Register meta boxes for CPT custom fields.
 */
function ifende_cpt_meta_boxes() {
	// Service: Icon field.
	add_meta_box( 'ifende_service_meta', esc_html__( 'Service Details', 'ifende' ), 'ifende_service_meta_cb', 'ifende_service', 'side' );

	// Client: URL and Icon fields.
	add_meta_box( 'ifende_client_meta', esc_html__( 'Client Details', 'ifende' ), 'ifende_client_meta_cb', 'ifende_client', 'normal' );

	// Testimonial: Role field.
	add_meta_box( 'ifende_testimonial_meta', esc_html__( 'Testimonial Details', 'ifende' ), 'ifende_testimonial_meta_cb', 'ifende_testimonial', 'normal' );
}
add_action( 'add_meta_boxes', 'ifende_cpt_meta_boxes' );

/**
 * Service meta box callback — Icon emoji field.
 */
function ifende_service_meta_cb( $post ) {
	wp_nonce_field( 'ifende_service_meta', 'ifende_service_nonce' );
	$icon = get_post_meta( $post->ID, '_ifende_service_icon', true );
	?>
	<p>
		<label for="ifende_service_icon"><strong><?php esc_html_e( 'Icon (emoji or text)', 'ifende' ); ?></strong></label><br>
		<input type="text" id="ifende_service_icon" name="ifende_service_icon" value="<?php echo esc_attr( $icon ); ?>" style="width:100%;font-size:1.5em;" placeholder="🌐">
		<br><small><?php esc_html_e( 'Use an emoji or short text as the service icon.', 'ifende' ); ?></small>
	</p>
	<?php
}

/**
 * Client meta box callback — URL and Icon fields.
 */
function ifende_client_meta_cb( $post ) {
	wp_nonce_field( 'ifende_client_meta', 'ifende_client_nonce' );
	$url  = get_post_meta( $post->ID, '_ifende_client_url', true );
	$icon = get_post_meta( $post->ID, '_ifende_client_icon', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="ifende_client_url"><?php esc_html_e( 'Website URL', 'ifende' ); ?></label></th>
			<td><input type="url" id="ifende_client_url" name="ifende_client_url" value="<?php echo esc_url( $url ); ?>" class="regular-text" placeholder="https://example.com"></td>
		</tr>
		<tr>
			<th><label for="ifende_client_icon"><?php esc_html_e( 'Icon (emoji)', 'ifende' ); ?></label></th>
			<td><input type="text" id="ifende_client_icon" name="ifende_client_icon" value="<?php echo esc_attr( $icon ); ?>" style="font-size:1.5em;width:80px;" placeholder="🔷"></td>
		</tr>
	</table>
	<?php
}

/**
 * Testimonial meta box callback — Role/position field.
 */
function ifende_testimonial_meta_cb( $post ) {
	wp_nonce_field( 'ifende_testimonial_meta', 'ifende_testimonial_nonce' );
	$role = get_post_meta( $post->ID, '_ifende_testimonial_role', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="ifende_testimonial_role"><?php esc_html_e( 'Role / Company', 'ifende' ); ?></label></th>
			<td><input type="text" id="ifende_testimonial_role" name="ifende_testimonial_role" value="<?php echo esc_attr( $role ); ?>" class="regular-text" placeholder="CEO, Company Name"></td>
		</tr>
	</table>
	<p class="description"><?php esc_html_e( 'The post title = person\'s name. The post content (editor above) = their quote.', 'ifende' ); ?></p>
	<?php
}

/**
 * Save meta box data for all CPTs.
 */
function ifende_save_cpt_meta( $post_id ) {
	// Service icon.
	if ( isset( $_POST['ifende_service_nonce'] ) && wp_verify_nonce( $_POST['ifende_service_nonce'], 'ifende_service_meta' ) ) {
		if ( isset( $_POST['ifende_service_icon'] ) ) {
			update_post_meta( $post_id, '_ifende_service_icon', sanitize_text_field( $_POST['ifende_service_icon'] ) );
		}
	}

	// Client URL and icon.
	if ( isset( $_POST['ifende_client_nonce'] ) && wp_verify_nonce( $_POST['ifende_client_nonce'], 'ifende_client_meta' ) ) {
		if ( isset( $_POST['ifende_client_url'] ) ) {
			update_post_meta( $post_id, '_ifende_client_url', esc_url_raw( $_POST['ifende_client_url'] ) );
		}
		if ( isset( $_POST['ifende_client_icon'] ) ) {
			update_post_meta( $post_id, '_ifende_client_icon', sanitize_text_field( $_POST['ifende_client_icon'] ) );
		}
	}

	// Testimonial role.
	if ( isset( $_POST['ifende_testimonial_nonce'] ) && wp_verify_nonce( $_POST['ifende_testimonial_nonce'], 'ifende_testimonial_meta' ) ) {
		if ( isset( $_POST['ifende_testimonial_role'] ) ) {
			update_post_meta( $post_id, '_ifende_testimonial_role', sanitize_text_field( $_POST['ifende_testimonial_role'] ) );
		}
	}
}
add_action( 'save_post', 'ifende_save_cpt_meta' );

/**
 * Add custom columns to the admin list tables.
 */
function ifende_service_columns( $columns ) {
	$new = [];
	foreach ( $columns as $key => $val ) {
		$new[ $key ] = $val;
		if ( 'title' === $key ) {
			$new['service_icon'] = esc_html__( 'Icon', 'ifende' );
		}
	}
	$new['menu_order'] = esc_html__( 'Order', 'ifende' );
	return $new;
}
add_filter( 'manage_ifende_service_posts_columns', 'ifende_service_columns' );

function ifende_service_column_content( $column, $post_id ) {
	if ( 'service_icon' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_ifende_service_icon', true ) ?: '—' );
	}
	if ( 'menu_order' === $column ) {
		echo esc_html( get_post_field( 'menu_order', $post_id ) );
	}
}
add_action( 'manage_ifende_service_posts_custom_column', 'ifende_service_column_content', 10, 2 );

function ifende_client_columns( $columns ) {
	$new = [];
	foreach ( $columns as $key => $val ) {
		$new[ $key ] = $val;
		if ( 'title' === $key ) {
			$new['client_icon'] = esc_html__( 'Icon', 'ifende' );
			$new['client_url']  = esc_html__( 'URL', 'ifende' );
		}
	}
	$new['menu_order'] = esc_html__( 'Order', 'ifende' );
	return $new;
}
add_filter( 'manage_ifende_client_posts_columns', 'ifende_client_columns' );

function ifende_client_column_content( $column, $post_id ) {
	if ( 'client_icon' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_ifende_client_icon', true ) ?: '—' );
	}
	if ( 'client_url' === $column ) {
		$url = get_post_meta( $post_id, '_ifende_client_url', true );
		echo $url ? '<a href="' . esc_url( $url ) . '" target="_blank">' . esc_html( wp_parse_url( $url, PHP_URL_HOST ) ) . '</a>' : '—';
	}
	if ( 'menu_order' === $column ) {
		echo esc_html( get_post_field( 'menu_order', $post_id ) );
	}
}
add_action( 'manage_ifende_client_posts_custom_column', 'ifende_client_column_content', 10, 2 );

function ifende_testimonial_columns( $columns ) {
	$new = [];
	foreach ( $columns as $key => $val ) {
		$new[ $key ] = $val;
		if ( 'title' === $key ) {
			$new['testimonial_role'] = esc_html__( 'Role', 'ifende' );
		}
	}
	$new['menu_order'] = esc_html__( 'Order', 'ifende' );
	return $new;
}
add_filter( 'manage_ifende_testimonial_posts_columns', 'ifende_testimonial_columns' );

function ifende_testimonial_column_content( $column, $post_id ) {
	if ( 'testimonial_role' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_ifende_testimonial_role', true ) ?: '—' );
	}
	if ( 'menu_order' === $column ) {
		echo esc_html( get_post_field( 'menu_order', $post_id ) );
	}
}
add_action( 'manage_ifende_testimonial_posts_custom_column', 'ifende_testimonial_column_content', 10, 2 );

/**
 * Make the Order column sortable.
 */
function ifende_sortable_order_column( $columns ) {
	$columns['menu_order'] = 'menu_order';
	return $columns;
}
add_filter( 'manage_edit-ifende_service_sortable_columns', 'ifende_sortable_order_column' );
add_filter( 'manage_edit-ifende_client_sortable_columns', 'ifende_sortable_order_column' );
add_filter( 'manage_edit-ifende_testimonial_sortable_columns', 'ifende_sortable_order_column' );
add_filter( 'manage_edit-ifende_faq_sortable_columns', 'ifende_sortable_order_column' );

/**
 * Helper: Check if any CPT posts exist for a given type.
 * Used by templates to fall back to Customizer data if CPTs are empty.
 *
 * @param string $post_type The post type slug.
 * @return bool
 */
function ifende_has_cpt_entries( $post_type ) {
	$count = wp_count_posts( $post_type );
	return ( isset( $count->publish ) && $count->publish > 0 );
}
