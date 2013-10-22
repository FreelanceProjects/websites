<?php
include "../../../wp-load.php";
wp();
if ($_POST['act'] == 'price_options')
{
	update_option('wpshop_price_under_title', $_POST['under_title']);
}
?>