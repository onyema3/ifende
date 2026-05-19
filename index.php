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
      <a href="#contact" class="btn-primary">Let's Work Together <span>→</span></a>
      <a href="#services" class="btn-secondary">View Services</a>
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
          <img src="<?php echo esc_url($photo_url); ?>" alt="<?php echo esc_attr($name); ?>">
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
<div class="marquee-section">
  <div class="marquee-track" id="marqueeTrack">
    <?php foreach(['Project Management','Web Development','Consulting','Branding','Game Development','Remote Operations','WordPress','Digital Strategy'] as $item): ?>
      <span class="marquee-item"><span class="marquee-dot"></span><?php echo esc_html($item); ?></span>
    <?php endforeach; ?>
  </div>
</div>

<!-- ABOUT -->
<section class="if-section dark" id="about">
  <div class="section-label">About Me</div>
  <div class="about-grid">
    <div>
      <h2 class="section-title reveal">The Man<br>Behind the <em>Work</em></h2>
      <p class="section-sub reveal reveal-d1" style="margin-top:24px;"><?php echo esc_html($about_bio); ?></p>
      <div style="margin-top:40px;" class="reveal reveal-d2">
        <div class="about-item"><span class="about-key">Residence</span><span class="about-val"><?php echo esc_html($location); ?></span></div>
        <div class="about-item"><span class="about-key">Freelance</span><span class="about-val" style="color:var(--green);">✓ <?php echo esc_html($freelance); ?></span></div>
        <div class="about-item"><span class="about-key">Specialties</span><span class="about-val">Project Management · Web Dev · Consulting · Branding · Game Dev</span></div>
        <div class="about-item"><span class="about-key">Twitter/X</span><span class="about-val"><a href="<?php echo esc_url($tw_url); ?>" target="_blank" style="color:var(--green);text-decoration:none;"><?php echo esc_html($tw_handle); ?></a></span></div>
      </div>
    </div>
    <div class="reveal reveal-d2">
      <div class="section-label" style="margin-bottom:20px;">Core Skills</div>
      <div class="skills-grid">
        <?php foreach($skills as $s): ?><div class="skill-tag"><?php echo esc_html($s); ?></div><?php endforeach; ?>
      </div>
      <div style="margin-top:40px;padding:32px;border:1px solid var(--border);border-radius:2px;background:rgba(33,161,78,0.04);">
        <div class="section-label" style="margin-bottom:16px;">Current Status</div>
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:300;color:var(--white);line-height:1.5;">Open to new projects, collaborations, and consulting engagements.</p>
        <a href="#contact" class="btn-primary" style="margin-top:24px;display:inline-flex;">Start a Conversation →</a>
      </div>
    </div>
  </div>
</section>

<!-- SERVICES -->
<section class="if-section" id="services">
  <div class="section-label">What I Do</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title reveal">Services I<br><em>Offer</em></h2>
    <p class="section-sub reveal reveal-d1">From web presence to business transformation — I bring a holistic approach to every engagement.</p>
  </div>
  <div class="services-grid reveal">
    <?php foreach([
      ['01','🌐','Web Development','I develop unique web presences that deliver your dream concepts to life. Your website designed and built to your specifications — not just websites, but dreams and relationships. Built on WordPress and modern web technologies.'],
      ['02','🎯','Consulting','I consult on various business processes giving clients a holistic experience. My aim is to be your one-stop spot for your virtual enterprise — maximising value and advocating for growth at every step.'],
      ['03','✦','Branding','Branding is what distinguishes you from your competitors and affects your bottom line. Your brand needs to be memorable and distinctive — my design approach has in-depth knowledge of marketing strategies.'],
      ['04','🎮','Game Development','Developing memorable and unique mobile games for Android, iOS, and video game platforms. I create immersive gaming experiences that engage, entertain, and leave lasting impressions on players.'],
    ] as [$num,$icon,$title,$desc]): ?>
      <div class="service-card">
        <div class="service-num"><?php echo esc_html($num); ?></div>
        <span class="service-icon"><?php echo $icon; ?></span>
        <h3><?php echo esc_html($title); ?></h3>
        <p><?php echo esc_html($desc); ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- CLIENTS -->
<section class="if-section dark" id="clients">
  <div class="clients-intro">
    <div>
      <div class="section-label">Trusted By</div>
      <h2 class="section-title reveal">Clients &amp;<br><em>Partners</em></h2>
    </div>
    <p class="section-sub reveal reveal-d1" style="align-self:flex-end;">A growing portfolio of businesses across Nigeria who trust me to deliver exceptional digital and consultancy work.</p>
  </div>
  <div class="clients-grid reveal">
    <?php foreach([
      ['Leadetics','https://leadetics.ng/','🔷'],
      ['Libertyhub','https://libertyhub.ng','🟢'],
      ['VTLeasing Limited','https://vtleasing.com/','🔵'],
      ['Stratagem Legal','https://stratagemlp.com/','⚖️'],
      ['Fort Solutions','https://fortsolutions.net','🏗️'],
      ['Liberty Mall','https://libertymall.ng','🛍️'],
      ['Libertyhub MCS','https://libertyhubmcs.ng','🤝'],
      ['Portal Consultancy','https://portalconsultancy.com.ng/','📋'],
      ['CFHRAD','https://cfhrad.org/','🏥'],
      ['Jos Water Services','https://www.jwsc.pl.gov.ng/','💧'],
      ['Liberty Matrix','http://libertymatrix.ng/','🔗'],
      ['Urban Bounty MCS','#','🌱'],
    ] as [$cname,$curl,$cicon]): ?>
      <a href="<?php echo esc_url($curl); ?>" target="_blank" rel="noopener" class="client-card">
        <span style="font-size:1.5rem;"><?php echo $cicon; ?></span>
        <span class="client-name"><?php echo esc_html($cname); ?></span>
        <span class="client-arrow">↗</span>
      </a>
    <?php endforeach; ?>
  </div>
</section>

<!-- CONTACT -->
<section class="if-section" id="contact">
  <div class="section-label">Get In Touch</div>
  <div class="contact-grid">
    <div>
      <h2 class="section-title reveal">Let's Build<br>Something <em>Great</em></h2>
      <p class="section-sub reveal reveal-d1" style="margin-top:24px;margin-bottom:48px;">Have a project in mind? Looking for a consultant, developer, or creative partner? I'd love to hear from you.</p>
      <div class="reveal reveal-d2">
        <div class="contact-item"><div class="contact-icon">📍</div><div><div class="contact-label">Location</div><div class="contact-val"><?php echo esc_html($location); ?></div></div></div>
        <div class="contact-item"><div class="contact-icon">💼</div><div><div class="contact-label">Availability</div><div class="contact-val" style="color:var(--green);">Open for Freelance &amp; Consulting</div></div></div>
        <div class="contact-item"><div class="contact-icon">🌐</div><div><div class="contact-label">Website</div><div class="contact-val"><a href="https://ifende.com" style="color:var(--white);text-decoration:none;">ifende.com</a></div></div></div>
      </div>
      <div style="margin-top:40px;">
        <div class="contact-label" style="margin-bottom:14px;">Follow Me</div>
        <div class="socials">
          <?php if($twitter_url): ?><a href="<?php echo esc_url($twitter_url); ?>" target="_blank" class="social-link">𝕏</a><?php endif; ?>
          <?php if($instagram): ?><a href="<?php echo esc_url($instagram); ?>" target="_blank" class="social-link">📷</a><?php endif; ?>
          <a href="https://ifende.com" target="_blank" class="social-link">🌐</a>
        </div>
      </div>
    </div>
    <div class="reveal reveal-d2">
      <form class="contact-form" id="contactForm">
        <div class="form-row">
          <div class="form-group"><label>First Name</label><input type="text" id="fname" placeholder="Amaka" required></div>
          <div class="form-group"><label>Last Name</label><input type="text" id="lname" placeholder="Okafor" required></div>
        </div>
        <div class="form-group"><label>Email Address</label><input type="email" id="femail" placeholder="you@example.com" required></div>
        <div class="form-group"><label>Subject</label><input type="text" id="fsubject" placeholder="Web development project..."></div>
        <div class="form-group"><label>Your Message</label><textarea id="fmessage" placeholder="Tell me about your project..." required></textarea></div>
        <button type="submit" class="btn-submit" id="submitBtn">Send Message →</button>
        <div id="formMsg" style="display:none;font-family:'DM Mono',monospace;font-size:0.72rem;letter-spacing:1px;color:var(--green);margin-top:8px;"></div>
      </form>
    </div>
  </div>
</section>

<?php get_footer(); ?>
