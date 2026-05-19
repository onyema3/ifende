<?php
/**
 * Table of Contents — Auto-generated from H2/H3 headings in post content.
 *
 * Adds a TOC block before the content on single posts and project pages.
 * Uses PHP DOMDocument to parse headings and inject IDs.
 *
 * @package Ifende
 * @since   1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Auto-generate Table of Contents for long-form content.
 *
 * @param string $content The post content.
 * @return string Modified content with TOC prepended and heading IDs.
 */
function ifende_auto_toc( $content ) {
	// Only on single posts and projects.
	if ( ! is_singular( [ 'post', 'ifende_project' ] ) ) {
		return $content;
	}

	// Only if content has at least 2 headings.
	preg_match_all( '/<h([23])[^>]*>(.*?)<\/h[23]>/i', $content, $matches, PREG_SET_ORDER );

	if ( count( $matches ) < 3 ) {
		return $content;
	}

	$toc_items = [];
	$used_ids  = [];

	foreach ( $matches as $match ) {
		$level = $match[1];
		$text  = wp_strip_all_tags( $match[2] );
		$id    = sanitize_title( $text );

		// Ensure unique IDs.
		$original_id = $id;
		$counter     = 1;
		while ( in_array( $id, $used_ids, true ) ) {
			$id = $original_id . '-' . $counter;
			$counter++;
		}
		$used_ids[] = $id;

		// Replace the heading in content with an ID-tagged version.
		$old_heading = $match[0];
		$new_heading = preg_replace( '/<h([23])/', '<h$1 id="' . esc_attr( $id ) . '"', $old_heading, 1 );
		$content     = str_replace( $old_heading, $new_heading, $content );

		$toc_items[] = [
			'level' => (int) $level,
			'text'  => $text,
			'id'    => $id,
		];
	}

	// Build TOC HTML.
	$toc  = '<nav class="toc" aria-label="' . esc_attr__( 'Table of Contents', 'ifende' ) . '">' . "\n";
	$toc .= '<div class="toc-title">' . esc_html__( 'Contents', 'ifende' ) . '</div>' . "\n";
	$toc .= '<ol class="toc-list">' . "\n";

	foreach ( $toc_items as $item ) {
		$indent = $item['level'] === 3 ? ' class="toc-sub"' : '';
		$toc .= sprintf(
			'<li%s><a href="#%s">%s</a></li>' . "\n",
			$indent,
			esc_attr( $item['id'] ),
			esc_html( $item['text'] )
		);
	}

	$toc .= '</ol>' . "\n";
	$toc .= '</nav>' . "\n";

	return $toc . $content;
}
add_filter( 'the_content', 'ifende_auto_toc', 5 );
