<?php
/**
 * Ifende Portfolio — sidebar.php
 * Template for displaying the sidebar widget area.
 *
 * @package Ifende
 */

if ( ! is_active_sidebar( 'ifende-sidebar' ) ) {
  return;
}
?>
<aside id="secondary" class="widget-area" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'ifende' ); ?>">
  <?php dynamic_sidebar( 'ifende-sidebar' ); ?>
</aside>
