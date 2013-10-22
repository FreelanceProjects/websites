<style type='text/css'>
.postbox h3
{
	cursor: none;
}
</style>
<div class="wrap">
<h2>Способы оплаты</h2>
<form method="POST">
<input type="hidden" name="update_payments" value="1"/>
		<div id="poststuff">
		<div class="postbox">
			<h3>Самовывоз</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Включить поддержку самовывоза из магазина/офиса.</td>
					<?php $vizit_activate = $this->vizit['activate'] ? " checked" : "";?>
					<td><input type="checkbox" name="wpshop_payments_vizit[activate]"<?php echo $vizit_activate;?>/></td>
				</tr>

				<tr>
					<td>Доставка</td>
					<td>
					<?php
						
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if (in_array($delivery->ID,$this->vizit['delivery']))
							{
								$checked = " checked";
							}
							echo "<input type='checkbox' name='wpshop_payments_vizit[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
						}
						
					?>
					</td>
				</tr>
			</table>
			</div>
		</div>
	</div>

	<div id="poststuff">
		<div class="postbox">
			<h3>Наличными курьеру</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Включить поддержку оплаты курьеру</td>
					<?php $cash_activate = $this->cash['activate'] ? " checked" : "";?>
					<td><input type="checkbox" name="wpshop_payments_cash[activate]"<?php echo $cash_activate;?>/></td>
				</tr>				
				<tr>
					<td>Доставка</td>
					<td>
					<?php
						
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if (in_array($delivery->ID,$this->cash['delivery']))
							{
								$checked = " checked";
							}							
							echo "<input type='checkbox' name='wpshop_payments_cash[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
						}
						
					?>
					</td>
				</tr>
			</table>
			</div>
		</div>
	</div>
	<div id="poststuff">
		<div class="postbox">
			<h3>Наложный платеж</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Включить поддержку наложного платежа</td>
					<?php $post_activate = $this->post['activate'] ? " checked" : "";?>
					<td><input type="checkbox" name="wpshop_payments_post[activate]"<?php echo $post_activate;?>/></td>
				</tr>
				<tr>
					<td>Доставка</td>
					<td>
					<?php
						
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if (in_array($delivery->ID,$this->post['delivery']))
							{
								$checked = " checked";
							}
							echo "<input type='checkbox' name='wpshop_payments_post[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
						}
						
					?>
					</td>
				</tr>
			</table>
			</div>
		</div>
	</div>	
	
	<div id="poststuff">
		<div class="postbox">
			<h3>Ваши банковские реквизиты</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Включить поддержку оплаты через банк</td>
					<?php $bank_activate = $this->bank['activate'] ? " checked" : "";?>
					<td><input type="checkbox" name="wpshop_payments_bank[activate]"<?php echo $bank_activate;?>/></td>
				</tr>			
				<tr>
					<td>Доставка</td>
					<td>
					<?php
						
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							
							if (is_array($this->bank['delivery']) && in_array($delivery->ID,$this->bank['delivery']))
							{
								$checked = " checked";
							}
							echo "<input type='checkbox' name='wpshop_payments_bank[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
						}
						
					?>
					</td>
				</tr>
				<tr>
					<td>БИК</td>
					<td><input type="text" name="wpshop_payments_bank[bik]" value='<?php echo $this->bank['bik'];?>'/></td>
				</tr>
				<tr>
					<td>Лицевой счет</td>
					<td><input type="text" name="wpshop_payments_bank[ls]" value='<?php echo $this->bank['ls'];?>'/></td>
				</tr>
				<tr>
					<td>Кор. счет</td>
					<td><input type="text" name="wpshop_payments_bank[ks]" value='<?php echo $this->bank['ks'];?>'/></td>
				</tr>
			</table>
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>Web-money</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Включить поддержку оплаты по Web-Money</td>
					<?php $wm_activate = $this->wm['activate'] ? " checked" : "";?>
					<td><input type="checkbox" name="wpshop_payments_wm[activate]"<?php echo $wm_activate;?>/></td>
				</tr>
				<tr>
					<td>Доставка</td>
					<td>
					<?php
						
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if (in_array($delivery->ID,$this->wm['delivery']))
							{
								$checked = " checked";
							}							
							echo "<input type='checkbox' name='wpshop_payments_wm[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
						}
						
					?>
					</td>
				</tr>
				
				<tr>
					<td>Ваш WM-Кошелек</td>
					<td><input type="text" name="wpshop_payments_wm[wmCheck]" value="<?php echo $this->wm['wmCheck'];?>"/></td>
				</tr>
				<tr>
					<td>Success URL</td>
					<td><input type="text" name="wpshop_payments_wm[successUrl]" value="<?php echo $this->wm['successUrl'];?>"/></td>
				</tr>
				<tr>
					<td>Failed URL</td>
					<td><input type="text" name="wpshop_payments_wm[failedUrl]" value="<?php echo $this->wm['failedUrl'];?>"/></td>
				</tr>
			</table>
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>RoboKassa</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Включить поддержку робокассы</td>
					<?php $robokassa_activate = $this->robokassa['activate'] ? " checked" : "";?>
					<td><input type="checkbox" name="wpshop_payments_robokassa[activate]"<?php echo $robokassa_activate;?>/></td>
				</tr>
				<tr>
					<td>Доставка</td>
					<td>
					<?php
						foreach($this->deliveries as $delivery)
						{
							$checked = "";
							if (in_array($delivery->ID,$this->robokassa['delivery']))
							{
								$checked = " checked";
							}							
							echo "<input type='checkbox' name='wpshop_payments_robokassa[delivery][]' value='{$delivery->ID}'{$checked}/> <label>{$delivery->name}</label><br/>";
						}
					?>
					</td>
				</tr>
				<tr>
					<td>Robokassa Login</td>
					<td><input type="text" name="wpshop_payments_robokassa[login]" value="<?php echo $this->robokassa['login'];?>"/></td>
				</tr>
				<tr>
					<td>Robokassa пароль 1</td>
					<td><input type="text" name="wpshop_payments_robokassa[pass1]" value="<?php echo $this->robokassa['pass1'];?>"/></td>
				</tr>
				<tr>
					<td>Robokassa пароль 2</td>
					<td><input type="text" name="wpshop_payments_robokassa[pass2]" value="<?php echo $this->robokassa['pass2'];?>"/></td>
				</tr>
			</table>
			</div>
		</div>
	</div>	
	<?php /*
	<div id="poststuff">
		<div class="postbox">
			<h3>PayPal</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Включить поддержку PayPal</td>
					<?php $paypal_activate = $this->paypal['activate'] ? " checked" : "";?>
					<td><input type="checkbox" name="wpshop_payments_paypal[activate]"<?php echo $paypal_activate;?>/></td>
				</tr>
				<tr>
					<td>URL страницы с формой оплаты</td>
					<td><input type="text" name="wpshop_payments_paypal[cart_url]" value="<?php echo $this->paypal['cart_url'];?>" style='width:400px;'/></td>
				</tr>
<!--				
				<tr>
					<td>Форма cforms</td>
					<td>
					<select name='wpshop_payments_paypal[cform]'>
					<?php 
					foreach ($this->cforms as $value)
					{
						if ($this->paypal['cform'] == $value['name'])
						{
							$selected = ' selected';
						}
						else
						{
							$selected = '';
						}
						echo "<option value='{$value['name']}'{$selected}>{$value['name']}</option>";
					}
					?>
					</select>
					</td>
				</tr>-->
			</table>
			</div>
		</div>
	</div-->
	*/?>
	<input type="submit" value="Сохранить" class="button">
</form>
</div>