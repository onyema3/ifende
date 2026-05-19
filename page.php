<?php
/**
 * Ifende Portfolio — page.php
 */
get_header();
?>
<div style="padding:140px 5vw 80px;max-width:860px;margin:0 auto;">
  <?php while(have_posts()): the_post(); ?>
    <h1 style="font-family:'Cormorant Garamond',serif;font-size:clamp(2rem,5vw,3.5rem);font-weight:300;color:var(--white);margin-bottom:32px;"><?php the_title(); ?></h1>
    <div style="font-size:0.95rem;line-height:1.8;color:rgba(245,242,236,0.7);"><?php the_content(); ?></div>
  <?php endwhile; ?>
</div>
<?php get_footer(); ?>
