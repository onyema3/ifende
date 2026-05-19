<?php
/**
 * Author Box — Display on blog posts with avatar, bio, and social links.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Output the author box after single post content.
 *
 * @param string $content The post content.
 * @return string Content with author box appended.
 */
function ifende_author_box( $content ) {
	if ( ! is_singular( 'post' ) || ! is_main_query() ) {
		return $content;
	}

	$author_id   = get_the_author_meta( 'ID' );
	$name        = get_the_author();
	$bio         = get_the_author_meta( 'description' );
	$avatar      = get_avatar( $author_id, 80, '', $name, [ 'class' => 'author-box-avatar' ] );
	$posts_url   = get_author_posts_url( $author_id );
	$website     = get_the_author_meta( 'user_url' );
	$post_count  = count_user_posts( $author_id, 'post', true );

	// Don't show if no bio.
	if ( empty( $bio ) ) {
		return $content;
	}

	$html  = '<div class="author-box">';
	$html .= '<div class="author-box-header">';
	$html .= $avatar;
	$html .= '<div class="author-box-info">';
	$html .= '<span class="author-box-label">' . esc_html__( 'Written by', 'ifende' ) . '</span>';
	$html .= '<a href="' . esc_url( $posts_url ) . '" class="author-box-name">' . esc_html( $name ) . '</a>';
	$html .= '</div>';
	$html .= '</div>';
	$html .= '<p class="author-box-bio">' . esc_html( $bio ) . '</p>';
	$html .= '<div class="author-box-footer">';
	$html .= '<a href="' . esc_url( $posts_url ) . '" class="author-box-link">';
	$html .= sprintf( esc_html__( 'View all %d posts', 'ifende' ), $post_count );
	$html .= '</a>';
	if ( $website ) {
		$html .= '<a href="' . esc_url( $website ) . '" target="_blank" rel="noopener" class="author-box-link">';
		$html .= esc_html__( 'Website', 'ifende' ) . ' ↗';
		$html .= '</a>';
	}
	$html .= '</div>';
	$html .= '</div>';

	return $content . $html;
}
add_filter( 'the_content', 'ifende_author_box', 20 );
