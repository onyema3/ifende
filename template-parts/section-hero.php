<?php
/**
 * Template Part: Hero Section
 *
 * @package Ifende
 */

$name      = ifende_opt( 'hero_name', 'Onyemechi Ifende' );
$label     = ifende_opt( 'hero_label', 'Based in Nigeria · Available Globally' );
$roles_raw = ifende_opt( 'hero_roles', 'Project Manager|Web Developer|Consultant' );
$bio       = ifende_opt( 'hero_bio', 'A multi-disciplinary professional with rich experience in project management, web development, consulting, and branding.' );
$s1n       = ifende_opt( 'hero_stat1_n', '12+' );
$s1l       = ifende_opt( 'hero_stat1_l', 'Clients Served' );
$s2n       = ifende_opt( 'hero_stat2_n', '5+' );
$s2l       = ifende_opt( 'hero_stat2_l', 'Years Experience' );
$s3n       = ifende_opt( 'hero_stat3_n', '4' );
$s3l       = ifende_opt( 'hero_stat3_l', 'Core Services' );
$status    = ifende_opt( 'hero_status', 'Available for Freelance' );
$photo_url = ifende_opt( 'hero_photo_url', '' );

if ( has_custom_logo() ) {
  $lid       = get_theme_mod( 'custom_logo' );
  $photo_url = wp_get_attachment_image_url( $lid, 'full' );
}

$roles = array_map( 'trim', explode( '|', $roles_raw ) );
$np    = explode( ' ', $name, 2 );
$first = $np[0];
$last  = $np[1] ?? '';
?>
<section class="hero-section" id="home">
  <div class="hero-bg"></div>
  <div class="hero-grid-bg"></div>
  <div class="hero-content">
    <div class="hero-label"><?php echo esc_html( $label ); ?></div>
    <h1><?php echo esc_html( $first ); ?><br><em><?php echo esc_html( $last ); ?></em></h1>
    <div class="hero-title-line">
      <?php foreach ( $roles as $i => $r ) : ?>
        <?php if ( $i > 0 ) : ?><span class="title-sep">·</span><?php endif; ?>
        <span><?php echo esc_html( $r ); ?></span>
      <?php endforeach; ?>
    </div>
    <p class="hero-bio"><?php echo esc_html( $bio ); ?></p>
    <div class="hero-actions">
      <a href="#contact" class="btn-primary"><?php esc_html_e( "Let's Work Together", 'ifende' ); ?> <span>&rarr;</span></a>
      <a href="#services" class="btn-secondary"><?php esc_html_e( 'View Services', 'ifende' ); ?></a>
    </div>
    <div class="hero-stats">
      <div><div class="stat-num"><?php echo esc_html( $s1n ); ?></div><div class="stat-label"><?php echo esc_html( $s1l ); ?></div></div>
      <div><div class="stat-num"><?php echo esc_html( $s2n ); ?></div><div class="stat-label"><?php echo esc_html( $s2l ); ?></div></div>
      <div><div class="stat-num"><?php echo esc_html( $s3n ); ?></div><div class="stat-label"><?php echo esc_html( $s3l ); ?></div></div>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-photo-wrap">
      <div class="hero-photo-border"></div>
      <div class="hero-photo">
        <?php if ( $photo_url ) : ?>
          <img src="<?php echo esc_url( $photo_url ); ?>" alt="<?php echo esc_attr( $name ); ?>" loading="lazy" width="380" height="480">
        <?php else : ?>
          <div class="hero-photo-placeholder">
            <div class="photo-initials"><?php echo esc_html( implode( '', array_map( fn( $p ) => strtoupper( substr( $p, 0, 1 ) ), explode( ' ', $name ) ) ) ); ?></div>
            <div class="photo-name"><?php echo esc_html( $name ); ?></div>
          </div>
        <?php endif; ?>
      </div>
      <div class="hero-status">
        <div class="status-dot"></div>
        <div class="status-text"><?php echo esc_html( $status ); ?></div>
      </div>
    </div>
  </div>
</section>
