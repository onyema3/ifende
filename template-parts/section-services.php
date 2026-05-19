<?php
/**
 * Template Part: Services Section
 *
 * @package Ifende
 */

$services_defaults = [
  1 => [ 'icon' => '🌐', 'title' => 'Web Development', 'desc' => 'I develop unique web presences that deliver your dream concepts to life. Your website designed and built to your specifications — not just websites, but dreams and relationships. Built on WordPress and modern web technologies.' ],
  2 => [ 'icon' => '🎯', 'title' => 'Consulting', 'desc' => 'I consult on various business processes giving clients a holistic experience. My aim is to be your one-stop spot for your virtual enterprise — maximising value and advocating for growth at every step.' ],
  3 => [ 'icon' => '✦', 'title' => 'Branding', 'desc' => 'Branding is what distinguishes you from your competitors and affects your bottom line. Your brand needs to be memorable and distinctive — my design approach has in-depth knowledge of marketing strategies.' ],
  4 => [ 'icon' => '🎮', 'title' => 'Game Development', 'desc' => 'Developing memorable and unique mobile games for Android, iOS, and video game platforms. I create immersive gaming experiences that engage, entertain, and leave lasting impressions on players.' ],
];
?>
<section class="if-section" id="services">
  <div class="section-label"><?php esc_html_e( 'What I Do', 'ifende' ); ?></div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'Services I<br><em>Offer</em>', 'ifende' ) ); ?></h2>
    <p class="section-sub reveal reveal-d1"><?php esc_html_e( 'From web presence to business transformation — I bring a holistic approach to every engagement.', 'ifende' ); ?></p>
  </div>
  <div class="services-grid reveal">
    <?php
    foreach ( $services_defaults as $i => $svc ) :
      $num   = str_pad( $i, 2, '0', STR_PAD_LEFT );
      $icon  = get_theme_mod( "ifende_service_{$i}_icon", $svc['icon'] );
      $title = get_theme_mod( "ifende_service_{$i}_title", $svc['title'] );
      $desc  = get_theme_mod( "ifende_service_{$i}_desc", $svc['desc'] );
      if ( empty( $title ) ) continue;
    ?>
      <div class="service-card">
        <div class="service-num"><?php echo esc_html( $num ); ?></div>
        <span class="service-icon" aria-hidden="true"><?php echo esc_html( $icon ); ?></span>
        <h3><?php echo esc_html( $title ); ?></h3>
        <p><?php echo esc_html( $desc ); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>
