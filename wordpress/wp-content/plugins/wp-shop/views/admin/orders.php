<script type="text/javascript">
jQuery(function()
{
	jQuery("[name='check_all_orders']").click(function()
	{
		jQuery("[name='order_check[]']").attr("checked",jQuery(this).is(':checked'));
	});
});
</script>
<?php
	$this_url = get_bloginfo('wpurl')."/wp-admin/admin.php?page=wpshop_orders";
?>
<div class="wrap">
<h2><?php echo __("Сделанные заказы",'wpshop');?></h2>
	<form action="<?php echo $this_url;?>" method="get">
	<input type='hidden' name='page' value='wpshop_orders'/>
	<table class='wp-list-table widefat posts'>
	<thead>
		<tr>
			<th colspan="5">Фильтр</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>
			<label for="filter_payment">Форма оплаты</label>
			<select name='filter_payment' id="filter_payment">
				<?php if (isset($_POST['filter_payment']) && $_POST['filter_payment'] != '')
				{
					$selected = '';
				}
				else
				{
					$selected = " selected='selected'";
				}?>
				<option value=''<?php echo $selected;?>>Все</option>
				<?php
					$payments = Wpshop_Payment::getSingleton()->getPayments();
					foreach($payments as $payment)
					{
						if (isset($this->post['filter_payment']) && $this->post['filter_payment'] === $payment->paymentID)
						{
							$selected = " selected='selected'";
						}
						else
						{
							$selected = '';
						}
				
						echo "<option value='{$payment->paymentID}'{$selected}>{$payment->name}</option>";
					}
				?>
			</select>
			</td>
			
			<td>
			<label for="filter_delivery">Способ доставки</label>
			<select name='filter_delivery' id="filter_payment">
				<?php if (isset($this->post['filter_delivery']) && $this->post['filter_delivery'] != '')
				{
					$selected = '';
				}
				else
				{
					$selected = " selected='selected'";
				}?>
				<option value='-1'<?php echo $selected;?>>Все</option>
				<?php
					$deliveries = Wpshop_Delivery::getInstance()->getDeliveries();
					foreach($deliveries as $delivery)
					{
						if (isset($this->post['filter_delivery']) && $this->post['filter_delivery'] === $delivery->ID)
						{
							$selected = " selected='selected'";
						}
						else
						{
							$selected = '';
						}
				
						echo "<option value='{$delivery->ID}'{$selected}>{$delivery->name}</option>";
					}
				?>
			</select>
			</td>			
			
			<td>
			<label>Статус</label>			
			<select name='filter_status'>
			<option value='-1'>Все</option>
				<?php
				foreach(Wpshop_Orders::getInstance()->getStatuses() as $key => $status)
				{
					$selected = '';
					if (isset($this->post['filter_status']) && $this->post['filter_status'] == $key)
					{
						$selected = " selected='selected'";	
					}
					echo "<option value='{$key}'{$selected}>{$status}</option>";
				}			
				?>
				</select>
			</td>
			<td>
			<label>Дата</label><nobr>
			<label>с</label> <input type="text" name="filter_date_from" value='<?php echo $this->filter_date_from;?>' style='width:80px'/>
			<label>по</label> <input type="text" name="filter_date_to" value='<?php echo $this->filter_date_to;?>' style='width:80px'/>
			</nobr>
			</td>
			<td >
				<input type="submit" name="" id="post-query-submit" class="button-secondary" value="Фильтр"/>
			</td>
		</tr>
	</tbody>
	</table>
	</form>
	<br/>
	<form method="post">
	<input type="hidden" name="mass_action" value="1" />
	<table cellpadding="0" cellspacing="0" border="0" id="wpshop_orders_list" class="widefat">
	<thead>
		<th style="text-align:center;"><input type="checkbox" name="check_all_orders" style="margin:0px"/></th>
		<th>№</th>
		<th>Форма оплаты</th>
		<th>Способ доставки</th>		
		<th>Дата / Время</th>
		<th>Клиент</th>
		<th>E-mail</th>
		<th>Статус</th>
	</thead>
	<tbody>
	<?php 
	//print_r($this->orders);
	
	foreach($this->orders as $order)
	{

		if ($order->order_payment != '')
		{
			$payment = Wpshop_Payment::getSingleton()->getPaymentByID($order->order_payment);
			$paymentName = $payment->name;
		}
		else
		{
			$paymentName = __("не определено","wpshop");
		}

		$tm = date("d.m.Y г. H:i:s",$order->order_date);

		$actions = "<div style='visibility:hidden' class='orders_row_actions'><a href='{$_SERVER['REQUEST_URI']}&act=edit&order_id={$order->order_id}'>Просмотреть</a> <a href='{$_SERVER['REQUEST_URI']}&act=delete&order_id={$order->order_id}'>Удалить</a></div>";

		$orderStatus = Wpshop_Orders::getInstance()->getStatus($order->order_status);
		
		if ($order->order_status == 1)
		{
			$orderStatus = "<strong style='color:green;'>{$orderStatus}</strong>";
		}
		
		$d = Wpshop_Delivery::getInstance()->getDelivery($order->order_delivery);
		if ($d != null)
		{
			$delivery_str = $d->name;			
		}
		else
		{
			$delivery_str = "не указано";
		}
		echo "<tr><td style='text-align:center;padding:5px;'><input type='checkbox' name='order_check[]' value='{$order->order_id}'</td><td><a href='{$_SERVER['REQUEST_URI']}&act=edit&order_id={$order->order_id}'>{$order->order_id}.</a></td><td>{$paymentName}</td><td>{$delivery_str}</td><td>{$tm}{$actions}</td><td>{$order->client_name}</td><td><a href='mailto:{$order->client_email}'>{$order->client_email}</a></td><td>{$orderStatus}</td></tr>";
	}
	?>
	</tbody>
	</table>
	<div style="margin:10px 0">
	<select name="orders_status">
<?php
	$statuses = Wpshop_Orders::getInstance()->getStatuses();
	foreach($statuses as $key=>$status)
	{
		echo "<option value='{$key}'>{$status}</option>";
	}
?>
	
	</select>
	<input type="submit" value="Применить" class="button-secondary"/>
	</div>
	</form>
	<script type="text/javascript">
	jQuery("#wpshop_orders_list tbody tr").hover(function()
		{
			jQuery(this).find('.orders_row_actions').css('visibility','visible');
		},
		function()
		{
			jQuery(this).find('.orders_row_actions').css('visibility','hidden');
		}
	)
	</script>

	<ul class="wpshop_pagers">
	<?php

	$url = $this_url;

	$queryString = $_SERVER['QUERY_STRING'];
	$queryString = str_replace("page=wpshop_orders&","",$queryString);
	

	$post = serialize($_POST);
	for ($i = 0; $i < $this->page['count']; $i++)
	{
		$page = $i+1;

		if (strpos($queryString,"num_page") !== false)
		{
			$prefix = preg_replace("/num_page=(\d+)/","num_page={$page}",$queryString);
		}
		else
		{
			$prefix = $queryString . "&num_page={$page}";
		}
		
		$url = $this_url . "&{$prefix}";

		echo "<li>";
		if ($this->page['current'] == $page)
		{
			echo "{$page}";
		}
		else
		{
			echo "<a href='{$url}' style='text-decoration:none;'>{$page}</a>";
		}
		echo "</li>";
	}
	?>
	</ul>
</div>

