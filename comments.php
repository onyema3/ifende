<?php
/**
 * Ifende Portfolio — comments.php
 * Template for displaying comments.
 *
 * @package Ifende
 */

if ( post_password_required() ) {
  return;
}
?>
<section id="comments" class="comments-area" style="margin-top:64px;padding-top:48px;border-top:1px solid var(--border);" aria-label="<?php esc_attr_e( 'Comments', 'ifende' ); ?>">
  <?php if ( have_comments() ) : ?>
    <div class="section-label"><?php esc_html_e( 'Discussion', 'ifende' ); ?></div>
    <h2 class="comments-title" style="font-family:'Cormorant Garamond',serif;font-size:1.8rem;font-weight:300;color:var(--white);margin-bottom:32px;">
      <?php
      $comment_count = get_comments_number();
      printf(
        /* translators: 1: comment count */
        esc_html( _n( '%1$s Comment', '%1$s Comments', $comment_count, 'ifende' ) ),
        esc_html( number_format_i18n( $comment_count ) )
      );
      ?>
    </h2>

    <ol class="comment-list" style="list-style:none;padding:0;">
      <?php
      wp_list_comments( [
        'style'       => 'ol',
        'short_ping'  => true,
        'avatar_size' => 42,
      ] );
      ?>
    </ol>

    <?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : ?>
      <nav class="comment-navigation" aria-label="<?php esc_attr_e( 'Comment navigation', 'ifende' ); ?>" style="margin-top:32px;display:flex;justify-content:space-between;">
        <div><?php previous_comments_link( esc_html__( '&larr; Older Comments', 'ifende' ) ); ?></div>
        <div><?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'ifende' ) ); ?></div>
      </nav>
    <?php endif; ?>

    <?php if ( ! comments_open() ) : ?>
      <p class="no-comments" style="color:var(--grey);font-size:0.88rem;margin-top:24px;"><?php esc_html_e( 'Comments are closed.', 'ifende' ); ?></p>
    <?php endif; ?>
  <?php endif; ?>

  <?php
  comment_form( [
    'title_reply'          => esc_html__( 'Leave a Comment', 'ifende' ),
    'title_reply_before'   => '<h3 id="reply-title" class="comment-reply-title" style="font-family:\'Cormorant Garamond\',serif;font-size:1.6rem;font-weight:300;color:var(--white);margin-bottom:24px;">',
    'title_reply_after'    => '</h3>',
    'comment_notes_before' => '<p class="comment-notes" style="font-size:0.82rem;color:var(--grey);margin-bottom:20px;">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'ifende' ) . '</p>',
    'class_form'           => 'contact-form',
    'class_submit'         => 'btn-submit',
    'label_submit'         => esc_html__( 'Post Comment', 'ifende' ),
  ] );
  ?>
</section>
