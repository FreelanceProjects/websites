<?php
include "../../../wp-load.php";
wp();
update_option('wp-shop-usd',$_POST['usd']);
update_option('wp-shop-eur',$_POST['eur']);

$usd_opt = $_POST['usd'];
$eur_opt = $_POST['eur'];

$results=$wpdb->get_results("SELECT * FROM $wpdb->posts");

foreach($results as $row)
{
	$temp = get_post_custom($row->ID);
	
	foreach($temp as $key => $value)
	{
		if (preg_match('/usd_(\d+)/',$key,$ar))
		{
			$usd = get_post_meta($row->ID,"usd_{$ar[1]}",true);
			
			if (update_post_meta($row->ID,"cost_{$ar[1]}",$usd * $usd_opt) === false)
			{
				add_post_meta($row->ID,"cost_{$ar[1]}",$usd * $usd_opt);
			}
		}
		if (preg_match('/eur_(\d+)/',$key,$ar))
		{
			$eur = get_post_meta($row->ID,"eur_{$ar[1]}",true);
			
			if (update_post_meta($row->ID,"cost_{$ar[1]}",$eur * $eur_opt) === false)
			{
				add_post_meta($row->ID,"cost_{$ar[1]}",$eur * $eur_opt);
			}
		}		
	}
}
?>