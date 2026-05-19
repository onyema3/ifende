# Ifende Portfolio — Theme Documentation

A modern, dark-themed portfolio WordPress theme for creatives, developers, and consultants.

---

## Table of Contents

1. [Installation](#installation)
2. [Theme Setup](#theme-setup)
3. [Customizer Options](#customizer-options)
4. [Templates & Sections](#templates--sections)
5. [Block Patterns](#block-patterns)
6. [WooCommerce](#woocommerce)
7. [Live Chat](#live-chat)
8. [Performance Features](#performance-features)
9. [Developer Reference](#developer-reference)
10. [Child Theme](#child-theme)
11. [FAQ](#faq)

---

## Installation

### From WordPress Admin

1. Go to **Appearance > Themes > Add New > Upload Theme**
2. Choose the `ifende.zip` file and click **Install Now**
3. Click **Activate**

### Manual (FTP)

1. Extract `ifende.zip`
2. Upload the `ifende/` folder to `wp-content/themes/`
3. Activate via **Appearance > Themes**

### Requirements

- WordPress 6.0+
- PHP 8.0+
- Recommended: 256MB memory limit

---

## Theme Setup

After activation, configure the theme via **Appearance > Customize > Ifende Portfolio Options**.

### Navigation Menu

1. Go to **Appearance > Menus**
2. Create a menu and assign it to the **Primary Navigation** location
3. Add a CSS class `nav-cta` to any menu item to style it as a CTA button

### Custom Logo

1. Go to **Appearance > Customize > Site Identity**
2. Upload your logo (recommended: SVG or PNG, max height 60px)

### Dark/Light Mode

The theme defaults to dark mode. Users can toggle via the sun/moon button in the nav. The preference is saved in localStorage and respects `prefers-color-scheme`.

---

## Customizer Options

All settings live under **Appearance > Customize > Ifende Portfolio Options**.

| Section | Settings |
|---------|----------|
| **Hero** | Tagline, name, roles, bio, stats, availability status, photo URL |
| **About** | Full bio, location, freelance status, Twitter, skills list |
| **Marquee** | Comma-separated scrolling text items |
| **Services** | Up to 4 services (icon emoji, title, description each) |
| **Clients** | `Name\|URL\|Emoji` per line |
| **Testimonials** | `Name\|Role\|Quote` per line |
| **FAQ** | `Question\|Answer` per line (generates Schema.org markup) |
| **Newsletter** | Heading, description, form action URL, email field name |
| **Contact** | Email, social URLs, Formspree ID, Web3Forms key |
| **Analytics** | GA4 Measurement ID, custom head scripts |
| **Cookie/GDPR** | Enable/disable banner, message text, privacy policy URL |
| **Live Chat** | Provider selection, widget ID/code |

---

## Templates & Sections

### Page Templates

| Template | File | Use Case |
|----------|------|----------|
| Default | `page.php` | Standard pages |
| Full Width | `template-fullwidth.php` | No sidebar, edge-to-edge content |
| Canvas | `template-canvas.php` | Blank (no header/footer) — landing pages |

### Homepage Sections (in order)

1. **Hero** — `template-parts/section-hero.php`
2. **Marquee** — `template-parts/section-marquee.php`
3. **About** — `template-parts/section-about.php`
4. **Services** — `template-parts/section-services.php`
5. **Clients** — `template-parts/section-clients.php`
6. **Testimonials** — `template-parts/section-testimonials.php`
7. **Blog** — `template-parts/section-blog.php`
8. **FAQ** — `template-parts/section-faq.php`
9. **Newsletter** — `template-parts/section-newsletter.php`
10. **Contact** — `template-parts/section-contact.php`

### Other Templates

- `single.php` — Blog posts (with reading time, share buttons, related posts)
- `archive.php` — Blog archive grid
- `search.php` — Search results
- `404.php` — Enhanced 404 with search form
- `comments.php` — Threaded comments

---

## Block Patterns

Access via **Block Editor > Patterns > Ifende** category. Patterns mirror the homepage section template parts so you can compose custom pages by inserting the same sections anywhere.

### Section patterns

These reproduce the markup of `template-parts/section-*.php` and use the theme's existing CSS hooks. They render with the theme's section styling out of the box.

| Pattern | Mirrors | Description |
|---------|---------|-------------|
| Hero Section | `section-hero.php` | Full-height landing hero with name, role list, bio, CTAs, stat counters, and photo card with status pill |
| Marquee Strip | `section-marquee.php` | Infinite-loop scrolling text strip of skills/services |
| About Section | `section-about.php` | Two-column bio with profile facts, animated skill bars, and a CTA card |
| Services Section | `section-services.php` | Two-by-two services grid with numbered cards and hover accent stripe |
| Clients Section | `section-clients.php` | Four-column client logo grid with outbound arrows |
| Testimonials Section | `section-testimonials.php` | Auto-fitting grid of quote cards with avatar initials |
| Blog / Latest Posts | `section-blog.php` | Three-card teaser grid (placeholder cards — swap for a Latest Posts block to render live content) |
| FAQ Section | `section-faq.php` | Accordion-style Q&A list (the live section also emits Schema.org `FAQPage` markup) |
| Newsletter Signup | `section-newsletter.php` | Heading + inline email form (set the form action to your Mailchimp/ConvertKit URL before publishing) |
| Contact Section | `section-contact.php` | Two-column contact info + name/email/subject/message form |
| Portfolio Section | `section-portfolio.php` | Three-card grid with client, year, description, and tech tags (placeholder — swap for a Query block on the `ifende_project` CPT for live entries) |

### Utility patterns

Generic building blocks not tied to a specific section. Built from native core blocks, so they're easy to edit visually.

| Pattern | Description |
|---------|-------------|
| Call to Action | Full-width CTA banner with headline, supporting text, and primary + outline buttons |
| Pricing Table | Three-tier pricing table with a highlighted middle plan, feature lists, and CTA buttons |

### Tips

- Section patterns are emitted as raw HTML blocks — switch the block to **Edit as HTML** to change copy, or use **Convert to blocks** to break the markup into editable native blocks.
- The block editor canvas loads `assets/css/main.css` (via `add_editor_style()` in `inc/setup.php`), so pattern previews and the editing experience visually match the front-end.

---

## WooCommerce

The theme fully supports WooCommerce. Install WooCommerce and the theme automatically:

- Applies consistent styling to shop, product, cart, and checkout pages
- Enables product gallery zoom, lightbox, and slider
- Displays a 3-column product grid (12 products per page)
- Provides a **Shop Sidebar** widget area
- Styles breadcrumbs, notices, and My Account pages

No configuration needed — just install WooCommerce and it works.

---

## Live Chat

Supports **Tawk.to**, **Crisp**, and custom embed codes.

### Setup

1. Go to **Appearance > Customize > Ifende Portfolio Options > Live Chat**
2. Choose your provider:
   - **Tawk.to** — Enter your Property ID (from Tawk.to dashboard)
   - **Crisp** — Enter your Website ID (from Crisp dashboard)
   - **Custom** — Paste any chat widget embed code
3. The chat widget loads on the front-end only (never in admin)

---

## Performance Features

Built-in optimizations (no plugins needed):

- **Critical CSS** — ~5 KB of above-the-fold styles (root vars, nav, hero, CTAs, preloader, mobile breakpoints) inlined in `<head>` for instant first paint
- **Media-swap CSS deferral** — non-critical stylesheets load with `media="print"` and an `onload` swap to `media="all"`, so they download in parallel without blocking rendering. A `<noscript>` fallback covers JS-disabled clients
- **Script deferral** — non-critical scripts load with `defer` (theme bootstrap and the no-flash theme detector are exempt)
- **Self-hosted woff2 fonts** — Cormorant Garamond, Syne, and DM Mono are served from `assets/fonts/` with `font-display: optional` and the two LCP-critical files preloaded
- **Preload hints** — main stylesheet and critical fonts preloaded
- **DNS prefetch** — external font/analytics domains resolved early
- **Lazy loading** — images below the fold use `loading="lazy"`; hero images get `fetchpriority="high"`
- **Minified assets** — `.min.css` and `.min.js` served in production
- **Emoji removal** — WordPress emoji scripts disabled
- **Head cleanup** — generator tags, RSD, wlwmanifest removed

---

## Developer Reference

### Helper Functions

| Function | Description |
|----------|-------------|
| `ifende_opt( $key, $default )` | Get a theme_mod with `ifende_` prefix |
| `ifende_picture( $args, $echo )` | Output `<picture>` with WebP + fallback |
| `ifende_reading_time( $post_id )` | Get reading time in minutes |
| `ifende_reading_time_badge( $post_id )` | Display formatted reading time |
| `ifende_related_posts( $post_id, $count )` | Get related posts array |
| `ifende_share_buttons( $post_id, $echo )` | Display social share links |
| `ifende_is_built_with_elementor()` | Check if current page uses Elementor |

### Hooks & Filters

| Hook/Filter | Location | Purpose |
|-------------|----------|---------|
| `ifende_content_width` | `inc/setup.php` | Filter content width (default: 1200) |
| `script_loader_tag` | `inc/performance.php` | Adds `defer` to scripts |
| `wp_resource_hints` | `inc/enqueue.php` | Preconnect for Google Fonts |
| `mime_types` | `inc/images.php` | Enables WebP uploads |
| `body_class` | `inc/woocommerce.php` | Adds WooCommerce page class |

### File Structure

```
ifende/
├── assets/
│   ├── css/
│   │   ├── main.css            — Main theme styles
│   │   ├── main.min.css        — Minified production CSS
│   │   ├── editor-style.css    — Block editor styles
│   │   └── woocommerce.css     — WooCommerce styles
│   └── js/
│       ├── main.js             — Theme JavaScript
│       └── main.min.js         — Minified production JS
├── docs/
│   └── README.md               — This documentation
├── ifende-child/               — Starter child theme
├── inc/
│   ├── ajax.php                — Contact form AJAX handler
│   ├── analytics.php           — GA4 and tracking scripts
│   ├── blog.php                — Reading time, related posts, share
│   ├── customizer.php          — All Customizer settings
│   ├── enqueue.php             — Asset loading
│   ├── gdpr.php                — Cookie consent banner
│   ├── images.php              — WebP / picture element helpers
│   ├── livechat.php            — Live chat widget integration
│   ├── page-builders.php       — Elementor compatibility
│   ├── patterns.php            — Block patterns registration
│   ├── performance.php         — Critical CSS, defer, preload
│   ├── seo.php                 — JSON-LD, Open Graph
│   ├── setup.php               — Theme setup, widget areas
│   └── woocommerce.php         — WooCommerce support
├── languages/
│   └── ifende.pot              — Translation template
├── template-parts/             — Homepage sections
├── functions.php               — Bootstrap / includes
├── style.css                   — Theme header (metadata only)
└── readme.txt                  — WordPress.org readme
```

---

## Child Theme

A starter child theme is included at `ifende-child/`.

### Installation

1. Copy `ifende-child/` to `wp-content/themes/`
2. Activate **Ifende Child** in Appearance > Themes

### Customization

- **CSS** — Add overrides to `ifende-child/style.css`
- **PHP** — Add functions to `ifende-child/functions.php`
- **Templates** — Copy any parent template into the child theme to override

See `ifende-child/README.md` for full details.

---

## FAQ

**Q: Does this theme work with Elementor?**
Yes. Full compatibility including Theme Builder locations. Pages built with Elementor automatically hide the theme's default title.

**Q: How do I regenerate minified files?**
Run `make minify` from the theme root (requires Node.js with `terser` and `clean-css-cli`).

**Q: Can I use this for a multi-page site?**
Absolutely. Use the standard Page template or Full Width template. The homepage sections only appear on `index.php`.

**Q: How do I add Google Analytics?**
Go to Customize > Ifende Portfolio Options > Analytics and enter your GA4 Measurement ID.

**Q: Is the theme translation-ready?**
Yes. All strings use WordPress i18n functions. A `.pot` file is included in `languages/`.

---

## License

GNU General Public License v2 or later — [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.html)
