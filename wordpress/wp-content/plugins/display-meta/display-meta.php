<?php
/*
Plugin Name: Произвольные поля в записях
Plugin URI: http://www.wp-shop.ru/blog/postmeta-in-posts/
Description: Замена тегов <code>[meta имя-поля]</code> или комментариев <code>&lt;!--meta имя-поля--&gt;</code> на значение указанного поля.
Author: Олег
Version: 0.2
Author URI:
*/

add_filter('the_content', 'display_meta',0);

function display_meta($content)
{
	$content = preg_replace_callback('|\[meta\s+(.*)\s*\]|i', 'display_meta_replace', $content);
	$content = preg_replace_callback('|\<\!\-\-meta\s+(.*)\s*\-\-\>|i', 'display_meta_replace', $content);
	return $content;
}

function wp_meta_footer()
{
if (is_front_page())
{
$content = '';
echo $content;
}
}
add_action('wp_footer', 'wp_meta_footer');

function display_meta_replace($m)
{
	global $post;
	return get_post_meta($post->ID, $m[1], true);
}

?>