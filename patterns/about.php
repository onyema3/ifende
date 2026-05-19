<?php
/**
 * Title: About Section
 * Slug: ifende/about
 * Categories: ifende
 * Description: Two-column about section with bio, profile facts, animated skill bars, and a call-to-action card.
 * Keywords: about, bio, profile, skills
 * Inserter: yes
 *
 * Mirrors template-parts/section-about.php.
 */
?>
<!-- wp:html -->
<section class="if-section dark" id="about">
  <div class="section-label">About Me</div>
  <div class="about-grid">
    <div>
      <h2 class="section-title">The Person<br>Behind the <em>Work</em></h2>
      <p class="section-sub" style="margin-top:24px;">Hello! I'm a multi-disciplinary professional with rich experience in project management, web development, consulting, and branding. Replace this with your own bio.</p>
      <div style="margin-top:40px;">
        <div class="about-item"><span class="about-key">Residence</span><span class="about-val">Global &mdash; Based Anywhere</span></div>
        <div class="about-item"><span class="about-key">Freelance</span><span class="about-val" style="color:var(--green);">&#10003; Currently Available</span></div>
        <div class="about-item"><span class="about-key">Specialties</span><span class="about-val">Strategy &middot; Design &middot; Development &middot; Operations</span></div>
        <div class="about-item"><span class="about-key">Twitter/X</span><span class="about-val"><a href="#" style="color:var(--green);text-decoration:none;">@yourhandle</a></span></div>
      </div>
    </div>
    <div>
      <div class="section-label" style="margin-bottom:20px;">Core Skills</div>
      <div class="skills-progress-list">
        <div class="skill-progress-item">
          <div class="skill-progress-header"><span class="skill-progress-name">WordPress</span><span class="skill-progress-pct">95%</span></div>
          <div class="skill-progress-bar"><div class="skill-progress-fill" style="--progress:95%"></div></div>
        </div>
        <div class="skill-progress-item">
          <div class="skill-progress-header"><span class="skill-progress-name">Project Management</span><span class="skill-progress-pct">90%</span></div>
          <div class="skill-progress-bar"><div class="skill-progress-fill" style="--progress:90%"></div></div>
        </div>
        <div class="skill-progress-item">
          <div class="skill-progress-header"><span class="skill-progress-name">UI/UX Design</span><span class="skill-progress-pct">85%</span></div>
          <div class="skill-progress-bar"><div class="skill-progress-fill" style="--progress:85%"></div></div>
        </div>
        <div class="skill-progress-item">
          <div class="skill-progress-header"><span class="skill-progress-name">Consulting</span><span class="skill-progress-pct">88%</span></div>
          <div class="skill-progress-bar"><div class="skill-progress-fill" style="--progress:88%"></div></div>
        </div>
      </div>
      <div style="margin-top:40px;padding:32px;border:1px solid var(--border);border-radius:2px;background:rgba(33,161,78,0.04);">
        <div class="section-label" style="margin-bottom:16px;">Current Status</div>
        <p style="font-family:'Cormorant Garamond',serif;font-size:1.4rem;font-weight:300;color:var(--white);line-height:1.5;">Open to new projects, collaborations, and consulting engagements.</p>
        <a href="#contact" class="btn-primary" style="margin-top:24px;display:inline-flex;">Start a Conversation &rarr;</a>
      </div>
    </div>
  </div>
</section>
<!-- /wp:html -->
