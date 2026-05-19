<?php
/**
 * Template Part: Marquee Section
 *
 * @package Ifende
 */

$marquee_raw   = get_theme_mod( 'ifende_marquee_items', 'Project Management,Web Development,Consulting,Branding,Game Development,Remote Operations,WordPress,Digital Strategy' );
$marquee_items = array_map( 'trim', explode( ',', $marquee_raw ) );
?>
<div class="marquee-section" aria-hidden="true">
  <div class="marquee-track" id="marqueeTrack">
    <?php foreach ( $marquee_items as $item ) : ?>
      <span class="marquee-item"><span class="marquee-dot"></span><?php echo esc_html( $item ); ?></span>
    <?php endforeach; ?>
  </div>
</div>
