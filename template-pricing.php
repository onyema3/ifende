<?php
/**
 * Template Name: Pricing
 * Template Post Type: page
 *
 * Dedicated pricing/packages page with three tiers, feature lists, and
 * "Book a Call" CTAs that tie into the booking integration.
 *
 * @package Ifende
 * @since   1.6.0
 */

get_header();

$booking_url = '';
$provider    = get_theme_mod( 'ifende_booking_provider', 'none' );
if ( 'calendly' === $provider ) {
	$booking_url = get_theme_mod( 'ifende_booking_calendly_url', '' );
} elseif ( 'calcom' === $provider ) {
	$booking_url = get_theme_mod( 'ifende_booking_calcom_url', '' );
} elseif ( 'custom' === $provider ) {
	$booking_url = get_theme_mod( 'ifende_booking_custom_url', '' );
}
$cta_text   = empty( $booking_url ) ? __( 'Get In Touch', 'ifende' ) : __( 'Book a Call', 'ifende' );
$cta_href   = empty( $booking_url ) ? '#contact' : $booking_url;
$cta_target = empty( $booking_url ) ? '' : ' target="_blank" rel="noopener"';
?>

<main id="main-content" tabindex="-1" class="fullwidth-content">
  <section class="if-section" style="padding-top:140px;">
    <div class="section-label"><?php esc_html_e( 'Pricing', 'ifende' ); ?></div>
    <h1 class="section-title" style="margin-bottom:12px;">Simple <em>Transparent</em> Pricing</h1>
    <p class="section-sub" style="margin-bottom:64px;"><?php esc_html_e( 'Choose a package that fits your needs. All plans include a free initial consultation.', 'ifende' ); ?></p>

    <div class="pricing-grid">
      <div class="pricing-card">
        <div class="pricing-card-header">
          <span class="pricing-tier"><?php esc_html_e( 'Starter', 'ifende' ); ?></span>
          <div class="pricing-amount">$499</div>
          <p class="pricing-desc"><?php esc_html_e( 'Perfect for small businesses and personal sites.', 'ifende' ); ?></p>
        </div>
        <ul class="pricing-features">
          <li><?php esc_html_e( '5-page website', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Mobile responsive', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Basic SEO', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Contact form', 'ifende' ); ?></li>
          <li><?php esc_html_e( '1 revision round', 'ifende' ); ?></li>
          <li><?php esc_html_e( '7-day delivery', 'ifende' ); ?></li>
        </ul>
        <a href="<?php echo esc_url( $cta_href ); ?>" class="btn-secondary pricing-cta"<?php echo $cta_target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $cta_text ); ?> &rarr;</a>
      </div>

      <div class="pricing-card pricing-card--featured">
        <div class="pricing-card-badge"><?php esc_html_e( 'Most Popular', 'ifende' ); ?></div>
        <div class="pricing-card-header">
          <span class="pricing-tier"><?php esc_html_e( 'Professional', 'ifende' ); ?></span>
          <div class="pricing-amount">$1,299</div>
          <p class="pricing-desc"><?php esc_html_e( 'For growing businesses that need more.', 'ifende' ); ?></p>
        </div>
        <ul class="pricing-features">
          <li><?php esc_html_e( '10-page website', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Custom design', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Advanced SEO', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'E-commerce ready', 'ifende' ); ?></li>
          <li><?php esc_html_e( '3 revision rounds', 'ifende' ); ?></li>
          <li><?php esc_html_e( '30 days support', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Analytics setup', 'ifende' ); ?></li>
        </ul>
        <a href="<?php echo esc_url( $cta_href ); ?>" class="btn-primary pricing-cta"<?php echo $cta_target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $cta_text ); ?> &rarr;</a>
      </div>

      <div class="pricing-card">
        <div class="pricing-card-header">
          <span class="pricing-tier"><?php esc_html_e( 'Enterprise', 'ifende' ); ?></span>
          <div class="pricing-amount"><?php esc_html_e( 'Custom', 'ifende' ); ?></div>
          <p class="pricing-desc"><?php esc_html_e( 'Tailored solutions for complex requirements.', 'ifende' ); ?></p>
        </div>
        <ul class="pricing-features">
          <li><?php esc_html_e( 'Unlimited pages', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Full custom build', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Priority support', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Performance audit', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Ongoing maintenance', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'Dedicated manager', 'ifende' ); ?></li>
          <li><?php esc_html_e( 'SLA guarantee', 'ifende' ); ?></li>
        </ul>
        <a href="<?php echo esc_url( $cta_href ); ?>" class="btn-secondary pricing-cta"<?php echo $cta_target; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>><?php echo esc_html( $cta_text ); ?> &rarr;</a>
      </div>
    </div>

    <?php while ( have_posts() ) : the_post(); if ( '' !== trim( get_the_content() ) ) : ?>
      <div class="pricing-extra" style="margin-top:80px;max-width:780px;margin-left:auto;margin-right:auto;">
        <div class="entry-content"><?php the_content(); ?></div>
      </div>
    <?php endif; endwhile; ?>
  </section>
</main>

<style>
.pricing-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:24px;align-items:start;}
.pricing-card{position:relative;border:1px solid var(--border);border-radius:4px;padding:40px 32px;display:flex;flex-direction:column;}
.pricing-card--featured{border-color:var(--green);border-width:2px;transform:scale(1.03);}
.pricing-card-badge{position:absolute;top:-12px;left:50%;transform:translateX(-50%);background:var(--green);color:var(--black);padding:4px 14px;border-radius:20px;font-size:0.62rem;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;white-space:nowrap;}
.pricing-card-header{margin-bottom:24px;}
.pricing-tier{font-size:0.72rem;letter-spacing:2px;text-transform:uppercase;color:var(--green);}
.pricing-amount{font-family:'Cormorant Garamond',serif;font-size:2.8rem;font-weight:300;color:var(--white);margin:8px 0;}
.pricing-desc{font-size:0.82rem;color:var(--grey);line-height:1.5;}
.pricing-features{list-style:none;padding:0;margin:0 0 32px;flex:1;}
.pricing-features li{padding:8px 0;border-bottom:1px solid var(--border);font-size:0.85rem;color:rgba(245,242,236,0.7);}
.pricing-features li::before{content:'✓';color:var(--green);margin-right:10px;font-weight:700;}
.pricing-cta{width:100%;text-align:center;justify-content:center;}
@media(max-width:900px){.pricing-grid{grid-template-columns:1fr;max-width:400px;margin:0 auto;}.pricing-card--featured{transform:none;}}
</style>

<?php get_footer(); ?>
