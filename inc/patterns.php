<?php
/**
 * Custom Block Patterns — pre-built layouts for the block editor that
 * mirror the homepage section template parts.
 *
 * Each section in template-parts/section-*.php has a corresponding pattern
 * here so editors can compose their own pages by inserting the same
 * sections into any post or page rather than relying on the hardcoded
 * homepage layout.
 *
 * The patterns are emitted as raw HTML blocks (<!-- wp:html -->) so the
 * theme's existing CSS hooks (.if-section, .hero-section, .services-grid,
 * etc.) take effect unchanged. Editors can swap text by switching the HTML
 * block to "Edit as HTML", or convert the whole pattern to native blocks
 * via the block toolbar's "Convert to blocks" option.
 *
 * The pattern preview in the inserter relies on assets/css/main.css being
 * loaded into the editor canvas — see the second add_editor_style() call
 * in inc/setup.php.
 *
 * @package Ifende
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wrap raw HTML as a Gutenberg HTML block so it round-trips through the
 * block parser unchanged.
 *
 * @param string $html Raw HTML.
 * @return string Block-comment-delimited HTML block.
 */
function ifende_pattern_html_block( $html ) {
	return "<!-- wp:html -->\n" . $html . "\n<!-- /wp:html -->";
}

/**
 * Thin wrapper around register_block_pattern() that fills in the default
 * category so each call site stays focused on title + content.
 *
 * @param string $slug Pattern slug (without the 'ifende/' prefix).
 * @param array  $args Pattern args; 'content' and 'title' required.
 */
function ifende_register_pattern( $slug, $args ) {
	$defaults = [
		'categories' => [ 'ifende' ],
	];
	register_block_pattern( 'ifende/' . $slug, array_merge( $defaults, $args ) );
}

/**
 * Register the 'ifende' pattern category and all section/utility patterns.
 *
 * Called on `init` (after the block editor is bootstrapped). Order in the
 * pattern inserter follows the order of the function calls below — kept
 * aligned with the homepage section order for predictability.
 */
function ifende_register_block_patterns() {
	register_block_pattern_category( 'ifende', [
		'label' => esc_html__( 'Ifende', 'ifende' ),
	] );

	// Section patterns — one per template-parts/section-*.php.
	ifende_register_hero_pattern();
	ifende_register_marquee_pattern();
	ifende_register_about_pattern();
	ifende_register_services_pattern();
	ifende_register_clients_pattern();
	ifende_register_testimonials_pattern();
	ifende_register_blog_pattern();
	ifende_register_faq_pattern();
	ifende_register_newsletter_pattern();
	ifende_register_contact_pattern();
	ifende_register_portfolio_pattern();

	// Utility patterns — generic building blocks not tied to a section.
	ifende_register_cta_pattern();
	ifende_register_pricing_pattern();
}
add_action( 'init', 'ifende_register_block_patterns' );


/* ============================================================================
 * Section patterns — mirror template-parts/section-*.php
 * ========================================================================= */

/**
 * Hero — mirrors template-parts/section-hero.php.
 */
function ifende_register_hero_pattern() {
	$html = <<<'HTML'
<section class="hero-section">
  <div class="hero-bg"></div>
  <div class="hero-grid-bg"></div>
  <div class="hero-content">
    <div class="hero-label">Available for Projects</div>
    <h1>Your Name<br><em>Here</em></h1>
    <div class="hero-title-line">
      <span>Project Manager</span>
      <span class="title-sep">&middot;</span>
      <span>Web Developer</span>
      <span class="title-sep">&middot;</span>
      <span>Consultant</span>
    </div>
    <p class="hero-bio">A short paragraph introducing yourself and the value you bring. Replace this with a compelling summary of who you are and what you do.</p>
    <div class="hero-actions">
      <a href="#contact" class="btn-primary">Let's Work Together <span>&rarr;</span></a>
      <a href="#services" class="btn-secondary">View Services</a>
    </div>
    <div class="hero-stats">
      <div><div class="stat-num">12+</div><div class="stat-label">Clients Served</div></div>
      <div><div class="stat-num">5+</div><div class="stat-label">Years Experience</div></div>
      <div><div class="stat-num">4</div><div class="stat-label">Core Services</div></div>
    </div>
  </div>
  <div class="hero-right">
    <div class="hero-photo-wrap">
      <div class="hero-photo-border"></div>
      <div class="hero-photo">
        <div class="hero-photo-placeholder">
          <div class="photo-initials">YN</div>
          <div class="photo-name">Your Name</div>
        </div>
      </div>
      <div class="hero-status">
        <div class="status-dot"></div>
        <div class="status-text">Available for Freelance</div>
      </div>
    </div>
  </div>
</section>
HTML;

	ifende_register_pattern( 'hero', [
		'title'       => esc_html__( 'Hero Section', 'ifende' ),
		'description' => esc_html__( 'Full-height landing hero with intro label, name, role list, bio, CTAs, stat counters, and a photo card with status pill.', 'ifende' ),
		'keywords'    => [ 'hero', 'banner', 'landing', 'intro', 'header' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Marquee — mirrors template-parts/section-marquee.php.
 *
 * Each item is duplicated once in the markup so the CSS animation can
 * loop seamlessly (translateX(-50%) reaches the start of the duplicate).
 */
function ifende_register_marquee_pattern() {
	$items_a = '';
	$items_b = '';
	$items   = [
		'Project Management', 'Web Development', 'Consulting', 'Branding',
		'Game Development', 'Remote Operations', 'WordPress', 'Digital Strategy',
	];
	foreach ( $items as $item ) {
		$line     = '<span class="marquee-item"><span class="marquee-dot"></span>' . esc_html( $item ) . '</span>';
		$items_a .= $line;
		$items_b .= $line;
	}

	$html = <<<HTML
<div class="marquee-section" aria-hidden="true">
  <div class="marquee-track">
    {$items_a}{$items_b}
  </div>
</div>
HTML;

	ifende_register_pattern( 'marquee', [
		'title'       => esc_html__( 'Marquee Strip', 'ifende' ),
		'description' => esc_html__( 'Scrolling text strip of skills/services. The CSS animation loops infinitely; duplicate the items inline to keep the loop seamless.', 'ifende' ),
		'keywords'    => [ 'marquee', 'ticker', 'scroller', 'strip' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * About — mirrors template-parts/section-about.php.
 */
function ifende_register_about_pattern() {
	$html = <<<'HTML'
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
HTML;

	ifende_register_pattern( 'about', [
		'title'       => esc_html__( 'About Section', 'ifende' ),
		'description' => esc_html__( 'Two-column about section with bio, profile facts, animated skill bars, and a call-to-action card.', 'ifende' ),
		'keywords'    => [ 'about', 'bio', 'profile', 'skills' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Services — mirrors template-parts/section-services.php.
 */
function ifende_register_services_pattern() {
	$html = <<<'HTML'
<section class="if-section" id="services">
  <div class="section-label">What I Do</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title">Services I<br><em>Offer</em></h2>
    <p class="section-sub">From web presence to business transformation &mdash; a holistic approach to every engagement. Replace this intro with your own positioning statement.</p>
  </div>
  <div class="services-grid">
    <div class="service-card">
      <div class="service-num">01</div>
      <span class="service-icon" aria-hidden="true">&#127760;</span>
      <h3>Web Development</h3>
      <p>Custom WordPress sites and modern web applications built with performance, accessibility, and conversion in mind.</p>
    </div>
    <div class="service-card">
      <div class="service-num">02</div>
      <span class="service-icon" aria-hidden="true">&#127919;</span>
      <h3>Consulting</h3>
      <p>Strategic technology and business consulting to help you make informed decisions and grow sustainably.</p>
    </div>
    <div class="service-card">
      <div class="service-num">03</div>
      <span class="service-icon" aria-hidden="true">&#10022;</span>
      <h3>Branding</h3>
      <p>Memorable brand identities that distinguish you from competitors and resonate with the right audience.</p>
    </div>
    <div class="service-card">
      <div class="service-num">04</div>
      <span class="service-icon" aria-hidden="true">&#127918;</span>
      <h3>Game Development</h3>
      <p>Mobile and indie game development from concept through to launch &mdash; immersive, polished, and shippable.</p>
    </div>
  </div>
</section>
HTML;

	ifende_register_pattern( 'services', [
		'title'       => esc_html__( 'Services Section', 'ifende' ),
		'description' => esc_html__( 'Two-by-two services grid with numbered cards, hover accent stripe, and large background numerals.', 'ifende' ),
		'keywords'    => [ 'services', 'offerings', 'features', 'grid' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Clients — mirrors template-parts/section-clients.php.
 */
function ifende_register_clients_pattern() {
	$html = <<<'HTML'
<section class="if-section dark" id="clients">
  <div class="clients-intro">
    <div>
      <div class="section-label">Trusted By</div>
      <h2 class="section-title">Clients &amp;<br><em>Partners</em></h2>
    </div>
    <p class="section-sub" style="align-self:flex-end;">A growing portfolio of businesses across industries who trust me to deliver exceptional digital and consultancy work.</p>
  </div>
  <div class="clients-grid">
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#128306;</span>
      <span class="client-name">Client One</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#128994;</span>
      <span class="client-name">Client Two</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#128998;</span>
      <span class="client-name">Client Three</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#9878;</span>
      <span class="client-name">Client Four</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#127959;</span>
      <span class="client-name">Client Five</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#128717;</span>
      <span class="client-name">Client Six</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#129309;</span>
      <span class="client-name">Client Seven</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
    <a href="#" target="_blank" rel="noopener" class="client-card">
      <span style="font-size:1.5rem;" aria-hidden="true">&#128203;</span>
      <span class="client-name">Client Eight</span>
      <span class="client-arrow" aria-hidden="true">&#8599;</span>
    </a>
  </div>
</section>
HTML;

	ifende_register_pattern( 'clients', [
		'title'       => esc_html__( 'Clients Section', 'ifende' ),
		'description' => esc_html__( 'Four-column logo grid with hover accents and outbound arrows. Replace placeholder cards with real client links.', 'ifende' ),
		'keywords'    => [ 'clients', 'partners', 'logos', 'trusted by' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Testimonials — mirrors template-parts/section-testimonials.php.
 */
function ifende_register_testimonials_pattern() {
	$html = <<<'HTML'
<section class="if-section" id="testimonials">
  <div class="section-label">Testimonials</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title">What People<br><em>Say</em></h2>
    <p class="section-sub">Hear from clients and partners who have experienced working with me firsthand.</p>
  </div>
  <div class="testimonials-grid">
    <blockquote class="testimonial-card">
      <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
      <p class="testimonial-text">Working with this team was a game-changer. They delivered our web platform on time and exceeded our expectations in every way.</p>
      <footer class="testimonial-author">
        <div class="testimonial-avatar" aria-hidden="true">C</div>
        <div>
          <cite class="testimonial-name">Client Name</cite>
          <span class="testimonial-role">CEO, Example Co.</span>
        </div>
      </footer>
    </blockquote>
    <blockquote class="testimonial-card">
      <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
      <p class="testimonial-text">Top-notch project management. Kept our team aligned and the project moving forward effortlessly through every milestone.</p>
      <footer class="testimonial-author">
        <div class="testimonial-avatar" aria-hidden="true">A</div>
        <div>
          <cite class="testimonial-name">Another Name</cite>
          <span class="testimonial-role">Director, Sample Studio</span>
        </div>
      </footer>
    </blockquote>
    <blockquote class="testimonial-card">
      <div class="testimonial-quote-mark" aria-hidden="true">&ldquo;</div>
      <p class="testimonial-text">From branding to web development, a rare combination of creativity and technical excellence. Highly recommended.</p>
      <footer class="testimonial-author">
        <div class="testimonial-avatar" aria-hidden="true">B</div>
        <div>
          <cite class="testimonial-name">Brand Lead</cite>
          <span class="testimonial-role">Founder, Demo Brand</span>
        </div>
      </footer>
    </blockquote>
  </div>
</section>
HTML;

	ifende_register_pattern( 'testimonials', [
		'title'       => esc_html__( 'Testimonials Section', 'ifende' ),
		'description' => esc_html__( 'Auto-fitting grid of testimonial quote cards with avatar initial, name, and role.', 'ifende' ),
		'keywords'    => [ 'testimonials', 'quotes', 'reviews', 'social proof' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Blog — mirrors template-parts/section-blog.php.
 *
 * Static placeholder cards. To pull live posts after inserting, replace
 * the .blog-grid contents with a core/latest-posts block configured for
 * featured image + date + excerpt.
 */
function ifende_register_blog_pattern() {
	$html = <<<'HTML'
<section class="if-section dark" id="blog">
  <div class="section-label">Latest Posts</div>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:64px;align-items:end;margin-bottom:64px;">
    <h2 class="section-title">From the<br><em>Blog</em></h2>
    <p class="section-sub">Thoughts, insights, and updates on web development, project management, and the digital landscape.</p>
  </div>
  <div class="blog-grid">
    <article class="blog-card">
      <div class="blog-card-content">
        <time class="blog-card-date">January 1, 2025</time>
        <h3 class="blog-card-title"><a href="#">A Sample Blog Post Title</a></h3>
        <p class="blog-card-excerpt">A short excerpt that previews the article and entices readers to click through to the full post.</p>
        <a href="#" class="blog-card-link">Read More &rarr;</a>
      </div>
    </article>
    <article class="blog-card">
      <div class="blog-card-content">
        <time class="blog-card-date">January 1, 2025</time>
        <h3 class="blog-card-title"><a href="#">Another Article Headline</a></h3>
        <p class="blog-card-excerpt">A short excerpt that previews the article and entices readers to click through to the full post.</p>
        <a href="#" class="blog-card-link">Read More &rarr;</a>
      </div>
    </article>
    <article class="blog-card">
      <div class="blog-card-content">
        <time class="blog-card-date">January 1, 2025</time>
        <h3 class="blog-card-title"><a href="#">A Third Recent Post</a></h3>
        <p class="blog-card-excerpt">A short excerpt that previews the article and entices readers to click through to the full post.</p>
        <a href="#" class="blog-card-link">Read More &rarr;</a>
      </div>
    </article>
  </div>
  <div style="text-align:center;margin-top:48px;">
    <a href="#" class="btn-secondary">View All Posts &rarr;</a>
  </div>
</section>
HTML;

	ifende_register_pattern( 'blog', [
		'title'       => esc_html__( 'Blog / Latest Posts Section', 'ifende' ),
		'description' => esc_html__( 'Three-card blog teaser grid. Cards are static placeholders &mdash; swap for a Latest Posts block to render live content.', 'ifende' ),
		'keywords'    => [ 'blog', 'posts', 'articles', 'news' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * FAQ — mirrors template-parts/section-faq.php (without the JSON-LD
 * schema script; that's only emitted by the live template).
 */
function ifende_register_faq_pattern() {
	$html = <<<'HTML'
<section class="if-section dark" id="faq">
  <div class="section-label">FAQ</div>
  <h2 class="section-title">Frequently Asked<br><em>Questions</em></h2>
  <p class="section-sub">Quick answers to common questions about working together.</p>
  <div class="faq-list">
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        What services do you offer?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>I specialise in web development, project management, consulting, branding, and game development. Each engagement is tailored to your specific needs.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        How long does a typical project take?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>Project timelines vary based on scope. A standard website takes 2&ndash;4 weeks; larger builds run 6&ndash;8 weeks. You'll get a detailed timeline at the consultation.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        Do you work with international clients?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>Yes &mdash; I work with clients globally. All communication and project management happens remotely using modern collaboration tools.</p>
      </div>
    </div>
    <div class="faq-item">
      <button class="faq-question" aria-expanded="false" type="button">
        What is your pricing structure?
        <span class="faq-icon" aria-hidden="true"></span>
      </button>
      <div class="faq-answer">
        <p>Pricing depends on scope, complexity, and timeline. I offer fixed-price projects and hourly consulting. Get in touch for an accurate quote.</p>
      </div>
    </div>
  </div>
</section>
HTML;

	ifende_register_pattern( 'faq', [
		'title'       => esc_html__( 'FAQ Section', 'ifende' ),
		'description' => esc_html__( 'Accordion-style frequently-asked-questions list. The live homepage section adds Schema.org FAQPage markup automatically.', 'ifende' ),
		'keywords'    => [ 'faq', 'questions', 'help', 'accordion' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Newsletter — mirrors template-parts/section-newsletter.php.
 *
 * The form action is left as '#' so editors must wire it to their own
 * Mailchimp/ConvertKit endpoint before publishing.
 */
function ifende_register_newsletter_pattern() {
	$html = <<<'HTML'
<section class="if-section newsletter-section" id="newsletter">
  <div class="newsletter-wrap">
    <div class="newsletter-content">
      <div class="section-label">Newsletter</div>
      <h2 class="section-title">Stay in the <em>Loop</em></h2>
      <p class="section-sub">Get occasional updates on new projects, insights, and opportunities. No spam, unsubscribe anytime.</p>
    </div>
    <form class="newsletter-form" action="#" method="POST" target="_blank" rel="noopener">
      <div class="newsletter-input-wrap">
        <label for="newsletter-email-pattern" class="screen-reader-text">Email address</label>
        <input type="email" id="newsletter-email-pattern" name="EMAIL" placeholder="Enter your email" required autocomplete="email">
        <button type="submit" class="newsletter-btn">Subscribe &rarr;</button>
      </div>
      <p class="newsletter-disclaimer">No spam. Unsubscribe anytime.</p>
    </form>
  </div>
</section>
HTML;

	ifende_register_pattern( 'newsletter', [
		'title'       => esc_html__( 'Newsletter Signup', 'ifende' ),
		'description' => esc_html__( 'Newsletter signup section with heading, description, and inline email form. Set the form action to your Mailchimp or ConvertKit URL before publishing.', 'ifende' ),
		'keywords'    => [ 'newsletter', 'email', 'signup', 'subscribe' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Contact — mirrors template-parts/section-contact.php.
 *
 * The form has unique IDs (suffixed with "-pattern") so it can coexist
 * with the live section's form on the same page if both are inserted.
 * Theme JS hooks the contact form by `#contactForm` &mdash; only one such ID
 * may exist per page; rename or remove the duplicate as needed.
 */
function ifende_register_contact_pattern() {
	$html = <<<'HTML'
<section class="if-section" id="contact">
  <div class="section-label">Get In Touch</div>
  <div class="contact-grid">
    <div>
      <h2 class="section-title">Let's Build<br>Something <em>Great</em></h2>
      <p class="section-sub" style="margin-top:24px;margin-bottom:48px;">Have a project in mind? Looking for a consultant, developer, or creative partner? I'd love to hear from you.</p>
      <div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">&#128205;</div><div><div class="contact-label">Location</div><div class="contact-val">Global &mdash; Based Anywhere</div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">&#128188;</div><div><div class="contact-label">Availability</div><div class="contact-val" style="color:var(--green);">Open for Freelance &amp; Consulting</div></div></div>
        <div class="contact-item"><div class="contact-icon" aria-hidden="true">&#127760;</div><div><div class="contact-label">Website</div><div class="contact-val"><a href="#" style="color:var(--white);text-decoration:none;">your-website.com</a></div></div></div>
      </div>
      <div style="margin-top:40px;">
        <div class="contact-label" style="margin-bottom:14px;">Follow Me</div>
        <div class="socials">
          <a href="#" target="_blank" rel="noopener" class="social-link" aria-label="Twitter / X">&#x1D54F;</a>
          <a href="#" target="_blank" rel="noopener" class="social-link" aria-label="Instagram">&#128247;</a>
          <a href="#" target="_blank" rel="noopener" class="social-link" aria-label="Website">&#127760;</a>
        </div>
      </div>
    </div>
    <div>
      <form class="contact-form" aria-label="Contact form">
        <div class="form-row">
          <div class="form-group"><label for="contact-fname-pattern">First Name</label><input type="text" id="contact-fname-pattern" name="fname" placeholder="Jane" autocomplete="given-name" required></div>
          <div class="form-group"><label for="contact-lname-pattern">Last Name</label><input type="text" id="contact-lname-pattern" name="lname" placeholder="Doe" autocomplete="family-name" required></div>
        </div>
        <div class="form-group"><label for="contact-email-pattern">Email Address</label><input type="email" id="contact-email-pattern" name="email" placeholder="you@example.com" autocomplete="email" required></div>
        <div class="form-group"><label for="contact-subject-pattern">Subject</label><input type="text" id="contact-subject-pattern" name="subject" placeholder="Web development project..."></div>
        <div class="form-group"><label for="contact-message-pattern">Your Message</label><textarea id="contact-message-pattern" name="message" placeholder="Tell me about your project..." required></textarea></div>
        <button type="submit" class="btn-submit">Send Message &rarr;</button>
      </form>
    </div>
  </div>
</section>
HTML;

	ifende_register_pattern( 'contact', [
		'title'       => esc_html__( 'Contact Section', 'ifende' ),
		'description' => esc_html__( 'Two-column contact section with location/availability/website, social links, and a name/email/subject/message form.', 'ifende' ),
		'keywords'    => [ 'contact', 'form', 'get in touch', 'email' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}

/**
 * Portfolio — mirrors template-parts/section-portfolio.php.
 *
 * Static placeholder project cards. To pull live ifende_project CPT
 * entries after inserting, replace the .portfolio-grid contents with a
 * core/query block configured for postType "ifende_project".
 */
function ifende_register_portfolio_pattern() {
	$html = <<<'HTML'
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
HTML;

	ifende_register_pattern( 'portfolio', [
		'title'       => esc_html__( 'Portfolio Section', 'ifende' ),
		'description' => esc_html__( 'Three-card portfolio grid with client, year, description, and tech tags. Cards are placeholders &mdash; swap for a Query block targeting the ifende_project CPT to render live entries.', 'ifende' ),
		'keywords'    => [ 'portfolio', 'projects', 'work', 'case studies' ],
		'content'     => ifende_pattern_html_block( $html ),
	] );
}


/* ============================================================================
 * Utility patterns — generic building blocks not tied to a homepage section
 * ========================================================================= */

/**
 * Call to Action — full-width CTA banner with heading, description, and
 * paired buttons. Useful at the end of any landing page.
 */
function ifende_register_cta_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"5vw","right":"5vw"}},"color":{"background":"rgba(33,161,78,0.05)"},"border":{"top":{"color":"rgba(245,242,236,0.12)","width":"1px"},"bottom":{"color":"rgba(245,242,236,0.12)","width":"1px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:rgba(33,161,78,0.05);border-top-color:rgba(245,242,236,0.12);border-top-width:1px;border-bottom-color:rgba(245,242,236,0.12);border-bottom-width:1px;padding-top:80px;padding-bottom:80px;padding-left:5vw;padding-right:5vw">

<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">

<!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%">
<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(1.8rem,3vw,2.8rem)","fontWeight":"300"}}} -->
<h2 class="wp-block-heading" style="font-size:clamp(1.8rem,3vw,2.8rem);font-weight:300">Ready to Start Your <em>Next Project</em>?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.95rem","lineHeight":"1.8"}}} -->
<p style="color:#8a8a8a;font-size:0.95rem;line-height:1.8">Let\'s discuss how we can work together to bring your ideas to life. Free consultation available.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%">
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons">
<!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">Get In Touch</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline"} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link wp-element-button">View Work</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	ifende_register_pattern( 'cta', [
		'title'       => esc_html__( 'Call to Action', 'ifende' ),
		'description' => esc_html__( 'Full-width CTA banner with headline, supporting text, and primary + outline buttons. Drop in at the end of a landing page.', 'ifende' ),
		'keywords'    => [ 'cta', 'call to action', 'banner' ],
		'content'     => $content,
	] );
}

/**
 * Pricing — three-tier pricing table with a highlighted middle plan.
 */
function ifende_register_pricing_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"100px","left":"5vw","right":"5vw"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:100px;padding-bottom:100px;padding-left:5vw;padding-right:5vw">

<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"3px","textTransform":"uppercase"}}} -->
<p style="font-size:0.72rem;letter-spacing:3px;text-transform:uppercase">Pricing</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(2rem,4vw,3.5rem)","fontWeight":"300"}}} -->
<h2 class="wp-block-heading" style="font-size:clamp(2rem,4vw,3.5rem);font-weight:300">Simple <em>Transparent</em> Pricing</h2>
<!-- /wp:heading -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"24px"}}}} -->
<div class="wp-block-columns">

<!-- wp:column {"style":{"border":{"width":"1px","color":"rgba(245,242,236,0.12)","radius":"4px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:rgba(245,242,236,0.12);border-width:1px;border-radius:4px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}}} -->
<p style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase">Starter</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"300"}}} -->
<h3 class="wp-block-heading" style="font-size:2.5rem;font-weight:300">$499</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.82rem"}}} -->
<p style="font-size:0.82rem">Perfect for small businesses and personal sites.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.85rem"},"spacing":{"blockGap":"10px"}}} -->
<ul style="font-size:0.85rem"><!-- wp:list-item --><li>5-page website</li><!-- /wp:list-item --><!-- wp:list-item --><li>Mobile responsive</li><!-- /wp:list-item --><!-- wp:list-item --><li>Basic SEO</li><!-- /wp:list-item --><!-- wp:list-item --><li>Contact form</li><!-- /wp:list-item --><!-- wp:list-item --><li>1 revision round</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100,"className":"is-style-outline"} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link wp-element-button">Choose Plan</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"width":"2px","color":"#21A14E","radius":"4px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:#21A14E;border-width:2px;border-radius:4px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}}} -->
<p style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase">Professional</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"300"}}} -->
<h3 class="wp-block-heading" style="font-size:2.5rem;font-weight:300">$1,299</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.82rem"}}} -->
<p style="font-size:0.82rem">For growing businesses that need more.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.85rem"},"spacing":{"blockGap":"10px"}}} -->
<ul style="font-size:0.85rem"><!-- wp:list-item --><li>10-page website</li><!-- /wp:list-item --><!-- wp:list-item --><li>Custom design</li><!-- /wp:list-item --><!-- wp:list-item --><li>Advanced SEO</li><!-- /wp:list-item --><!-- wp:list-item --><li>E-commerce ready</li><!-- /wp:list-item --><!-- wp:list-item --><li>3 revision rounds</li><!-- /wp:list-item --><!-- wp:list-item --><li>30 days support</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100"><a class="wp-block-button__link wp-element-button">Choose Plan</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"width":"1px","color":"rgba(245,242,236,0.12)","radius":"4px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:rgba(245,242,236,0.12);border-width:1px;border-radius:4px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}}} -->
<p style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase">Enterprise</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"300"}}} -->
<h3 class="wp-block-heading" style="font-size:2.5rem;font-weight:300">Custom</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.82rem"}}} -->
<p style="font-size:0.82rem">Tailored solutions for complex requirements.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.85rem"},"spacing":{"blockGap":"10px"}}} -->
<ul style="font-size:0.85rem"><!-- wp:list-item --><li>Unlimited pages</li><!-- /wp:list-item --><!-- wp:list-item --><li>Full custom build</li><!-- /wp:list-item --><!-- wp:list-item --><li>Priority support</li><!-- /wp:list-item --><!-- wp:list-item --><li>Performance audit</li><!-- /wp:list-item --><!-- wp:list-item --><li>Ongoing maintenance</li><!-- /wp:list-item --><!-- wp:list-item --><li>Dedicated manager</li><!-- /wp:list-item --></ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100,"className":"is-style-outline"} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline"><a class="wp-block-button__link wp-element-button">Contact Us</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	ifende_register_pattern( 'pricing', [
		'title'       => esc_html__( 'Pricing Table', 'ifende' ),
		'description' => esc_html__( 'Three-tier pricing table with a highlighted middle plan, feature lists, and call-to-action buttons.', 'ifende' ),
		'keywords'    => [ 'pricing', 'plans', 'packages' ],
		'content'     => $content,
	] );
}
