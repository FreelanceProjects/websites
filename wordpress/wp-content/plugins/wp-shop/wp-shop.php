<?php
/*
 Plugin Name: WP-Shop
 Plugin URI: http://www.wp-shop.ru
 Description: Интернет-магазин для WordPress.
 Author: www.wp-shop.ru
 Version: 3.3.5.
 Author URI: http://www.wp-shop.ru
 */

define( 'WPSHOP_DIR', dirname(realpath(__FILE__)));
define( 'WPSHOP_URL', plugins_url("",__FILE__) );
define( 'WPSHOP_CLASSES_DIR' , WPSHOP_DIR ."/classes");
define( 'WPSHOP_VIEWS_DIR' , WPSHOP_DIR ."/views");
define( 'WPSHOP_DATA_DIR', WPSHOP_DIR ."/data");

define( 'CURR_BEFORE',	'&nbsp;' );
define( 'CART_ID',		'wpshop_cart' );
define( 'MINICART_ID',	'wpshop_minicart' );
define( 'CART_TAG',		'[cart]' );
define( 'MINICART_TAG',	'[minicart]' );
define( 'SPL', '}{' );

function wpshopAutoload($ClassName)
{	$class = array();
	preg_match("/Wpshop_(\S+)/",$ClassName,$class);
	$file = WPSHOP_CLASSES_DIR."/class.Wpshop.{$class[1]}.php";
	if (file_exists($file))
	{
		require_once($file);
	}
}

spl_autoload_register('wpshopAutoload');

$WpShopBoot = new Wpshop_Boot();
