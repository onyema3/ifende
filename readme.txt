=== Ifende Portfolio ===
Contributors: onyemechi
Tags: portfolio, one-page, dark, custom-colors, custom-logo, custom-menu, editor-style, featured-images, threaded-comments, translation-ready, block-styles, wide-blocks
Requires at least: 6.0
Tested up to: 6.5
Requires PHP: 8.0
Stable tag: 1.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

A modern, dark-themed portfolio WordPress theme for creatives, developers, and consultants.

== Description ==

Ifende is a sleek, performance-focused portfolio theme designed for project managers, web developers, consultants, and game developers. It features a one-page layout with smooth scroll navigation, dark/light mode toggle, custom cursor, and full compatibility with Elementor and the block editor.

**Key Features:**

* Dark and light mode with system preference detection
* Smooth scroll navigation with progress indicator
* Custom animated cursor (non-touch devices)
* Elementor and Block Editor compatible
* One-page sections: Hero, About, Services, Clients, Testimonials, FAQ, Blog, Contact
* Google Fonts with performance-optimized loading
* GDPR-compliant analytics and cookie consent
* SEO-optimized with structured data support
* WebP image support with `<picture>` element helper
* Blog enhancements: reading time, related posts, share buttons
* Critical CSS inlining and script deferral for performance
* Fully responsive design
* Accessibility-ready with skip links and ARIA labels
* Translation-ready with .pot file included

== Installation ==

1. In your WordPress admin panel, go to Appearance > Themes and click "Add New".
2. Click "Upload Theme" and choose the ifende.zip file.
3. Click "Install Now" and then "Activate".
4. Navigate to Appearance > Customize to configure your portfolio content.

== Frequently Asked Questions ==

= Does this theme support Elementor? =

Yes. Ifende is fully compatible with Elementor and Elementor Pro, including Theme Builder locations for header and footer overrides.

= How do I switch between dark and light mode? =

The theme includes a toggle button in the navigation bar. It also respects the user's system color scheme preference on first visit.

= Can I use this theme for a multi-page site? =

Yes. While designed as a one-page portfolio, Ifende includes templates for standard pages, blog posts, archives, and search results.

= Is a child theme available? =

Yes. An `ifende-child` starter theme is included in the package with proper setup and documentation.

== Changelog ==

= 1.5.0 =
* Added: 11 block patterns mirroring template-parts/section-*.php (hero, marquee, about, services, clients, testimonials, blog, faq, newsletter, contact, portfolio) plus 2 utility patterns (cta, pricing).
* Added: Block patterns auto-discovery via /patterns/ — one file per pattern, no PHP registration boilerplate. inc/patterns.php now only registers the category.
* Added: Tools > Regenerate Thumbnails admin page — bulk rebuilds image subsizes via batched AJAX (5 attachments per request, 'manage_options' + nonce gated, per-attachment audit log).
* Added: Schema.org BlogPosting JSON-LD on single posts (headline, dates, author, publisher with logo, image, categories, tags, word count, mainEntityOfPage).
* Added: Schema.org BreadcrumbList JSON-LD on every non-front-page request, sourced from the same trail builder as the visible breadcrumbs.
* Added: Schema.org SearchAction in the WebSite schema so search engines can advertise the site search (Google sitelinks searchbox).
* Improved: Critical CSS bundle expanded from ~1 KB to ~5 KB so the entire above-the-fold (root vars, reset, nav, hero, CTAs, preloader, mobile + reduced-motion breakpoints) paints correctly without the main stylesheet.
* Improved: Non-critical stylesheets now use the media-swap deferral technique (media="print" + onload swap to media="all") with a <noscript> fallback, so they download in parallel without blocking rendering.
* Improved: main.css is loaded into the block editor canvas via add_editor_style() so pattern previews and the editing experience visually match the front-end.
* Fixed: 52 PHPCS escaping/sanitisation errors across comments.php and 8 inc/ files. All real bugs (missing wp_unslash before sanitize, missing nonce sanitisation, unescaped output) — the gate now covers the entire theme instead of skipping nine files.
* Fixed: Theme Quality Check CI workflow — Composer 2.2+ plugin allowlist for the WPCS install, plus a scoped phpcs:ignore for the EnqueuedResources sniff inside the style_loader_tag filter (where it doesn't apply by definition).
* Fixed: Makefile 'js' target no longer regresses main.min.js to a verbatim copy of main.js when terser isn't installed; it now leaves the existing min file untouched and prints a visible warning instead.
* Removed: Stale fonts.googleapis.com preload in inc/performance.php (the theme self-hosts woff2 — the preload pointed at a stylesheet nothing requested).
* Removed: Deprecated WordPress.PHP.POSIXFunctions sniff from phpcs.xml.dist (replaced upstream by PHPCompatibilityWP, already in the ruleset).

= 1.1.0 =
* Added: WebP image support with `ifende_picture()` helper function
* Added: Child theme starter (ifende-child)
* Added: Blog enhancements — reading time, related posts, share buttons
* Added: Performance optimizations — critical CSS inlining, script deferral, preload hints
* Added: WordPress.org readme.txt and Theme Check compliance
* Improved: Accessibility and ARIA landmark labeling

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.5.0 =
Adds 13 block patterns and auto-discovery, a bulk Regenerate Thumbnails admin tool, BlogPosting + BreadcrumbList + SearchAction Schema.org coverage, and ~5 KB of inlined critical CSS with media-swap deferral. Also pays down 52 escaping/sanitisation errors in admin form handlers — recommended upgrade for any production install.

= 1.1.0 =
Adds WebP support, blog enhancements, performance optimizations, and WordPress.org compliance.

== Resources ==

= Fonts =
Syne, Cormorant Garamond, DM Mono — Google Fonts
License: SIL Open Font License, 1.1
Source: https://fonts.google.com

= Icons =
Custom SVG icons used in the theme are original works released under GPLv2.

= Images =
screenshot.png — Original work by the theme author, released under GPLv2.

== Copyright ==

Ifende Portfolio WordPress Theme, Copyright 2024 Onyemechi Ifende
Ifende is distributed under the terms of the GNU GPL v2 or later.
