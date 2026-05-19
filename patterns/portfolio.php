<?php
/**
 * Title: Portfolio Section
 * Slug: ifende/portfolio
 * Categories: ifende
 * Description: Three-card portfolio grid with client, year, description, and tech tags. Cards are placeholders &mdash; swap for a Query block targeting the ifende_project CPT to render live entries.
 * Keywords: portfolio, projects, work, case studies
 * Inserter: yes
 *
 * Mirrors template-parts/section-portfolio.php. Static placeholder project
 * cards. To pull live ifende_project CPT entries after inserting, replace
 * the .portfolio-grid contents with a core/query block configured for
 * postType "ifende_project".
 */
?>
<!-- wp:html -->
<section class="if-section" id="portfolio">
  <div class="section-label">Portfolio</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:48px;">
    <h2 class="section-title">Selected<br><em>Work</em></h2>
    <p class="section-sub">A selection of projects that showcase my approach to solving problems through design and code.</p>
  </div>
  <div class="portfolio-grid">
    <article class="portfolio-card" data-categories="web">
      <div class="portfolio-card-content">
        <div class="portfolio-card-meta"><span>Sample Client</span><span>2024</span></div>
        <h3 class="portfolio-card-title">Project Title One</h3>
        <p class="portfolio-card-excerpt">A short description of what the project did, the problem solved, and the impact delivered.</p>
        <div class="portfolio-card-tech">
          <span class="tech-tag">WordPress</span>
          <span class="tech-tag">React</span>
          <span class="tech-tag">REST API</span>
        </div>
      </div>
    </article>
    <article class="portfolio-card" data-categories="branding">
      <div class="portfolio-card-content">
        <div class="portfolio-card-meta"><span>Another Brand</span><span>2024</span></div>
        <h3 class="portfolio-card-title">Project Title Two</h3>
        <p class="portfolio-card-excerpt">A short description of what the project did, the problem solved, and the impact delivered.</p>
        <div class="portfolio-card-tech">
          <span class="tech-tag">Branding</span>
          <span class="tech-tag">Identity</span>
        </div>
      </div>
    </article>
    <article class="portfolio-card" data-categories="game">
      <div class="portfolio-card-content">
        <div class="portfolio-card-meta"><span>Indie Studio</span><span>2023</span></div>
        <h3 class="portfolio-card-title">Project Title Three</h3>
        <p class="portfolio-card-excerpt">A short description of what the project did, the problem solved, and the impact delivered.</p>
        <div class="portfolio-card-tech">
          <span class="tech-tag">Unity</span>
          <span class="tech-tag">C#</span>
          <span class="tech-tag">iOS</span>
        </div>
      </div>
    </article>
  </div>
</section>
<!-- /wp:html -->
