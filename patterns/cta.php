<?php
/**
 * Title: Call to Action
 * Slug: ifende/cta
 * Categories: ifende
 * Description: Full-width CTA banner with headline, supporting text, and primary + outline buttons. Drop in at the end of a landing page.
 * Keywords: cta, call to action, banner
 * Inserter: yes
 *
 * Utility pattern (not tied to a specific homepage section) built from
 * native core blocks for easy visual editing.
 */
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"80px","bottom":"80px","left":"5vw","right":"5vw"}},"color":{"background":"rgba(33,161,78,0.05)"},"border":{"top":{"color":"rgba(245,242,236,0.12)","width":"1px"},"bottom":{"color":"rgba(245,242,236,0.12)","width":"1px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull has-background" style="background-color:rgba(33,161,78,0.05);border-top-color:rgba(245,242,236,0.12);border-top-width:1px;border-bottom-color:rgba(245,242,236,0.12);border-bottom-width:1px;padding-top:80px;padding-bottom:80px;padding-left:5vw;padding-right:5vw">

<!-- wp:columns {"verticalAlignment":"center"} -->
<div class="wp-block-columns are-vertically-aligned-center">

<!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%">
<!-- wp:heading {"style":{"typography":{"fontSize":"clamp(1.8rem,3vw,2.8rem)","fontWeight":"300"}}} -->
<h2 class="wp-block-heading" style="font-size:clamp(1.8rem,3vw,2.8rem);font-weight:300">Ready to Start Your <em>Next Project</em>?</h2>
<!-- /wp:heading -->
<!-- wp:paragraph {"style":{"color":{"text":"#8a8a8a"},"typography":{"fontSize":"0.95rem","lineHeight":"1.8"}}} -->
<p style="color:#8a8a8a;font-size:0.95rem;line-height:1.8">Let's discuss how we can work together to bring your ideas to life. Free consultation available.</p>
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
<!-- /wp:group -->
