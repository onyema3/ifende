# Ifende Child Theme

A starter child theme for the **Ifende Portfolio** WordPress theme.

## Installation

1. **Upload both themes** — Ensure the parent `ifende` theme and this `ifende-child` folder are both in `wp-content/themes/`.
2. **Activate** — In *Appearance > Themes*, activate **Ifende Child**.

## Usage

### Custom Styles

Add your CSS overrides to `style.css`. The child theme stylesheet loads after the parent, so your rules will take priority.

```css
/* Example: Change the accent color */
:root {
  --green: #ff6b35;
}
```

### Custom Functions

Add PHP customizations to `functions.php`. The child theme's `functions.php` loads **in addition to** (not instead of) the parent's.

```php
// Example: Add a custom widget area
function my_custom_sidebar() {
    register_sidebar( [
        'name'          => 'Custom Sidebar',
        'id'            => 'custom-sidebar',
        'before_widget' => '<div class="widget">',
        'after_widget'  => '</div>',
    ] );
}
add_action( 'widgets_init', 'my_custom_sidebar' );
```

### Template Overrides

To override a parent theme template, copy it into this child theme folder with the same filename:

```
ifende-child/
├── header.php          ← Overrides parent's header.php
├── single.php          ← Overrides parent's single.php
├── template-parts/
│   └── section-hero.php  ← Overrides parent's template part
```

## File Structure

```
ifende-child/
├── style.css        — Theme header + custom styles
├── functions.php    — Enqueues parent styles + custom functions
└── README.md        — This file
```

## Requirements

- WordPress 6.0+
- PHP 8.0+
- Parent theme: **Ifende** (must be installed)

## License

GNU General Public License v2 or later — [GPL-2.0+](http://www.gnu.org/licenses/gpl-2.0.html)
