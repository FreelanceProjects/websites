<script type="text/javascript">
jQuery(function()
{
	jQuery(".cform").prepend("<input type='hidden' name='delivery' value=''/>");
	window.__cart.afterChange();
});
</script>
<br/>
Выберите способ доставки:
<select name="select_delivery">
	<?php
	$firstPayment = null;
	foreach($this->delivery as $delivery)
	{
		foreach($this->payments as $payment)
		{
			if (in_array($delivery->ID,$payment->data['delivery']) && $payment->paymentID == $_GET['payment'])
			{
				if ($firstPayment == null) 
				{
					$firstPayment = $delivery->ID;
					$selected = ' selected';
				}
				else
				{
					$selected = '';
				}
				echo "<option value='{$delivery->ID}' cost='{$delivery->cost}'{$selected}>{$delivery->name}</option>";
			}
		}
	}
	?>
</select>
&nbsp;&nbsp;
<a href="<?php echo get_option('wpshop.cart.deliveyrCondition');?>">Подробнее о доставке</a>