<?php get_header();
$name        = ifende_opt('hero_name',     'Onyemechi Ifende');
$label       = ifende_opt('hero_label',    'Based in Nigeria · Available Globally');
$roles_raw   = ifende_opt('hero_roles',    'Project Manager|Web Developer|Consultant');
$bio         = ifende_opt('hero_bio',      'A multi-disciplinary professional with rich experience in project management, web development, consulting, and branding.');
$s1n         = ifende_opt('hero_stat1_n',  '12+');
$s1l         = ifende_opt('hero_stat1_l',  'Clients Served');
$s2n         = ifende_opt('hero_stat2_n',  '5+');
$s2l         = ifende_opt('hero_stat2_l',  'Years Experience');
$s3n         = ifende_opt('hero_stat3_n',  '4');
$s3l         = ifende_opt('hero_stat3_l',  'Core Services');
$status      = ifende_opt('hero_status',   'Available for Freelance');
$photo_url   = ifende_opt('hero_photo_url','');
$about_bio   = ifende_opt('about_bio',     "Hello! I'm Onyemechi Ifende — a Project Manager, Consultant, Web Developer, and Freelancer from Nigeria.");
$location    = ifende_opt('about_location','Global — Based in Nigeria');
$freelance   = ifende_opt('about_freelance','Currently Available');
$tw_handle   = ifende_opt('about_twitter', '@ifende');
$tw_url      = ifende_opt('about_twitter_url','https://twitter.com/ifende');
$skills_raw  = ifende_opt('about_skills',  'WordPress,Project Management,Web Design,Consulting,Branding,Game Dev,Remote Ops,Team Leadership');
$email       = ifende_opt('email',         'hello@ifende.com');
$instagram   = ifende_opt('instagram_url', 'https://instagram.com/onyema.ifende');
$twitter_url = ifende_opt('twitter_url',   'https://twitter.com/ifende');
if(has_custom_logo()){ $lid=get_theme_mod('custom_logo'); $photo_url=wp_get_attachment_image_url($lid,'full'); }
$roles  = array_map('trim', explode('|', $roles_raw));
$skills = array_map('trim', explode(',', $skills_raw));
$np     = explode(' ', $name, 2);
$first  = $np[0]; $last = $np[1] ?? '';
?>

<!-- HERO -->
<main id="main-content">
<section class="hero-section" id="home">
  <div class="hero-bg"></div>
  <div class="hero-grid-bg"></div>
  <div class="hero-content">
    <div class="hero-label"><?php echo esc_html($label); ?></div>
    <h1><?php echo esc_html($first); ?><br><em><?php echo esc_html($last); ?></em></h1>
    <div class="hero-title-line">
      <?php foreach($roles as $i=>$r): ?>
        <?php if($i>0): ?><span class="title-sep">·</span><?php endif; ?>
        <span><?php echo esc_html($r); ?></span>
      <?php endforeach; ?>
    </div>
    <p class="hero-bio"><?php echo esc_html($bio); ?></p>
    <div class="hero-actions">
      <a href="#contact" class="btn-primary"><?php esc_html_e( "Let's Work Together", 'ifende' ); ?> <span>&rarr;</span></a>
      <a href="#services" class="btn-secondary"><?php esc_html_e( 'View Services', 'ifende' ); ?></a>
    </div>
    <div class="hero-stats">
      <div><div class="stat-num"><?php echo esc_html($s1n); ?></div><div class="stat-label"><?php echo esc_html($s1l); ?></div></div>
      <div><div class="stat-num"><?php echo esc_html($s2n); ?></div><div class="stat-label"><?php echo esc_html($s2l); ?></div></div>
      <div><div class="stat-num"><?php echo esc_html($s3n); ?></div><div class="stat-label"><?php echo esc_html($s3l); ?></div></div>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-photo-wrap">
      <div class="hero-photo-border"></div>
      <div class="hero-photo">
        <?php if($photo_url): ?>
          <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($name); ?>" loading="lazy" width="380" height="480">
        <?php else: ?>
          <div class="hero-photo-placeholder">
            <div class="photo-initials"><?php
              echo esc_html(implode('',array_map(fn($p)=>strtoupper(substr($p,0,1)),explode(' ',$name))));
            ?></div>
            <div class="photo-name"><?php echo esc_html($name); ?></div>
          </div>
        <?php endif; ?>
      </div>
      <div class="hero-status">
        <div class="status-dot"></div>
        <div class="status-text"><?php echo esc_html($status); ?></div>
      </div>
    </div>
  </div>
</section>

<!-- MARQUEE -->
<div class="marquee-section" aria-hidden="true">
  <div class="marquee-track" id="marqueeTrack">
    <?php
    $marquee_raw   = get_theme_mod( 'ifende_marquee_items', 'Project Management,Web Development,Consulting,Branding,Game Development,Remote Operations,WordPress,Digital Strategy' );
    $marquee_items = array_map( 'trim', explode( ',', $marquee_raw ) );
    foreach ( $marquee_items as $item ) :
    ?>
      <span class="marquee-item"><span class="marquee-dot"></span><?php echo esc_html( $item ); ?></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ABOUT -->
<section class="if-section dark" id="about">
  <div class="section-label"><?php esc_html_e( 'About Me', 'ifende' ); ?></div>
  <div class="about-grid">
    <div>
      <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'The Man<br>Behind the <em>Work</em>', 'ifende' ) ); ?></h2>
      <p class="section-sub reveal reveal-d1" style="margin-top:24px;"><?php echo esc_html($about_bio); ?></p>
      <div style="margin-top:40px;" class="reveal reveal-d2">
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Residence', 'ifende' ); ?></span><span class="about-val"><?php echo esc_html($location); ?></span></div>
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Freelance', 'ifende' ); ?></span><span class="about-val" style="color:var(--green);">✓ <?php echo esc_html($freelance); ?></span></div>
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Specialties', 'ifende' ); ?></span><span class="about-val"><?php esc_html_e( 'Project Management · Web Dev · Consulting · Branding · Game Dev', 'ifende' ); ?></span></div>
        <div class="about-item"><span class="about-key"><?php esc_html_e( 'Twitter/X', 'ifende' ); ?></span><span class="about-val"><a href="<?php echo esc_url($tw_url); ?>" target="_blank" rel="noopener" style="color:var(--green);text-decoration:none;"><?php echo esc_html($tw_handle); ?></a></span></div>
      </div>
    </div>
    <div class="reveal reveal-d2">
      <div class="section-label" style="margin-bottom:20px;"><?php esc_html_e( 'Core Skills', 'ifende' ); ?></div>
      <div class="skills-grid">
        <?php foreach($skills as $s): ?><div class="skill-tag"><?php echo esc_html($s); ?></div><?php endforeach; ?>
      </div>
      <div style="margin-top:40px;padding:32px;border:1px solid var(--border);border-radius:2px;background:rgba(33,161,78,0.04);">
        <div class="section-label" style="margin-bottom:16px;"><?php esc_html_e( 'Current Status', 'ifende' ); ?></div>
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:300;color:var(--white);line-height:1.5;"><?php esc_html_e( 'Open to new projects, collaborations, and consulting engagements.', 'ifende' ); ?></p>
        <a href="#contact" class="btn-primary" style="margin-top:24px;display:inline-flex;"><?php esc_html_e( 'Start a Conversation', 'ifende' ); ?> &rarr;</a>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="if-section" id="services">
  <div class="section-label"><?php esc_html_e( 'What I Do', 'ifende' ); ?></div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'Services I<br><em>Offer</em>', 'ifende' ) ); ?></h2>
    <p class="section-sub reveal reveal-d1"><?php esc_html_e( 'From web presence to business transformation — I bring a holistic approach to every engagement.', 'ifende' ); ?></p>
  </div>
  <div class="services-grid reveal">
    <?php
    $services_defaults = [
      1 => [ 'icon' => '🌐', 'title' => 'Web Development', 'desc' => 'I develop unique web presences that deliver your dream concepts to life. Your website designed and built to your specifications — not just websites, but dreams and relationships. Built on WordPress and modern web technologies.' ],
      2 => [ 'icon' => '🎯', 'title' => 'Consulting', 'desc' => 'I consult on various business processes giving clients a holistic experience. My aim is to be your one-stop spot for your virtual enterprise — maximising value and advocating for growth at every step.' ],
      3 => [ 'icon' => '✦', 'title' => 'Branding', 'desc' => 'Branding is what distinguishes you from your competitors and affects your bottom line. Your brand needs to be memorable and distinctive — my design approach has in-depth knowledge of marketing strategies.' ],
      4 => [ 'icon' => '🎮', 'title' => 'Game Development', 'desc' => 'Developing memorable and unique mobile games for Android, iOS, and video game platforms. I create immersive gaming experiences that engage, entertain, and leave lasting impressions on players.' ],
    ];
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

<!-- CLIENTS -->
<section class="if-section dark" id="clients">
  <div class="clients-intro">
    <div>
      <div class="section-label"><?php esc_html_e( 'Trusted By', 'ifende' ); ?></div>
      <h2 class="section-title reveal"><?php echo wp_kses_post( __( 'Clients &amp;<br><em>Partners</em>', 'ifende' ) ); ?></h2>
    </div>
    <?php $clients_intro = get_theme_mod( 'ifende_clients_intro', 'A growing portfolio of businesses across Nigeria who trust me to deliver exceptional digital and consultancy work.' ); ?>
    <p class="section-sub reveal reveal-d1" style="align-self:flex-end;"><?php echo esc_html( $clients_intro ); ?></p>
  </div>
  <div class="clients-grid reveal">
    <?php
    $clients_raw = get_theme_mod( 'ifende_clients_list', "Leadetics|https://leadetics.ng/|🔷\nLibertyhub|https://libertyhub.ng|🟢\nVTLeasing Limited|https://vtleasing.com/|🔵\nStratagem Legal|https://stratagemlp.com/|⚖️\nFort Solutions|https://fortsolutions.net|🏗️\nLiberty Mall|https://libertymall.ng|🛍️\nLibertyhub MCS|https://libertyhubmcs.ng|🤝\nPortal Consultancy|https://portalconsultancy.com.ng/|📋\nCFHRAD|https://cfhrad.org/|🏥\nJos Water Services|https://www.jwsc.pl.gov.ng/|💧\nLiberty Matrix|http://libertymatrix.ng/|🔗\nUrban Bounty MCS|#|🌱" );
    $clients = array_filter( array_map( 'trim', explode( "\n", $clients_raw ) ) );
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

<!-- CONTACT -->
<section class="if-section" id="contact">
  <div class="section-label"><?php esc_html_e( 'Get In Touch', 'ifende' ); ?></div>
  <div class="contact-grid">
    <div>
      <h2 class="section-title reveal"><?php echo wp_kses_post( __( "Let's Build<br>Something <em>Great</em>", 'ifende' ) ); ?></h2>
      <p class="section-sub reveal reveal-d1" style="margin-top:24px;margin-bottom:48px;"><?php esc_html_e( "Have a project in mind? Looking for a consultant, developer, or creative partner? I'd love to hear from you.", 'ifende' ); ?></p>
      <div class="reveal reveal-d2">
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">📍</div><div><div class="contact-label"><?php esc_html_e( 'Location', 'ifende' ); ?></div><div class="contact-val"><?php echo esc_html($location); ?></div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">💼</div><div><div class="contact-label"><?php esc_html_e( 'Availability', 'ifende' ); ?></div><div class="contact-val" style="color:var(--green);"><?php esc_html_e( 'Open for Freelance & Consulting', 'ifende' ); ?></div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">🌐</div><div><div class="contact-label"><?php esc_html_e( 'Website', 'ifende' ); ?></div><div class="contact-val"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" style="color:var(--white);text-decoration:none;"><?php echo esc_html( wp_parse_url( home_url(), PHP_URL_HOST ) ); ?></a></div></div></div>
      </div>
      <div style="margin-top:40px;">
        <div class="contact-label" style="margin-bottom:14px;"><?php esc_html_e( 'Follow Me', 'ifende' ); ?></div>
        <div class="socials">
          <?php if($twitter_url): ?><a href="<?php echo esc_url($twitter_url); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e( 'Twitter / X', 'ifende' ); ?>">𝕏</a><?php endif; ?>
          <?php if($instagram): ?><a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e( 'Instagram', 'ifende' ); ?>">📷</a><?php endif; ?>
          <a href="<?php echo esc_url( home_url( '/' ) ); ?>" target="_blank" rel="noopener" class="social-link" aria-label="<?php esc_attr_e( 'Website', 'ifende' ); ?>">🌐</a>
        </div>
      </div>
    </div>
    <div class="reveal reveal-d2">
      <form class="contact-form" id="contactForm" aria-label="<?php esc_attr_e( 'Contact form', 'ifende' ); ?>">
        <div class="form-row">
          <div class="form-group"><label for="fname"><?php esc_html_e( 'First Name', 'ifende' ); ?></label><input type="text" id="fname" name="fname" placeholder="<?php esc_attr_e( 'Amaka', 'ifende' ); ?>" autocomplete="given-name" required></div>
          <div class="form-group"><label for="lname"><?php esc_html_e( 'Last Name', 'ifende' ); ?></label><input type="text" id="lname" name="lname" placeholder="<?php esc_attr_e( 'Okafor', 'ifende' ); ?>" autocomplete="family-name" required></div>
        </div>
        <div class="form-group"><label for="femail"><?php esc_html_e( 'Email Address', 'ifende' ); ?></label><input type="email" id="femail" name="email" placeholder="<?php esc_attr_e( 'you@example.com', 'ifende' ); ?>" autocomplete="email" required></div>
        <div class="form-group"><label for="fsubject"><?php esc_html_e( 'Subject', 'ifende' ); ?></label><input type="text" id="fsubject" name="subject" placeholder="<?php esc_attr_e( 'Web development project...', 'ifende' ); ?>"></div>
        <div class="form-group"><label for="fmessage"><?php esc_html_e( 'Your Message', 'ifende' ); ?></label><textarea id="fmessage" name="message" placeholder="<?php esc_attr_e( 'Tell me about your project...', 'ifende' ); ?>" required></textarea></div>
        <button type="submit" class="btn-submit" id="submitBtn" aria-busy="false"><?php esc_html_e( 'Send Message', 'ifende' ); ?> &rarr;</button>
        <div id="formMsg" role="status" aria-live="polite" style="display:none;font-family:'DM Mono',monospace;font-size:0.72rem;letter-spacing:1px;color:var(--green);margin-top:8px;"></div>
      </form>
    </div>
  </div>
</section>
</main>

<?php get_footer(); ?>
