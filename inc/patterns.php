<?php
/**
 * Custom Block Patterns — Pre-built layouts for the block editor.
 *
 * @package Ifende
 * @since   1.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register block pattern category and patterns.
 */
function ifende_register_block_patterns() {
	// Register the pattern category.
	register_block_pattern_category( 'ifende', [
		'label' => esc_html__( 'Ifende', 'ifende' ),
	] );

	// Register individual patterns.
	ifende_register_hero_pattern();
	ifende_register_services_pattern();
	ifende_register_testimonial_pattern();
	ifende_register_cta_pattern();
	ifende_register_about_pattern();
	ifende_register_pricing_pattern();
}
add_action( 'init', 'ifende_register_block_patterns' );


/**
 * Hero Section Pattern.
 */
function ifende_register_hero_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"120px","bottom":"80px","left":"5vw","right":"5vw"}}},"backgroundColor":"black","textColor":"white","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-black-background-color has-white-color has-text-color has-background" style="padding-top:120px;padding-bottom:80px;padding-left:5vw;padding-right:5vw">

<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">

<!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%">

<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"3px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:3px;text-transform:uppercase">Available for Projects</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"clamp(3rem,6vw,5.5rem)","fontWeight":"300","lineHeight":"1.05"}}} -->
<h1 style="font-size:clamp(3rem,6vw,5.5rem);font-weight:300;line-height:1.05">Creative <em>Developer</em> &amp; Designer</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"fontSize":"1rem","lineHeight":"1.8"},"color":{"text":"#8a8a8a"}}} -->
<p style="font-size:1rem;line-height:1.8;color:#8a8a8a">Crafting beautiful digital experiences with modern technologies. Let us bring your vision to life.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase","fontWeight":"700"}}} -->
<div class="wp-block-button" style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase;font-weight:700"><a class="wp-block-button__link wp-element-button">View Projects</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}}} -->
<div class="wp-block-button is-style-outline" style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase"><a class="wp-block-button__link wp-element-button">Get In Touch</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->

</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%">
<!-- wp:image {"sizeSlug":"large","style":{"border":{"radius":"4px"}}} -->
<figure class="wp-block-image size-large" style="border-radius:4px"><img src="" alt="Hero image"/></figure>
<!-- /wp:image -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	register_block_pattern( 'ifende/hero', [
		'title'       => esc_html__( 'Hero Section', 'ifende' ),
		'description' => esc_html__( 'A full-width hero with headline, tagline, CTA buttons, and image.', 'ifende' ),
		'categories'  => [ 'ifende' ],
		'keywords'    => [ 'hero', 'banner', 'header', 'landing' ],
		'content'     => $content,
	] );
}


/**
 * Services Grid Pattern.
 */
function ifende_register_services_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"100px","left":"5vw","right":"5vw"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:100px;padding-bottom:100px;padding-left:5vw;padding-right:5vw">

<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"3px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:3px;text-transform:uppercase">What I Do</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(2rem,4vw,3.5rem)","fontWeight":"300"}}} -->
<h2 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:300">Services &amp; <em>Expertise</em></h2>
<!-- /wp:heading -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"1px"}}}} -->
<div class="wp-block-columns">

<!-- wp:column {"style":{"border":{"left":{"color":"var(--green)","width":"3px"}},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-left-color:var(--green);border-left-width:3px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"400"}}} -->
<h3 style="font-size:1.4rem;font-weight:400">Web Development</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.88rem","lineHeight":"1.8"}}} -->
<p style="color:#8a8a8a;font-size:0.88rem;line-height:1.8">Custom WordPress sites and web applications built with modern standards and performance in mind.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"left":{"color":"var(--green)","width":"3px"}},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-left-color:var(--green);border-left-width:3px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"400"}}} -->
<h3 style="font-size:1.4rem;font-weight:400">Consulting</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.88rem","lineHeight":"1.8"}}} -->
<p style="color:#8a8a8a;font-size:0.88rem;line-height:1.8">Strategic business and technology consulting to help you make informed decisions and grow.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"left":{"color":"var(--green)","width":"3px"}},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-left-color:var(--green);border-left-width:3px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.4rem","fontWeight":"400"}}} -->
<h3 style="font-size:1.4rem;font-weight:400">Branding</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.88rem","lineHeight":"1.8"}}} -->
<p style="color:#8a8a8a;font-size:0.88rem;line-height:1.8">Memorable brand identities that distinguish you from competitors and resonate with your audience.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	register_block_pattern( 'ifende/services', [
		'title'       => esc_html__( 'Services Grid', 'ifende' ),
		'description' => esc_html__( 'Three-column services grid with accent borders.', 'ifende' ),
		'categories'  => [ 'ifende' ],
		'keywords'    => [ 'services', 'features', 'grid', 'columns' ],
		'content'     => $content,
	] );
}


/**
 * Testimonial Pattern.
 */
function ifende_register_testimonial_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"100px","left":"5vw","right":"5vw"}},"color":{"background":"rgba(245,242,236,0.02)"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:rgba(245,242,236,0.02);padding-top:100px;padding-bottom:100px;padding-left:5vw;padding-right:5vw">

<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"3px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:3px;text-transform:uppercase">Testimonials</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(2rem,4vw,3.5rem)","fontWeight":"300"}}} -->
<h2 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:300">What Clients <em>Say</em></h2>
<!-- /wp:heading -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"24px"}}}} -->
<div class="wp-block-columns">

<!-- wp:column {"style":{"border":{"width":"1px","color":"rgba(245,242,236,0.12)","radius":"4px"},"spacing":{"padding":{"top":"36px","bottom":"36px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:rgba(245,242,236,0.12);border-width:1px;border-radius:4px;padding-top:36px;padding-bottom:36px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.92rem","lineHeight":"1.8","fontStyle":"italic"},"color":{"text":"rgba(245,242,236,0.7)"}}} -->
<p style="font-size:0.92rem;line-height:1.8;font-style:italic;color:rgba(245,242,236,0.7)">"Working with this team was a game-changer. They delivered our platform on time and exceeded expectations."</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.82rem","fontWeight":"600"}}} -->
<p style="font-size:0.82rem;font-weight:600">John Doe</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.65rem","letterSpacing":"1.5px","textTransform":"uppercase"},"color":{"text":"#8a8a8a"}}} -->
<p style="font-size:0.65rem;letter-spacing:1.5px;text-transform:uppercase;color:#8a8a8a">CEO, Example Co</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"width":"1px","color":"rgba(245,242,236,0.12)","radius":"4px"},"spacing":{"padding":{"top":"36px","bottom":"36px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:rgba(245,242,236,0.12);border-width:1px;border-radius:4px;padding-top:36px;padding-bottom:36px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.92rem","lineHeight":"1.8","fontStyle":"italic"},"color":{"text":"rgba(245,242,236,0.7)"}}} -->
<p style="font-size:0.92rem;line-height:1.8;font-style:italic;color:rgba(245,242,236,0.7)">"Their project management skills are exceptional. They kept our team aligned and the project on track throughout."</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.82rem","fontWeight":"600"}}} -->
<p style="font-size:0.82rem;font-weight:600">Jane Smith</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.65rem","letterSpacing":"1.5px","textTransform":"uppercase"},"color":{"text":"#8a8a8a"}}} -->
<p style="font-size:0.65rem;letter-spacing:1.5px;text-transform:uppercase;color:#8a8a8a">Director, Agency XYZ</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	register_block_pattern( 'ifende/testimonial', [
		'title'       => esc_html__( 'Testimonial Cards', 'ifende' ),
		'description' => esc_html__( 'Two-column testimonial cards with quotes and attribution.', 'ifende' ),
		'categories'  => [ 'ifende' ],
		'keywords'    => [ 'testimonial', 'review', 'quote', 'client' ],
		'content'     => $content,
	] );
}


/**
 * Call to Action Pattern.
 */
function ifende_register_cta_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"5vw","right":"5vw"}},"color":{"background":"rgba(33,161,78,0.05)"},"border":{"top":{"color":"rgba(245,242,236,0.12)","width":"1px"},"bottom":{"color":"rgba(245,242,236,0.12)","width":"1px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:rgba(33,161,78,0.05);border-top-color:rgba(245,242,236,0.12);border-top-width:1px;border-bottom-color:rgba(245,242,236,0.12);border-bottom-width:1px;padding-top:80px;padding-bottom:80px;padding-left:5vw;padding-right:5vw">

<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">

<!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%">
<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(1.8rem,3vw,2.8rem)","fontWeight":"300"}}} -->
<h2 style="font-size:clamp(1.8rem,3vw,2.8rem);font-weight:300">Ready to Start Your <em>Next Project</em>?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.95rem","lineHeight":"1.8"}}} -->
<p style="color:#8a8a8a;font-size:0.95rem;line-height:1.8">Let us discuss how we can work together to bring your ideas to life. Free consultation available.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%">
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons">
<!-- wp:button {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase","fontWeight":"700"}}} -->
<div class="wp-block-button" style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase;font-weight:700"><a class="wp-block-button__link wp-element-button">Get In Touch</a></div>
<!-- /wp:button -->
<!-- wp:button {"className":"is-style-outline","style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}}} -->
<div class="wp-block-button is-style-outline" style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase"><a class="wp-block-button__link wp-element-button">View Work</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	register_block_pattern( 'ifende/cta', [
		'title'       => esc_html__( 'Call to Action', 'ifende' ),
		'description' => esc_html__( 'Full-width CTA section with heading, description, and buttons.', 'ifende' ),
		'categories'  => [ 'ifende' ],
		'keywords'    => [ 'cta', 'call to action', 'banner', 'contact' ],
		'content'     => $content,
	] );
}


/**
 * About Section Pattern.
 */
function ifende_register_about_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"100px","left":"5vw","right":"5vw"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:100px;padding-bottom:100px;padding-left:5vw;padding-right:5vw">

<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"3px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:3px;text-transform:uppercase">About Me</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(2rem,4vw,3.5rem)","fontWeight":"300"}}} -->
<h2 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:300">A Little <em>Background</em></h2>
<!-- /wp:heading -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"64px"}}}} -->
<div class="wp-block-columns">

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem","lineHeight":"1.8"},"color":{"text":"rgba(245,242,236,0.75)"}}} -->
<p style="font-size:0.95rem;line-height:1.8;color:rgba(245,242,236,0.75)">I am a multi-disciplinary professional passionate about building digital products that solve real problems. With experience spanning web development, project management, and consulting, I bring a holistic approach to every engagement.</p>
<!-- /wp:paragraph -->
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.95rem","lineHeight":"1.8"},"color":{"text":"rgba(245,242,236,0.75)"}}} -->
<p style="font-size:0.95rem;line-height:1.8;color:rgba(245,242,236,0.75)">My goal is simple: understand your vision, plan meticulously, and execute with precision. Whether you need a website, strategic guidance, or a complete digital transformation — I am here to help.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column">
<!-- wp:heading {"level":4,"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<h4 style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase">Skills</h4>
<!-- /wp:heading -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.88rem"},"color":{"text":"rgba(245,242,236,0.7)"},"spacing":{"blockGap":"8px"}}} -->
<ul style="font-size:0.88rem;color:rgba(245,242,236,0.7)"><li>WordPress Development</li><li>Project Management</li><li>UI/UX Design</li><li>Business Consulting</li><li>Brand Strategy</li><li>Team Leadership</li></ul>
<!-- /wp:list -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	register_block_pattern( 'ifende/about', [
		'title'       => esc_html__( 'About Section', 'ifende' ),
		'description' => esc_html__( 'Two-column about section with bio text and skills list.', 'ifende' ),
		'categories'  => [ 'ifende' ],
		'keywords'    => [ 'about', 'bio', 'skills', 'profile' ],
		'content'     => $content,
	] );
}


/**
 * Pricing Table Pattern.
 */
function ifende_register_pricing_pattern() {
	$content = '<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"100px","bottom":"100px","left":"5vw","right":"5vw"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:100px;padding-bottom:100px;padding-left:5vw;padding-right:5vw">

<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"3px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:3px;text-transform:uppercase">Pricing</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(2rem,4vw,3.5rem)","fontWeight":"300"}}} -->
<h2 style="font-size:clamp(2rem,4vw,3.5rem);font-weight:300">Simple <em>Transparent</em> Pricing</h2>
<!-- /wp:heading -->

<!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"24px"}}}} -->
<div class="wp-block-columns">

<!-- wp:column {"style":{"border":{"width":"1px","color":"rgba(245,242,236,0.12)","radius":"4px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:rgba(245,242,236,0.12);border-width:1px;border-radius:4px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase">Starter</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"300"}}} -->
<h3 style="font-size:2.5rem;font-weight:300">$499</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.82rem"}}} -->
<p style="color:#8a8a8a;font-size:0.82rem">Perfect for small businesses and personal sites.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.85rem"},"color":{"text":"rgba(245,242,236,0.7)"},"spacing":{"blockGap":"10px"}}} -->
<ul style="font-size:0.85rem;color:rgba(245,242,236,0.7)"><li>5-page website</li><li>Mobile responsive</li><li>Basic SEO</li><li>Contact form</li><li>1 revision round</li></ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100,"className":"is-style-outline","style":{"typography":{"fontSize":"0.68rem","letterSpacing":"2px","textTransform":"uppercase"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline" style="font-size:0.68rem;letter-spacing:2px;text-transform:uppercase"><a class="wp-block-button__link wp-element-button">Choose Plan</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"width":"2px","color":"var(--green)","radius":"4px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:var(--green);border-width:2px;border-radius:4px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase">Professional</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"300"}}} -->
<h3 style="font-size:2.5rem;font-weight:300">$1,299</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.82rem"}}} -->
<p style="color:#8a8a8a;font-size:0.82rem">For growing businesses that need more.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.85rem"},"color":{"text":"rgba(245,242,236,0.7)"},"spacing":{"blockGap":"10px"}}} -->
<ul style="font-size:0.85rem;color:rgba(245,242,236,0.7)"><li>10-page website</li><li>Custom design</li><li>Advanced SEO</li><li>E-commerce ready</li><li>3 revision rounds</li><li>30 days support</li></ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100,"style":{"typography":{"fontSize":"0.68rem","letterSpacing":"2px","textTransform":"uppercase","fontWeight":"700"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100" style="font-size:0.68rem;letter-spacing:2px;text-transform:uppercase;font-weight:700"><a class="wp-block-button__link wp-element-button">Choose Plan</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

<!-- wp:column {"style":{"border":{"width":"1px","color":"rgba(245,242,236,0.12)","radius":"4px"},"spacing":{"padding":{"top":"40px","bottom":"40px","left":"32px","right":"32px"}}}} -->
<div class="wp-block-column" style="border-color:rgba(245,242,236,0.12);border-width:1px;border-radius:4px;padding-top:40px;padding-bottom:40px;padding-left:32px;padding-right:32px">
<!-- wp:paragraph {"style":{"typography":{"fontSize":"0.72rem","letterSpacing":"2px","textTransform":"uppercase"}},"textColor":"vivid-green-cyan"} -->
<p style="font-size:0.72rem;letter-spacing:2px;text-transform:uppercase">Enterprise</p>
<!-- /wp:paragraph -->
<!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"2.5rem","fontWeight":"300"}}} -->
<h3 style="font-size:2.5rem;font-weight:300">Custom</h3>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.82rem"}}} -->
<p style="color:#8a8a8a;font-size:0.82rem">Tailored solutions for complex requirements.</p>
<!-- /wp:paragraph -->
<!-- wp:list {"style":{"typography":{"fontSize":"0.85rem"},"color":{"text":"rgba(245,242,236,0.7)"},"spacing":{"blockGap":"10px"}}} -->
<ul style="font-size:0.85rem;color:rgba(245,242,236,0.7)"><li>Unlimited pages</li><li>Full custom build</li><li>Priority support</li><li>Performance audit</li><li>Ongoing maintenance</li><li>Dedicated manager</li></ul>
<!-- /wp:list -->
<!-- wp:buttons -->
<div class="wp-block-buttons">
<!-- wp:button {"width":100,"className":"is-style-outline","style":{"typography":{"fontSize":"0.68rem","letterSpacing":"2px","textTransform":"uppercase"}}} -->
<div class="wp-block-button has-custom-width wp-block-button__width-100 is-style-outline" style="font-size:0.68rem;letter-spacing:2px;text-transform:uppercase"><a class="wp-block-button__link wp-element-button">Contact Us</a></div>
<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
</div>
<!-- /wp:column -->

</div>
<!-- /wp:columns -->

</div>
<!-- /wp:group -->';

	register_block_pattern( 'ifende/pricing', [
		'title'       => esc_html__( 'Pricing Table', 'ifende' ),
		'description' => esc_html__( 'Three-tier pricing table with features and CTA buttons.', 'ifende' ),
		'categories'  => [ 'ifende' ],
		'keywords'    => [ 'pricing', 'plans', 'table', 'packages' ],
		'content'     => $content,
	] );
}
