<?php
/**
 * Template Part: Clients Section
 *
 * @package Ifende
 */

$clients_intro = get_theme_mod( 'ifende_clients_intro', 'A growing portfolio of businesses across Nigeria who trust me to deliver exceptional digital and consultancy work.' );
$clients_raw   = get_theme_mod( 'ifende_clients_list', "Leadetics|https://leadetics.ng/|🔷\nLibertyhub|https://libertyhub.ng|🟢\nVTLeasing Limited|https://vtleasing.com/|🔵\nStratagem Legal|https://stratagemlp.com/|⚖️\nFort Solutions|https://fortsolutions.net|🏗️\nLiberty Mall|https://libertymall.ng|🛍️\nLibertyhub MCS|https://libertyhubmcs.ng|🤝\nPortal Consultancy|https://portalconsultancy.com.ng/|📋\nCFHRAD|https://cfhrad.org/|🏥\nJos Water Services|https://www.jwsc.pl.gov.ng/|💧\nLiberty Matrix|http://libertymatrix.ng/|🔗\nUrban Bounty MCS|#|🌱" );
$clients       = array_filter( array_map( 'trim', explode( "\n", $clients_raw ) ) );
?>
<section class="if-section dark" id="clients">
  <div class="clients-intro">
    <div>
      <div class="section-label"><?php esc_html_e( 'Trusted By', 'ifende' ); ?></div>
      <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'Clients &amp;<br><em>Partners</em>', 'ifende' ) ); ?></h2>
    </div>
    <p class="section-sub reveal reveal-d1" style="align-self:flex-end;"><?php echo esc_html( $clients_intro ); ?></p>
  </div>
  <div class="clients-grid reveal">
    <?php
    foreach ( $clients as $client_line ) :
      $parts = array_map( 'trim', explode( '|', $client_line ) );
      if ( count( $parts ) < 2 ) continue;
      $cname = $parts[0];
      $curl  = $parts[1];
      $cicon = $parts[2] ?? '🔗';
    ?>
      <a href="<?php echo esc_url( $curl ); ?>" target="_blank" rel="noopener" class="client-card">
        <span style="font-size:1.5rem;" aria-hidden="true"><?php echo esc_html( $cicon ); ?></span>
        <span class="client-name"><?php echo esc_html( $cname ); ?></span>
        <span class="client-arrow" aria-hidden="true">↗</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>
