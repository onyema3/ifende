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
