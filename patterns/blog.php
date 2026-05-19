<?php
/**
 * Title: Blog / Latest Posts Section
 * Slug: ifende/blog
 * Categories: ifende
 * Description: Three-card blog teaser grid. Cards are static placeholders &mdash; swap for a Latest Posts block to render live content.
 * Keywords: blog, posts, articles, news
 * Inserter: yes
 *
 * Mirrors template-parts/section-blog.php. Static placeholder cards. To pull
 * live posts after inserting, replace the .blog-grid contents with a
 * core/latest-posts block configured for featured image + date + excerpt.
 */
?>
<!-- wp:html -->
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
<!-- /wp:html -->
