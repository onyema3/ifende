<?php
/**
 * Title: Marquee Strip
 * Slug: ifende/marquee
 * Categories: ifende
 * Description: Scrolling text strip of skills/services. The CSS animation loops infinitely; duplicate the items inline to keep the loop seamless.
 * Keywords: marquee, ticker, scroller, strip
 * Inserter: yes
 *
 * Mirrors template-parts/section-marquee.php. Each item is duplicated once
 * in the markup so the CSS animation can loop seamlessly (translateX(-50%)
 * reaches the start of the duplicate).
 */

$ifende_marquee_items = [
	'Project Management', 'Web Development', 'Consulting', 'Branding',
	'Game Development', 'Remote Operations', 'WordPress', 'Digital Strategy',
];

$ifende_marquee_line = '';
foreach ( $ifende_marquee_items as $ifende_marquee_item ) {
	$ifende_marquee_line .= '<span class="marquee-item"><span class="marquee-dot"></span>' . esc_html( $ifende_marquee_item ) . '</span>';
}
$ifende_marquee_track = $ifende_marquee_line . $ifende_marquee_line;
?>
<!-- wp:html -->
<div class="marquee-section" aria-hidden="true">
  <div class="marquee-track">
    <?php echo $ifende_marquee_track; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- $ifende_marquee_line is built from esc_html()-escaped values above. ?>
  </div>
</div>
<!-- /wp:html -->
