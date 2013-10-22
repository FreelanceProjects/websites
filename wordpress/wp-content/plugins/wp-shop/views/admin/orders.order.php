<div><strong><a href="javascript:history.back()"> <- Назад</a></strong></div>
<br/>
<div class="wrap">
	<form method="post">
	<div id="poststuff" class="metabox-holder" style="padding:0px;">
		<div id="side-sortables" class="meta-box-sortabless ui-sortable">
			<div id="sm_pnres" class="postbox">
			<h3 class="hndle"><span>Сделанные заказы -> Заказ № <?php echo $this->order->order_id;?></span></h3>
				<div class="inside">
					<div id="wpshop_order_info" style="margin-right:30px">
						<?php $tm = date("d.m.Y г. H:i:s",$this->order->order_date);?>
						<div><strong>Дата:</strong> <?php echo $tm;?></div>
						<div><strong>Клиент:</strong> <?php echo $this->order->client_name;?></div>
						<div><strong>E-mail:</strong> <a href="mailto:<?php echo $this->order->client_email;?>"><?php echo $this->order->client_email;?></a></div>
						<div><strong>Способ оплаты:</strong> <?php echo $this->order->payment;?></div>
						<div><strong>Способ доставки</strong> <?php 
						try
						{
							$delivery = Wpshop_Delivery::getInstance()->getDelivery($this->order->order_delivery)->name;
						}
						catch(Exception $e)
						{
							$delivery = "не указано";
						}
						echo $delivery;
						
						?></div>
						<div><strong>Статус заказа:</strong>
							<select name='order[status]'>
							<?php
							foreach(Wpshop_Orders::getInstance()->getStatuses() as $key=>$status)
							{
								$selected = "";
								if ($this->order->order_status == $key)
								{
									$selected = " selected";
								}
								echo "<option value='{$key}'{$selected}>{$status}</option>";
							}						
							?>
							</select>
						</div>
						<div><strong>IP-клиента:</strong> <?php echo $this->order->client_ip;?></div>			
					</div>
					
					<div style='padding:0 50px'>
						<div style = 'font-size:14px;font-weight:bold;'>Комментарий к заказу</div>
						<textarea name='order[comment]' style='width:300px;height:200px'><?php echo $this->order->order_comment;?></textarea>
					</div>
					<div style="clear: both;"></div>
					<input type="hidden" name="order[save]" value="1"/>
					<input type="submit" value='Сохранить' class='button'/>
				</div>
			</div>
		</div>
	</div>

	<table cellpadding="0" cellspacing="5" border="0" class="widefat">
		<thead>
			<tr>
				<th style='width:20px'>№</th>
				<th>Наименование</th>
				<th>&nbsp;</th>
				<th style="text-align:center;">Кол-во</th>
				<th style="text-align:center;">Цена</th>
				<th style="text-align:center;">Итого</th>
			</tr>
		</thead>
		<tbody>
		<?php
		$i = 0;
		
		$delivery = Wpshop_Delivery::getInstance()->getDelivery($this->order->order_delivery);
		
		$itogo = 0;
		
		foreach($this->ordered as $order)
		{
			$i++;
			$total = $order->ordered_count * $order->ordered_cost;
			$itogo += $total;
			echo "<tr><td>{$i}.</td><td>{$order->ordered_name}</td><td>{$order->ordered_key}</td><td style='text-align:center;'>{$order->ordered_count}</td><td style='text-align:center'>{$order->ordered_cost}</td><td style='text-align:center'>{$total}</td></tr>";
		}
		?>
		</tbody>
		<tfoot>
			<tr><td colspan='5' style='text-align:right;font-weight:bold'>Итого:</td><td style='text-align:center;font-weight:bold'><?php echo $itogo;?></td></tr>
			<?php if (!empty($this->order->order_discount)) : 
			$discount = $itogo /100 * $this->order->order_discount;
			$itogo = $itogo - $discount;
			?>
			<tr><td colspan='5' style='text-align:right;font-weight:bold'>Скидка (<?php echo $this->order->order_discount;?>%):</td><td style='text-align:center'>-<?php echo $discount;?></td></tr>
			<?php endif;?>
			<?php if (!empty($delivery)) :
			$itogo = $itogo + $delivery->cost;
			?>
			<tr><td colspan='5' style='text-align:right;font-weight:bold'>Доставка: </td><td style='text-align:center'><?php echo $delivery->cost;?></td></tr>
			<?php endif;?>
			<tr><td colspan='5' style='text-align:right;font-weight:bold'>ВСЕГО: </td><td style='text-align:center;font-weight:bold'><?php echo $itogo;?></td></tr>
		</tfoot>
	</table>
	</form>
</div>
