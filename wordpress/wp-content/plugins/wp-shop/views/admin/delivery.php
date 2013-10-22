<div class="wrap">
<h2><?php echo __("Доставка",'wpshop');?></h2>
	<form action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
	<input type="hidden" name="update" value="1"/>
	<div id="poststuff">
		<div class="postbox">
			<h3>Почта по стране</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Стоимость</td>
					<td><input type="text" name="wpshop_delivery[postByCountry][cost]" value="<?php echo $this->delivery['postByCountry']['cost']; ?>"/></td>
				</tr>				
			</table>
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>Международная почта</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Стоимость</td>
					<td><input type="text" name="wpshop_delivery[postByWorld][cost]" value="<?php echo $this->delivery['postByWorld']['cost']; ?>"/></td>
				</tr>				
			</table>
			</div>
		</div>
	</div>
	
	<div id="poststuff">
		<div class="postbox">
			<h3>Курьерская доставка</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Стоимость</td>
					<td><input type="text" name="wpshop_delivery[courier][cost]" value="<?php echo $this->delivery['courier']['cost']; ?>"/></td>
				</tr>
			</table>
			</div>
		</div>
		
		<div class="postbox">
			<h3>Визит о офис</h3>
			<div class="inside">
			<table cellpadding="2" cellspacing="2">
				<tr>
					<td style='width:400px;'>Стоимость</td>
					<td><input type="text" name="wpshop_delivery[vizit][cost]" value="<?php echo $this->delivery['vizit']['cost']; ?>"/></td>
				</tr>
			</table>
			</div>
		</div>		
		
	</div>	
	<input type="submit" value="Сохранить" class="button">	
	</form>
</div>