<?php
/**
 * Portfolio/Projects CPT — Custom Post Type with filterable grid.
 *
 * @package Ifende
 * @since   1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Portfolio CPT and Project Category taxonomy.
 */
function ifende_register_portfolio() {
	// Custom Post Type.
	register_post_type( 'ifende_project', [
		'labels' => [
			'name'               => _x( 'Projects', 'post type general name', 'ifende' ),
			'singular_name'      => _x( 'Project', 'post type singular name', 'ifende' ),
			'add_new'            => _x( 'Add Project', 'project', 'ifende' ),
			'add_new_item'       => esc_html__( 'Add New Project', 'ifende' ),
			'edit_item'          => esc_html__( 'Edit Project', 'ifende' ),
			'all_items'          => esc_html__( 'All Projects', 'ifende' ),
			'search_items'       => esc_html__( 'Search Projects', 'ifende' ),
			'not_found'          => esc_html__( 'No projects found.', 'ifende' ),
			'not_found_in_trash' => esc_html__( 'No projects found in Trash.', 'ifende' ),
		],
		'public'       => true,
		'show_ui'      => true,
		'show_in_menu' => true,
		'show_in_rest' => true,
		'menu_icon'    => 'dashicons-portfolio',
		'menu_position'=> 5,
		'supports'     => [ 'title', 'editor', 'thumbnail', 'excerpt', 'page-attributes' ],
		'has_archive'  => true,
		'rewrite'      => [ 'slug' => 'projects', 'with_front' => false ],
	] );

	// Project Category taxonomy.
	register_taxonomy( 'project_category', 'ifende_project', [
		'labels' => [
			'name'          => esc_html__( 'Project Categories', 'ifende' ),
			'singular_name' => esc_html__( 'Category', 'ifende' ),
			'add_new_item'  => esc_html__( 'Add New Category', 'ifende' ),
			'edit_item'     => esc_html__( 'Edit Category', 'ifende' ),
			'all_items'     => esc_html__( 'All Categories', 'ifende' ),
			'search_items'  => esc_html__( 'Search Categories', 'ifende' ),
		],
		'public'       => true,
		'show_in_rest' => true,
		'hierarchical' => true,
		'rewrite'      => [ 'slug' => 'project-category' ],
	] );
}
add_action( 'init', 'ifende_register_portfolio' );

/**
 * Flush rewrite rules when theme is activated so /projects/ permalink works immediately.
 */
add_action( 'after_switch_theme', function() {
	ifende_register_portfolio();
	flush_rewrite_rules();
} );

/**
 * Register meta box for project details.
 */
function ifende_project_meta_box() {
	add_meta_box( 'ifende_project_details', esc_html__( 'Project Details', 'ifende' ), 'ifende_project_meta_cb', 'ifende_project', 'normal', 'high' );
}
add_action( 'add_meta_boxes', 'ifende_project_meta_box' );

/**
 * Project meta box callback.
 */
function ifende_project_meta_cb( $post ) {
	wp_nonce_field( 'ifende_project_meta', 'ifende_project_nonce' );
	$client   = get_post_meta( $post->ID, '_ifende_project_client', true );
	$url      = get_post_meta( $post->ID, '_ifende_project_url', true );
	$year     = get_post_meta( $post->ID, '_ifende_project_year', true );
	$tech     = get_post_meta( $post->ID, '_ifende_project_tech', true );
	?>
	<table class="form-table">
		<tr>
			<th><label for="ifende_project_client"><?php esc_html_e( 'Client Name', 'ifende' ); ?></label></th>
			<td><input type="text" id="ifende_project_client" name="ifende_project_client" value="<?php echo esc_attr( $client ); ?>" class="regular-text" placeholder="Company Name"></td>
		</tr>
		<tr>
			<th><label for="ifende_project_url"><?php esc_html_e( 'Live URL', 'ifende' ); ?></label></th>
			<td><input type="url" id="ifende_project_url" name="ifende_project_url" value="<?php echo esc_url( $url ); ?>" class="regular-text" placeholder="https://example.com"></td>
		</tr>
		<tr>
			<th><label for="ifende_project_year"><?php esc_html_e( 'Year', 'ifende' ); ?></label></th>
			<td><input type="text" id="ifende_project_year" name="ifende_project_year" value="<?php echo esc_attr( $year ); ?>" style="width:80px;" placeholder="2024"></td>
		</tr>
		<tr>
			<th><label for="ifende_project_tech"><?php esc_html_e( 'Tech Stack (comma-separated)', 'ifende' ); ?></label></th>
			<td><input type="text" id="ifende_project_tech" name="ifende_project_tech" value="<?php echo esc_attr( $tech ); ?>" class="regular-text" placeholder="WordPress, Elementor, PHP"></td>
		</tr>
	</table>
	<?php
}

/**
 * Save project meta.
 */
function ifende_save_project_meta( $post_id ) {
	if ( ! isset( $_POST['ifende_project_nonce'] ) || ! wp_verify_nonce( $_POST['ifende_project_nonce'], 'ifende_project_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( isset( $_POST['ifende_project_client'] ) ) {
		update_post_meta( $post_id, '_ifende_project_client', sanitize_text_field( $_POST['ifende_project_client'] ) );
	}
	if ( isset( $_POST['ifende_project_url'] ) ) {
		update_post_meta( $post_id, '_ifende_project_url', esc_url_raw( $_POST['ifende_project_url'] ) );
	}
	if ( isset( $_POST['ifende_project_year'] ) ) {
		update_post_meta( $post_id, '_ifende_project_year', sanitize_text_field( $_POST['ifende_project_year'] ) );
	}
	if ( isset( $_POST['ifende_project_tech'] ) ) {
		update_post_meta( $post_id, '_ifende_project_tech', sanitize_text_field( $_POST['ifende_project_tech'] ) );
	}
}
add_action( 'save_post_ifende_project', 'ifende_save_project_meta' );

/**
 * Add admin columns for Projects.
 */
function ifende_project_columns( $columns ) {
	$new = [];
	foreach ( $columns as $key => $val ) {
		$new[ $key ] = $val;
		if ( 'title' === $key ) {
			$new['project_client'] = esc_html__( 'Client', 'ifende' );
			$new['project_year']   = esc_html__( 'Year', 'ifende' );
		}
	}
	return $new;
}
add_filter( 'manage_ifende_project_posts_columns', 'ifende_project_columns' );

function ifende_project_column_content( $column, $post_id ) {
	if ( 'project_client' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_ifende_project_client', true ) ?: '—' );
	}
	if ( 'project_year' === $column ) {
		echo esc_html( get_post_meta( $post_id, '_ifende_project_year', true ) ?: '—' );
	}
}
add_action( 'manage_ifende_project_posts_custom_column', 'ifende_project_column_content', 10, 2 );
