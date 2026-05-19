<?php
/**
 * SEO Enhancements — Structured Data (JSON-LD)
 *
 * @package Ifende
 */

if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Output structured data (JSON-LD) in the <head> for the homepage.
 * Outputs Person schema based on Customizer settings.
 */
function ifende_structured_data() {
  if ( ! is_front_page() ) {
    return;
  }

  $name      = get_theme_mod( 'ifende_hero_name', 'Onyemechi Ifende' );
  $bio       = get_theme_mod( 'ifende_hero_bio', 'A multi-disciplinary professional with rich experience in project management, web development, consulting, and branding.' );
  $email     = get_theme_mod( 'ifende_email', 'hello@ifende.com' );
  $twitter   = get_theme_mod( 'ifende_twitter_url', 'https://twitter.com/ifende' );
  $instagram = get_theme_mod( 'ifende_instagram_url', 'https://instagram.com/onyema.ifende' );
  $location  = get_theme_mod( 'ifende_about_location', 'Global — Based in Nigeria' );
  $roles_raw = get_theme_mod( 'ifende_hero_roles', 'Project Manager|Web Developer|Consultant' );
  $roles     = array_map( 'trim', explode( '|', $roles_raw ) );
  $photo     = get_theme_mod( 'ifende_hero_photo_url', '' );

  $schema = [
    '@context'    => 'https://schema.org',
    '@type'       => 'Person',
    'name'        => $name,
    'description' => $bio,
    'url'         => home_url( '/' ),
    'email'       => $email,
    'jobTitle'    => $roles[0] ?? 'Professional',
    'knowsAbout'  => $roles,
    'address'     => [
      '@type'           => 'PostalAddress',
      'addressLocality' => $location,
    ],
    'sameAs' => array_filter( [ $twitter, $instagram ] ),
  ];

  if ( $photo ) {
    $schema['image'] = $photo;
  }

  // Also add WebSite schema.
  $website_schema = [
    '@context' => 'https://schema.org',
    '@type'    => 'WebSite',
    'name'     => get_bloginfo( 'name' ),
    'url'      => home_url( '/' ),
  ];

  echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
  echo '<script type="application/ld+json">' . wp_json_encode( $website_schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'ifende_structured_data', 5 );

/**
 * Output Open Graph and Twitter Card meta tags.
 */
function ifende_og_meta() {
  $name  = get_theme_mod( 'ifende_hero_name', 'Onyemechi Ifende' );
  $bio   = get_theme_mod( 'ifende_hero_bio', 'A multi-disciplinary professional with rich experience in project management, web development, consulting, and branding.' );
  $photo = get_theme_mod( 'ifende_hero_photo_url', '' );

  echo '<meta property="og:type" content="website">' . "\n";
  echo '<meta property="og:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
  echo '<meta property="og:description" content="' . esc_attr( $bio ) . '">' . "\n";
  echo '<meta property="og:url" content="' . esc_url( home_url( '/' ) ) . '">' . "\n";
  if ( $photo ) {
    echo '<meta property="og:image" content="' . esc_url( $photo ) . '">' . "\n";
  }
  echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
  echo '<meta name="twitter:title" content="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . "\n";
  echo '<meta name="twitter:description" content="' . esc_attr( $bio ) . '">' . "\n";
  if ( $photo ) {
    echo '<meta name="twitter:image" content="' . esc_url( $photo ) . '">' . "\n";
  }
}
add_action( 'wp_head', 'ifende_og_meta', 2 );


/**
 * Output Schema.org CreativeWork structured data for single projects.
 */
function ifende_project_schema() {
	if ( ! is_singular( 'ifende_project' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$client  = get_post_meta( $post_id, '_ifende_project_client', true );
	$url     = get_post_meta( $post_id, '_ifende_project_url', true );
	$year    = get_post_meta( $post_id, '_ifende_project_year', true );
	$tech    = get_post_meta( $post_id, '_ifende_project_tech', true );
	$image   = get_the_post_thumbnail_url( $post_id, 'large' );

	$schema = [
		'@context'    => 'https://schema.org',
		'@type'       => 'CreativeWork',
		'name'        => get_the_title(),
		'description' => get_the_excerpt() ?: wp_trim_words( get_the_content(), 30 ),
		'url'         => get_permalink(),
		'author'      => [
			'@type' => 'Person',
			'name'  => get_theme_mod( 'ifende_hero_name', get_bloginfo( 'name' ) ),
		],
	];

	if ( $image ) {
		$schema['image'] = $image;
	}
	if ( $url ) {
		$schema['mainEntityOfPage'] = $url;
	}
	if ( $year ) {
		$schema['dateCreated'] = $year;
	}
	if ( $client ) {
		$schema['provider'] = [
			'@type' => 'Organization',
			'name'  => $client,
		];
	}
	if ( $tech ) {
		$schema['keywords'] = $tech;
	}

	echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT ) . '</script>' . "\n";
}
add_action( 'wp_head', 'ifende_project_schema', 6 );

/**
 * Output Open Graph meta for single projects (auto-generates social share previews).
 */
function ifende_project_og_meta() {
	if ( ! is_singular( 'ifende_project' ) ) {
		return;
	}

	$post_id = get_the_ID();
	$title   = get_the_title();
	$excerpt = get_the_excerpt() ?: wp_trim_words( get_the_content(), 25 );
	$image   = get_the_post_thumbnail_url( $post_id, 'large' );
	$url     = get_permalink();

	echo '<meta property="og:type" content="article">' . "\n";
	echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $excerpt ) . '">' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $url ) . '">' . "\n";
	if ( $image ) {
		echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
	}
	echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $title ) . '">' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $excerpt ) . '">' . "\n";
	if ( $image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $image ) . '">' . "\n";
	}
}
add_action( 'wp_head', 'ifende_project_og_meta', 3 );

/**
 * Customize the RSS feed: add featured image and reading time to each item.
 *
 * @param string $content RSS item content.
 * @return string Modified content.
 */
function ifende_rss_feed_content( $content ) {
	global $post;

	$output = '';

	// Add featured image.
	if ( has_post_thumbnail( $post ) ) {
		$thumb = get_the_post_thumbnail_url( $post, 'medium' );
		$output .= '<p><img src="' . esc_url( $thumb ) . '" alt="' . esc_attr( get_the_title() ) . '" style="max-width:100%;height:auto;border-radius:4px;"></p>';
	}

	$output .= $content;

	// Add reading time.
	if ( function_exists( 'ifende_reading_time' ) ) {
		$minutes = ifende_reading_time( $post->ID );
		$output .= '<p style="font-size:0.85em;color:#8A8A8A;margin-top:16px;">&#128337; ' . sprintf( _n( '%d minute read', '%d minutes read', $minutes, 'ifende' ), $minutes ) . '</p>';
	}

	// Add visit site CTA.
	$output .= '<p style="margin-top:16px;"><a href="' . esc_url( get_permalink() ) . '" style="color:#21A14E;text-decoration:none;font-weight:bold;">' . esc_html__( 'Read on website &rarr;', 'ifende' ) . '</a></p>';

	return $output;
}
add_filter( 'the_content_feed', 'ifende_rss_feed_content' );
add_filter( 'the_excerpt_rss', 'ifende_rss_feed_content' );

/**
 * Add projects to the default RSS feed.
 *
 * @param WP_Query $query The main query.
 */
function ifende_add_projects_to_feed( $query ) {
	if ( $query->is_feed() && $query->is_main_query() ) {
		$query->set( 'post_type', [ 'post', 'ifende_project' ] );
	}
}
add_action( 'pre_get_posts', 'ifende_add_projects_to_feed' );

/**
 * Register Projects CPT with popular SEO plugin sitemaps automatically.
 * Works with Yoast SEO, RankMath, and All in One SEO.
 *
 * @param array $post_types Post types to include in sitemap.
 * @return array Modified post types.
 */
function ifende_sitemap_post_types( $post_types ) {
	$post_types[] = 'ifende_project';
	return $post_types;
}
// Yoast SEO.
add_filter( 'wpseo_sitemap_post_type_include', function( $include, $post_type ) {
	if ( 'ifende_project' === $post_type ) {
		return true;
	}
	return $include;
}, 10, 2 );
// RankMath.
add_filter( 'rank_math/sitemap/post_type', function( $post_types ) {
	$post_types[] = 'ifende_project';
	return $post_types;
} );
