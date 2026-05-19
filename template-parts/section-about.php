<?php
/**
 * Template Part: About Section
 *
 * @package Ifende
 */

$about_bio  = ifende_opt( 'about_bio', "Hello! I'm Onyemechi Ifende — a Project Manager, Consultant, Web Developer, and Freelancer from Nigeria." );
$location   = ifende_opt( 'about_location', 'Global — Based in Nigeria' );
$freelance  = ifende_opt( 'about_freelance', 'Currently Available' );
$tw_handle  = ifende_opt( 'about_twitter', '@ifende' );
$tw_url     = ifende_opt( 'about_twitter_url', 'https://twitter.com/ifende' );
$skills_raw = ifende_opt( 'about_skills', 'WordPress,Project Management,Web Design,Consulting,Branding,Game Dev,Remote Ops,Team Leadership' );
$skills     = array_map( 'trim', explode( ',', $skills_raw ) );
?>
<section class="if-section dark" id="about">
  <div class="section-label"><?php esc_html_e( 'About Me', 'ifende' ); ?></div>
  <div class="about-grid">
    <div>
      <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'The Man<br>Behind the <em>Work</em>', 'ifende' ) ); ?></h2>
      <p class="section-sub reveal reveal-d1" style="margin-top:24px;"><?php echo esc_html( $about_bio ); ?></p>
      <div style="margin-top:40px;" class="reveal reveal-d2">
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Residence', 'ifende' ); ?></span><span class="about-val"><?php echo esc_html( $location ); ?></span></div>
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Freelance', 'ifende' ); ?></span><span class="about-val" style="color:var(--green);">✓ <?php echo esc_html( $freelance ); ?></span></div>
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Specialties', 'ifende' ); ?></span><span class="about-val"><?php esc_html_e( 'Project Management · Web Dev · Consulting · Branding · Game Dev', 'ifende' ); ?></span></div>
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Twitter/X', 'ifende' ); ?></span><span class="about-val"><a href="<?php echo esc_url( $tw_url ); ?>" target="_blank" rel="noopener" style="color:var(--green);text-decoration:none;"><?php echo esc_html( $tw_handle ); ?></a></span></div>
      </div>
    </div>
    <div class="reveal reveal-d2">
      <div class="section-label" style="margin-bottom:20px;"><?php esc_html_e( 'Core Skills', 'ifende' ); ?></div>
      <div class="skills-progress-list">
        <?php
        foreach ( $skills as $s ) :
          // Parse skill with optional percentage: "WordPress:90" or just "WordPress"
          $skill_parts = array_map( 'trim', explode( ':', $s ) );
          $skill_name  = $skill_parts[0];
          $skill_pct   = isset( $skill_parts[1] ) ? intval( $skill_parts[1] ) : 85;
        ?>
          <div class="skill-progress-item">
            <div class="skill-progress-header">
              <span class="skill-progress-name"><?php echo esc_html( $skill_name ); ?></span>
              <span class="skill-progress-pct"><?php echo esc_html( $skill_pct ); ?>%</span>
            </div>
            <div class="skill-progress-bar">
              <div class="skill-progress-fill" style="--progress:<?php echo esc_attr( $skill_pct ); ?>%"></div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
      <div style="margin-top:40px;padding:32px;border:1px solid var(--border);border-radius:2px;background:rgba(33,161,78,0.04);">
        <div class="section-label" style="margin-bottom:16px;"><?php esc_html_e( 'Current Status', 'ifende' ); ?></div>
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:300;color:var(--white);line-height:1.5;"><?php esc_html_e( 'Open to new projects, collaborations, and consulting engagements.', 'ifende' ); ?></p>
        <a href="#contact" class="btn-primary" style="margin-top:24px;display:inline-flex;"><?php esc_html_e( 'Start a Conversation', 'ifende' ); ?> &rarr;</a>
      </div>
    </div>
  </div>
</section>
