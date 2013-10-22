<?php
/**
 * @package WordPress
 * @subpackage Classic_Theme
 */
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<?php /*the_date('','<h2>','</h2>');*/ ?>

<div <?php post_class() ?> id="post-<?php the_ID(); ?>">
	 <h3 class="storytitle"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_title(); ?></a></h3>
<?/*	<div class="meta"><?php _e("Filed under:"); ?> <?php the_category(',') ?> &#8212; <?php the_tags(__('Tags: '), ', ', ' &#8212; '); ?> <?php the_author() ?> @ <?php the_time() ?> <?php edit_post_link(__('Edit This')); ?></div>
*/?>
	<div class="storycontent">
		<?php the_content(__('(more...)')); ?>
<?/*		<div class="entrytext entrytext-page">
		<?php
				echo apply_filters('the_content', '<div class="the_content_block">'.$post->post_content.'</div>');
						?>
						</div>
*/?>	</div>

	<div class="feedback">
		<?php wp_link_pages(); ?>
		<?php comments_popup_link(__('Комментарии (0)'), __('Комментарий (1)'), __('Комментариев (%)')); ?>
	</div>

</div>

<?php comments_template(); // Get wp-comments.php template ?>

<?php endwhile; else: ?>
<p><?php _e('Sorry, no posts matched your criteria.'); ?></p>
<?php endif; ?>
<?php if(function_exists('wp_pagenavi')) : wp_pagenavi(); else : ?>
<?php posts_nav_link(' &#8212; ', __('&laquo; НАЗАД'), __('ВПЕРЕД &raquo;')); ?>
<?php endif; ?>

<?php get_footer(); ?>
