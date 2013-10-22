<?php

class Wpshop_Post
{
	private $view;
	private $post;
	public function __construct()
	{
		global $post;
		$this->post = &$post;
		$this->view = new Wpshop_View();
		if (is_admin())
		{
			add_action('admin_init', array(&$this,'PostMetaBoxInit'));
		}
		
		add_action('wp_head', array(&$this,"jsInc"));
	}
	
	public function PostMetaBoxInit()
	{
		if (function_exists('add_meta_box'))
		{
			//add_meta_box('wp-shop-p-metabox','WP-Shop',array(&$this,'PostMetaBoxAction'),'post','normal','high');
		}
	}
	
	public function PostMetaBoxAction()
	{
		$this->view->goodData = new Wpshop_Good_Data($this->post->ID);
		$this->view->post_id = $this->post->ID;
		$this->view->render("admin/post.metabox.php");
	}
	
	public function jsInc()
	{
		$this->view->render("js.inc.php");
	}
}

?>